<?php

namespace WechatOfficialAccountCardBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WechatOfficialAccountBundle\Repository\AccountRepository;
use WechatOfficialAccountBundle\Service\OfficialAccountClient;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Entity\Embed\CardDateInfo;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Repository\CardRepository;
use WechatOfficialAccountCardBundle\Request\Basic\BatchGetListRequest;
use WechatOfficialAccountCardBundle\Request\Basic\GetDetailRequest;

#[AsCommand(
    name: self::NAME,
    description: '同步微信卡券信息',
)]
class SyncCardCommand extends Command
{
    public const NAME = 'wechat:card:sync';
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AccountRepository $accountRepository,
        private readonly CardRepository $cardRepository,
        private readonly OfficialAccountClient $client,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accounts = $this->accountRepository->findAll();

        foreach ($accounts as $account) {
            $output->writeln(sprintf('正在同步账号 %s 的卡券信息...', $account->getName()));

            $offset = 0;
            $count = 50;

            do {
                $batchGetListRequest = new BatchGetListRequest();
                $batchGetListRequest->setAccount($account);
                $batchGetListRequest->setOffset($offset);
                $batchGetListRequest->setCount($count);

                $response = $this->client->request($batchGetListRequest);
                $cardList = $response['card_id_list'] ?? [];

                foreach ($cardList as $cardId) {
                    $getDetailRequest = new GetDetailRequest();
                    $getDetailRequest->setAccount($account);
                    $getDetailRequest->setCardId($cardId);

                    $cardDetail = $this->client->request($getDetailRequest);
                    $cardInfo = $cardDetail['card'];

                    // 查找或创建卡券
                    $card = $this->cardRepository->findOneBy(['cardId' => $cardId]) ?? new Card();
                    $card->setSyncing(true);

                    // 设置基本信息
                    $card->setAccount($account);
                    $card->setCardId($cardId);
                    $card->setCardType(CardType::from($cardInfo['card_type']));

                    // 获取具体卡券类型的信息
                    $typeInfo = $cardInfo[strtolower($cardInfo['card_type'])];
                    $baseInfo = $typeInfo['base_info'];

                    // 设置或创建 CardBaseInfo
                    if (!$card->getBaseInfo()) {
                        $card->setBaseInfo(new CardBaseInfo());
                    }

                    // 设置基本信息
                    $card->getBaseInfo()
                        ->setLogoUrl($baseInfo['logo_url'])
                        ->setBrandName($baseInfo['brand_name'])
                        ->setTitle($baseInfo['title'])
                        ->setColor($baseInfo['color'])
                        ->setNotice($baseInfo['notice'])
                        ->setDescription($baseInfo['description'])
                        ->setQuantity($baseInfo['sku']['quantity'])
                        ->setUseLimit($baseInfo['use_limit'])
                        ->setGetLimit($baseInfo['get_limit'])
                        ->setCanShare($baseInfo['can_share'])
                        ->setCanGiveFriend($baseInfo['can_give_friend']);

                    // 设置或创建 CardDateInfo
                    if (!$card->getBaseInfo()->getDateInfo()) {
                        $card->getBaseInfo()->setDateInfo(new CardDateInfo());
                    }

                    // 设置日期信息
                    $dateInfo = $baseInfo['date_info'];
                    $card->getBaseInfo()->getDateInfo()
                        ->setType($dateInfo['type'])
                        ->setBeginTimestamp($dateInfo['begin_timestamp'] ?? null)
                        ->setEndTimestamp($dateInfo['end_timestamp'] ?? null)
                        ->setFixedTerm($dateInfo['fixed_term'] ?? null)
                        ->setFixedBeginTerm($dateInfo['fixed_begin_term'] ?? null);

                    if (!$card->getId()) {
                        $this->entityManager->persist($card);
                    }
                }

                $this->entityManager->flush();
                $offset += $count;
            } while (count($cardList) === $count);
        }

        return Command::SUCCESS;
    }
}

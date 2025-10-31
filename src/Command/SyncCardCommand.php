<?php

namespace WechatOfficialAccountCardBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountBundle\Repository\AccountRepository;
use WechatOfficialAccountBundle\Service\OfficialAccountClient;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\DateType;
use WechatOfficialAccountCardBundle\Repository\CardRepository;
use WechatOfficialAccountCardBundle\Request\Basic\BatchGetListRequest;
use WechatOfficialAccountCardBundle\Request\Basic\GetDetailRequest;

#[AsCommand(
    name: self::NAME,
    description: '同步微信卡券信息',
)]
#[Autoconfigure(public: true)]
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
            $this->syncCardsForAccount($account);
        }

        return Command::SUCCESS;
    }

    private function syncCardsForAccount(Account $account): void
    {
        $offset = 0;
        $count = 50;

        do {
            $cardList = $this->getCardList($account, $offset, $count);

            foreach ($cardList as $cardId) {
                $this->syncSingleCard($account, $cardId);
            }

            $this->entityManager->flush();
            $offset += $count;
        } while (count($cardList) === $count);
    }

    /**
     * @return string[]
     */
    private function getCardList(Account $account, int $offset, int $count): array
    {
        $batchGetListRequest = new BatchGetListRequest();
        $batchGetListRequest->setAccount($account);
        $batchGetListRequest->setOffset($offset);
        $batchGetListRequest->setCount($count);

        $response = $this->client->request($batchGetListRequest);

        if (!is_array($response) || !isset($response['card_id_list']) || !is_array($response['card_id_list'])) {
            return [];
        }

        /** @var array<string> $cardIdList */
        $cardIdList = array_filter($response['card_id_list'], static fn ($item): bool => is_string($item));

        return $cardIdList;
    }

    private function syncSingleCard(Account $account, string $cardId): void
    {
        $cardDetail = $this->getCardDetail($account, $cardId);
        if (!isset($cardDetail['card']) || !is_array($cardDetail['card'])) {
            return;
        }

        /** @var array<string, mixed> $cardInfo */
        $cardInfo = $cardDetail['card'];
        if (!isset($cardInfo['card_type']) || !is_string($cardInfo['card_type'])) {
            return;
        }

        $card = $this->findOrCreateCard($cardId);
        $card->setSyncing(true);
        $card->setAccount($account);
        $card->setCardId($cardId);
        $card->setCardType(CardType::from($cardInfo['card_type']));

        $this->setCardBaseInfo($card, $cardInfo);

        if (null === $card->getId()) {
            $this->entityManager->persist($card);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function getCardDetail(Account $account, string $cardId): array
    {
        $getDetailRequest = new GetDetailRequest();
        $getDetailRequest->setAccount($account);
        $getDetailRequest->setCardId($cardId);

        $result = $this->client->request($getDetailRequest);

        if (!is_array($result)) {
            return [];
        }

        /** @var array<string, mixed> $result */
        return $result;
    }

    private function findOrCreateCard(string $cardId): Card
    {
        return $this->cardRepository->findOneBy(['cardId' => $cardId]) ?? new Card();
    }

    /**
     * @param array<string, mixed> $cardInfo
     */
    private function setCardBaseInfo(Card $card, array $cardInfo): void
    {
        $baseInfo = $this->extractBaseInfo($cardInfo);
        if (null === $baseInfo) {
            return;
        }

        $cardBaseInfo = $card->getBaseInfo();

        $this->setCardBasicProperties($cardBaseInfo, $baseInfo);
        $this->setCardLimitsAndPermissions($cardBaseInfo, $baseInfo);

        if (isset($baseInfo['date_info']) && is_array($baseInfo['date_info'])) {
            /** @var array<string, mixed> $dateInfoArray */
            $dateInfoArray = $baseInfo['date_info'];
            $this->setCardDateInfo($card, $dateInfoArray);
        }
    }

    /**
     * @param array<string, mixed> $cardInfo
     * @return array<string, mixed>|null
     */
    private function extractBaseInfo(array $cardInfo): ?array
    {
        if (!isset($cardInfo['card_type']) || !is_string($cardInfo['card_type'])) {
            return null;
        }

        $cardTypeKey = strtolower($cardInfo['card_type']);
        if (!isset($cardInfo[$cardTypeKey]) || !is_array($cardInfo[$cardTypeKey])) {
            return null;
        }

        /** @var array<string, mixed> $typeInfo */
        $typeInfo = $cardInfo[$cardTypeKey];
        if (!isset($typeInfo['base_info']) || !is_array($typeInfo['base_info'])) {
            return null;
        }

        /** @var array<string, mixed> */
        return $typeInfo['base_info'];
    }

    /**
     * @param array<string, mixed> $baseInfo
     */
    private function setCardBasicProperties(CardBaseInfo $cardBaseInfo, array $baseInfo): void
    {
        $this->setStringField($baseInfo, 'logo_url', $cardBaseInfo->setLogoUrl(...));
        $this->setStringField($baseInfo, 'brand_name', $cardBaseInfo->setBrandName(...));
        $this->setStringField($baseInfo, 'title', $cardBaseInfo->setTitle(...));
        $this->setStringField($baseInfo, 'notice', $cardBaseInfo->setNotice(...));
        $this->setStringField($baseInfo, 'description', $cardBaseInfo->setDescription(...));

        if (isset($baseInfo['color']) && is_string($baseInfo['color'])) {
            $cardBaseInfo->setColor(CardColor::from($baseInfo['color']));
        }
    }

    /**
     * @param array<string, mixed> $baseInfo
     */
    private function setCardLimitsAndPermissions(CardBaseInfo $cardBaseInfo, array $baseInfo): void
    {
        if (isset($baseInfo['sku']) && is_array($baseInfo['sku']) && isset($baseInfo['sku']['quantity']) && is_int($baseInfo['sku']['quantity'])) {
            $cardBaseInfo->setQuantity($baseInfo['sku']['quantity']);
        }

        $this->setIntField($baseInfo, 'use_limit', $cardBaseInfo->setUseLimit(...));
        $this->setIntField($baseInfo, 'get_limit', $cardBaseInfo->setGetLimit(...));
        $this->setBoolField($baseInfo, 'can_share', $cardBaseInfo->setCanShare(...));
        $this->setBoolField($baseInfo, 'can_give_friend', $cardBaseInfo->setCanGiveFriend(...));
    }

    /**
     * @param array<string, mixed> $dateInfo
     */
    private function setCardDateInfo(Card $card, array $dateInfo): void
    {
        $dateInfoObj = $card->getBaseInfo()->getDateInfo();

        if (isset($dateInfo['type']) && is_string($dateInfo['type'])) {
            $dateInfoObj->setType(DateType::from($dateInfo['type']));
        }

        $this->setNullableIntField(
            $dateInfo,
            'begin_timestamp',
            $dateInfoObj->setBeginTimestamp(...)
        );

        $this->setNullableIntField(
            $dateInfo,
            'end_timestamp',
            $dateInfoObj->setEndTimestamp(...)
        );

        $this->setNullableIntField(
            $dateInfo,
            'fixed_term',
            $dateInfoObj->setFixedTerm(...)
        );

        $this->setNullableIntField(
            $dateInfo,
            'fixed_begin_term',
            $dateInfoObj->setFixedBeginTerm(...)
        );
    }

    /**
     * @param array<string, mixed> $data
     * @param callable(string): void $setter
     */
    private function setStringField(array $data, string $key, callable $setter): void
    {
        if (isset($data[$key]) && is_string($data[$key])) {
            $setter($data[$key]);
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param callable(int): void $setter
     */
    private function setIntField(array $data, string $key, callable $setter): void
    {
        if (isset($data[$key]) && is_int($data[$key])) {
            $setter($data[$key]);
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param callable(bool): void $setter
     */
    private function setBoolField(array $data, string $key, callable $setter): void
    {
        if (isset($data[$key]) && is_bool($data[$key])) {
            $setter($data[$key]);
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param callable(int|null): void $setter
     */
    private function setNullableIntField(array $data, string $key, callable $setter): void
    {
        $value = $data[$key] ?? null;
        if (null !== $value && is_int($value)) {
            $setter($value);
        } elseif (null === $value) {
            $setter(null);
        }
    }
}

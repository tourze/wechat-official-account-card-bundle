<?php

namespace WechatOfficialAccountCardBundle\Tests\Integration\Command;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WechatOfficialAccountBundle\Repository\AccountRepository;
use WechatOfficialAccountBundle\Service\OfficialAccountClient;
use WechatOfficialAccountCardBundle\Command\SyncCardCommand;
use WechatOfficialAccountCardBundle\Repository\CardRepository;

class SyncCardCommandTest extends TestCase
{
    private SyncCardCommand $command;
    private EntityManagerInterface $entityManager;
    private AccountRepository $accountRepository;
    private CardRepository $cardRepository;
    private OfficialAccountClient $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->accountRepository = $this->createMock(AccountRepository::class);
        $this->cardRepository = $this->createMock(CardRepository::class);
        $this->client = $this->createMock(OfficialAccountClient::class);
        
        $this->command = new SyncCardCommand(
            $this->entityManager,
            $this->accountRepository,
            $this->cardRepository,
            $this->client
        );
    }
    
    public function testCommandExtendsSymfonyCommand(): void
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }
    
    public function testCommandName(): void
    {
        $this->assertEquals('wechat:card:sync', SyncCardCommand::NAME);
        $this->assertEquals('wechat:card:sync', $this->command->getName());
    }
    
    public function testCommandDescription(): void
    {
        $this->assertEquals('同步微信卡券信息', $this->command->getDescription());
    }
    
    public function testExecuteWithNoAccounts(): void
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);
        
        $this->accountRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        
        $output->expects($this->never())
            ->method('writeln');
        
        $result = $this->command->run($input, $output);
        
        $this->assertEquals(Command::SUCCESS, $result);
    }
    
    public function testCommandInstantiation(): void
    {
        $command = new SyncCardCommand(
            $this->entityManager,
            $this->accountRepository,
            $this->cardRepository,
            $this->client
        );
        
        $this->assertInstanceOf(SyncCardCommand::class, $command);
    }
}
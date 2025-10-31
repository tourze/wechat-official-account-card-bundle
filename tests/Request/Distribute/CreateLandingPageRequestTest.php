<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Distribute;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\LandingPageBannerType;
use WechatOfficialAccountCardBundle\Request\Distribute\CreateLandingPageRequest;

/**
 * @internal
 */
#[CoversClass(CreateLandingPageRequest::class)]
final class CreateLandingPageRequestTest extends RequestTestCase
{
    private CreateLandingPageRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateLandingPageRequest();
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('https://api.weixin.qq.com/card/landingpage/create', $this->request->getRequestPath());
    }

    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(CreateLandingPageRequest::class, $this->request);
    }

    public function testSetBanner(): void
    {
        $banner = 'https://example.com/banner.jpg';
        $this->request->setBanner($banner);

        $this->assertEquals($banner, $this->request->getBanner());
    }

    public function testSetPageTitle(): void
    {
        $pageTitle = 'Test Landing Page';
        $this->request->setPageTitle($pageTitle);

        $this->assertEquals($pageTitle, $this->request->getPageTitle());
    }

    public function testSetCanShare(): void
    {
        $this->request->setCanShare(false);
        $this->assertFalse($this->request->isCanShare());

        $this->request->setCanShare(true);
        $this->assertTrue($this->request->isCanShare());
    }

    public function testDefaultCanShareIsTrue(): void
    {
        $this->assertTrue($this->request->isCanShare());
    }

    public function testSetBannerType(): void
    {
        $bannerType = LandingPageBannerType::BANNER;
        $this->request->setBannerType($bannerType);

        $this->assertEquals($bannerType, $this->request->getBannerType());
    }

    public function testSetCardList(): void
    {
        $cardList = [
            ['cardId' => 'card1', 'thumbUrl' => 'thumb1.jpg'],
            ['cardId' => 'card2', 'thumbUrl' => 'thumb2.jpg'],
        ];

        $this->request->setCardList($cardList);
        $this->assertEquals($cardList, $this->request->getCardList());
    }

    public function testAddCard(): void
    {
        $cardId = 'test_card_id';
        $thumbUrl = 'https://example.com/thumb.jpg';

        $this->request->addCard($cardId, $thumbUrl);

        $cardList = $this->request->getCardList();
        $this->assertCount(1, $cardList);
        $this->assertEquals($cardId, $cardList[0]['cardId']);
        $this->assertEquals($thumbUrl, $cardList[0]['thumbUrl']);

        // 测试添加多个卡券
        $this->request->addCard('card2', 'thumb2.jpg');
        $cardList = $this->request->getCardList();
        $this->assertCount(2, $cardList);
    }

    public function testGetRequestOptions(): void
    {
        $this->request->setBanner('https://example.com/banner.jpg');
        $this->request->setPageTitle('Test Landing Page');
        $this->request->setCanShare(false);
        $this->request->setBannerType(LandingPageBannerType::BANNER);
        $this->request->addCard('card_001', 'https://example.com/thumb1.jpg');
        $this->request->addCard('card_002', 'https://example.com/thumb2.jpg');

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $data = $options['json'];
        $this->assertIsArray($data);

        $this->assertEquals('https://example.com/banner.jpg', $data['banner']);
        $this->assertEquals('Test Landing Page', $data['page_title']);
        $this->assertFalse($data['can_share']);
        $this->assertEquals(LandingPageBannerType::BANNER->value, $data['scene']);

        $this->assertArrayHasKey('card_list', $data);
        $cardList = $data['card_list'];
        $this->assertIsArray($cardList);

        $card0 = $cardList[0];
        $this->assertIsArray($card0);
        $this->assertEquals('card_001', $card0['card_id']);
        $this->assertEquals('https://example.com/thumb1.jpg', $card0['thumb_url']);

        $card1 = $cardList[1];
        $this->assertIsArray($card1);
        $this->assertEquals('card_002', $card1['card_id']);
        $this->assertEquals('https://example.com/thumb2.jpg', $card1['thumb_url']);
    }

    public function testGetRequestOptionsWithDifferentBannerTypes(): void
    {
        $this->request->setBanner('https://example.com/banner.jpg');
        $this->request->setPageTitle('Test Page');
        $this->request->setCanShare(true);
        $this->request->addCard('card_001', 'https://example.com/thumb.jpg');

        // 测试 URL 场景
        $this->request->setBannerType(LandingPageBannerType::URL);
        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $data = $options['json'];
        $this->assertIsArray($data);
        $this->assertEquals(LandingPageBannerType::URL->value, $data['scene']);

        // 测试 CELL 场景
        $this->request->setBannerType(LandingPageBannerType::CELL);
        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $data = $options['json'];
        $this->assertIsArray($data);
        $this->assertEquals(LandingPageBannerType::CELL->value, $data['scene']);
    }

    public function testGetRequestOptionsWithEmptyCardList(): void
    {
        $this->request->setBanner('https://example.com/banner.jpg');
        $this->request->setPageTitle('Test Page');
        $this->request->setCanShare(true);
        $this->request->setBannerType(LandingPageBannerType::BANNER);

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $data = $options['json'];
        $this->assertIsArray($data);

        $this->assertArrayHasKey('card_list', $data);
        $this->assertIsArray($data['card_list']);
        $this->assertEmpty($data['card_list']);
    }
}

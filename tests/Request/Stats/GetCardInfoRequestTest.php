<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Stats;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardCondSource;
use WechatOfficialAccountCardBundle\Request\Stats\GetCardInfoRequest;

/**
 * @internal
 */
#[CoversClass(GetCardInfoRequest::class)]
final class GetCardInfoRequestTest extends RequestTestCase
{
    private GetCardInfoRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetCardInfoRequest();
    }

    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetCardInfoRequest::class, $this->request);
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('https://api.weixin.qq.com/datacube/getcardcardinfo', $this->request->getRequestPath());
    }

    public function testBeginDateGetterAndSetter(): void
    {
        $date = new \DateTime('2023-01-01');
        $this->request->setBeginDate($date);

        $this->assertSame($date, $this->request->getBeginDate());
    }

    public function testEndDateGetterAndSetter(): void
    {
        $date = new \DateTime('2023-01-31');
        $this->request->setEndDate($date);

        $this->assertSame($date, $this->request->getEndDate());
    }

    public function testCondSourceGetterAndSetter(): void
    {
        $condSource = CardCondSource::API;
        $this->request->setCondSource($condSource);

        $this->assertSame($condSource, $this->request->getCondSource());
    }

    public function testCardIdGetterAndSetter(): void
    {
        $cardId = 'pFS7wjgaJBEfMIDRYj9rltBtVA_E';
        $this->request->setCardId($cardId);

        $this->assertEquals($cardId, $this->request->getCardId());
    }

    public function testGetRequestOptions(): void
    {
        $beginDate = new \DateTime('2023-01-01');
        $endDate = new \DateTime('2023-01-31');
        $condSource = CardCondSource::PLATFORM;
        $cardId = 'pFS7wjgaJBEfMIDRYj9rltBtVA_E';

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);
        $this->request->setCondSource($condSource);
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'begin_date' => '2023-01-01',
            'end_date' => '2023-01-31',
            'cond_source' => 0,
            'card_id' => 'pFS7wjgaJBEfMIDRYj9rltBtVA_E',
        ], $options['json']);
    }

    public function testGetRequestOptionsWithDifferentParameters(): void
    {
        $beginDate = new \DateTime('2023-02-01');
        $endDate = new \DateTime('2023-02-28');
        $condSource = CardCondSource::API;
        $cardId = 'pFS7wjgaJBEfMIDRYj9rltBtVA_F';

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);
        $this->request->setCondSource($condSource);
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'begin_date' => '2023-02-01',
            'end_date' => '2023-02-28',
            'cond_source' => 1,
            'card_id' => 'pFS7wjgaJBEfMIDRYj9rltBtVA_F',
        ], $options['json']);
    }

    public function testDateFormattingInRequestOptions(): void
    {
        $beginDate = new \DateTime('2023-03-15 14:30:25');
        $endDate = new \DateTime('2023-03-20 09:45:10');
        $condSource = CardCondSource::PLATFORM;
        $cardId = 'testCardId123';

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);
        $this->request->setCondSource($condSource);
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals('2023-03-15', $json['begin_date']);
        $this->assertEquals('2023-03-20', $json['end_date']);
        $this->assertEquals('testCardId123', $json['card_id']);
    }
}

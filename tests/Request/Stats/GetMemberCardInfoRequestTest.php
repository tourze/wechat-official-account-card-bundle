<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Stats;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardCondSource;
use WechatOfficialAccountCardBundle\Request\Stats\GetMemberCardInfoRequest;

/**
 * @internal
 */
#[CoversClass(GetMemberCardInfoRequest::class)]
final class GetMemberCardInfoRequestTest extends RequestTestCase
{
    private GetMemberCardInfoRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetMemberCardInfoRequest();
    }

    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetMemberCardInfoRequest::class, $this->request);
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('https://api.weixin.qq.com/datacube/getcardmembercardinfo', $this->request->getRequestPath());
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

    public function testGetRequestOptions(): void
    {
        $beginDate = new \DateTime('2023-01-01');
        $endDate = new \DateTime('2023-01-31');
        $condSource = CardCondSource::PLATFORM;

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);
        $this->request->setCondSource($condSource);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'begin_date' => '2023-01-01',
            'end_date' => '2023-01-31',
            'cond_source' => 0,
        ], $options['json']);
    }

    public function testGetRequestOptionsWithDifferentCondSource(): void
    {
        $beginDate = new \DateTime('2023-02-01');
        $endDate = new \DateTime('2023-02-28');
        $condSource = CardCondSource::API;

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);
        $this->request->setCondSource($condSource);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'begin_date' => '2023-02-01',
            'end_date' => '2023-02-28',
            'cond_source' => 1,
        ], $options['json']);
    }

    public function testDateFormattingInRequestOptions(): void
    {
        $beginDate = new \DateTime('2023-03-15 14:30:25');
        $endDate = new \DateTime('2023-03-20 09:45:10');
        $condSource = CardCondSource::PLATFORM;

        $this->request->setBeginDate($beginDate);
        $this->request->setEndDate($endDate);
        $this->request->setCondSource($condSource);

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals('2023-03-15', $json['begin_date']);
        $this->assertEquals('2023-03-20', $json['end_date']);
    }
}

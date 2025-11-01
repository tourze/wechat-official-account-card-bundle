<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Distribute;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Exception\CodeListExceededException;
use WechatOfficialAccountCardBundle\Request\Distribute\ImportCodeRequest;

/**
 * @internal
 */
#[CoversClass(ImportCodeRequest::class)]
final class ImportCodeRequestTest extends RequestTestCase
{
    private ImportCodeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new ImportCodeRequest();
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('https://api.weixin.qq.com/card/code/deposit', $this->request->getRequestPath());
    }

    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(ImportCodeRequest::class, $this->request);
    }

    public function testSetCardId(): void
    {
        $cardId = 'test_card_id_12345';
        $this->request->setCardId($cardId);

        $this->assertEquals($cardId, $this->request->getCardId());
    }

    public function testSetCodeList(): void
    {
        $codeList = ['CODE_001', 'CODE_002', 'CODE_003'];
        $this->request->setCodeList($codeList);

        $this->assertEquals($codeList, $this->request->getCodeList());
    }

    public function testSetCodeListWithEmptyArray(): void
    {
        $this->request->setCodeList([]);

        $this->assertEmpty($this->request->getCodeList());
    }

    public function testSetCodeListExceedsLimit(): void
    {
        $codeList = array_fill(0, 101, 'code');

        $this->expectException(CodeListExceededException::class);
        $this->expectExceptionMessage('Code list cannot exceed 100 items, but 101 given');

        $this->request->setCodeList($codeList);
    }

    public function testSetCodeListAtLimit(): void
    {
        $codeList = array_fill(0, 100, 'code');

        $this->request->setCodeList($codeList);

        $this->assertCount(100, $this->request->getCodeList());
    }

    public function testAddCode(): void
    {
        $code = 'TEST_CODE_001';

        $this->request->addCode($code);

        $codeList = $this->request->getCodeList();
        $this->assertCount(1, $codeList);
        $this->assertEquals($code, $codeList[0]);

        // 测试添加多个code
        $this->request->addCode('TEST_CODE_002');
        $codeList = $this->request->getCodeList();
        $this->assertCount(2, $codeList);
        $this->assertEquals('TEST_CODE_002', $codeList[1]);
    }

    public function testAddCodeWhenAtLimit(): void
    {
        $this->request->setCodeList(array_fill(0, 100, 'code'));

        $this->expectException(CodeListExceededException::class);
        $this->expectExceptionMessage('Code list cannot exceed 100 items, but 101 given');

        $this->request->addCode('code_101');
    }

    public function testAddCodeReachingLimit(): void
    {
        $this->request->setCodeList(array_fill(0, 99, 'code'));

        $this->request->addCode('code_100');

        $this->assertCount(100, $this->request->getCodeList());
        $this->assertEquals('code_100', $this->request->getCodeList()[99]);
    }

    public function testGetRequestOptions(): void
    {
        $cardId = 'test_card_001';
        $codeList = ['CODE_001', 'CODE_002', 'CODE_003'];

        $this->request->setCardId($cardId);
        $this->request->setCodeList($codeList);

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);

        $this->assertEquals($cardId, $json['card_id']);
        $this->assertEquals($codeList, $json['code']);
    }

    public function testGetRequestOptionsWithEmptyCodeList(): void
    {
        $cardId = 'test_card_001';

        $this->request->setCardId($cardId);
        $this->request->setCodeList([]);

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);

        $this->assertEquals($cardId, $json['card_id']);
        $this->assertEmpty($json['code']);
    }

    public function testGetRequestOptionsWithMaxCodeList(): void
    {
        $cardId = 'test_card_001';
        $codeList = array_map(fn ($i) => 'CODE_' . str_pad((string) $i, 3, '0', STR_PAD_LEFT), range(1, 100));

        $this->request->setCardId($cardId);
        $this->request->setCodeList($codeList);

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);

        $this->assertEquals($cardId, $json['card_id']);
        $code = $json['code'];
        $this->assertIsArray($code);
        $this->assertEquals('CODE_001', $code[0]);
        $this->assertEquals('CODE_100', $code[99]);
    }

    public function testGetRequestOptionsStructure(): void
    {
        $this->request->setCardId('test_card');
        $this->request->addCode('TEST_CODE');

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertCount(1, $options);
        $this->assertArrayHasKey('json', $options);

        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('card_id', $json);
        $this->assertArrayHasKey('code', $json);
        $this->assertSame('test_card', $json['card_id']);
        $this->assertSame(['TEST_CODE'], $json['code']);
    }

    public function testCodeListOperationsChain(): void
    {
        $this->request->addCode('CODE_001');
        $this->request->addCode('CODE_002');

        $initialCount = count($this->request->getCodeList());
        $this->assertEquals(2, $initialCount);

        $this->request->setCodeList(['NEW_CODE_001', 'NEW_CODE_002', 'NEW_CODE_003']);
        $newCount = count($this->request->getCodeList());
        $this->assertEquals(3, $newCount);

        $this->request->addCode('ADDITIONAL_CODE');
        $finalCount = count($this->request->getCodeList());
        $this->assertEquals(4, $finalCount);

        $finalList = $this->request->getCodeList();
        $this->assertEquals('NEW_CODE_001', $finalList[0]);
        $this->assertEquals('NEW_CODE_002', $finalList[1]);
        $this->assertEquals('NEW_CODE_003', $finalList[2]);
        $this->assertEquals('ADDITIONAL_CODE', $finalList[3]);
    }
}

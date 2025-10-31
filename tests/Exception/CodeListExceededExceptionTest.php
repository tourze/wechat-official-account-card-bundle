<?php

namespace WechatOfficialAccountCardBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatOfficialAccountCardBundle\Exception\CodeListExceededException;

/**
 * @internal
 */
#[CoversClass(CodeListExceededException::class)]
final class CodeListExceededExceptionTest extends AbstractExceptionTestCase
{
    public function testExceptionExtendsRuntimeException(): void
    {
        $exception = new CodeListExceededException(100, 150);

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testExceptionMessage(): void
    {
        $exception = new CodeListExceededException(100, 150);

        $this->assertEquals(
            'Code list cannot exceed 100 items, but 150 given',
            $exception->getMessage()
        );
    }

    public function testExceptionWithDifferentValues(): void
    {
        $exception = new CodeListExceededException(50, 75);

        $this->assertEquals(
            'Code list cannot exceed 50 items, but 75 given',
            $exception->getMessage()
        );
    }

    public function testExceptionWithZeroValues(): void
    {
        $exception = new CodeListExceededException(0, 1);

        $this->assertEquals(
            'Code list cannot exceed 0 items, but 1 given',
            $exception->getMessage()
        );
    }

    public function testExceptionCanBeThrown(): void
    {
        $this->expectException(CodeListExceededException::class);
        $this->expectExceptionMessage('Code list cannot exceed 10 items, but 20 given');

        throw new CodeListExceededException(10, 20);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new CodeListExceededException(5, 10);
        } catch (CodeListExceededException $e) {
            $this->assertEquals('Code list cannot exceed 5 items, but 10 given', $e->getMessage());
        }
    }
}

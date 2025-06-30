<?php

namespace WechatOfficialAccountCardBundle\Exception;

/**
 * 卡券码列表超出限制异常
 */
class CodeListExceededException extends \RuntimeException
{
    public function __construct(int $limit, int $actual)
    {
        parent::__construct(sprintf('Code list cannot exceed %d items, but %d given', $limit, $actual));
    }
}
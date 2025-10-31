<?php

declare(strict_types=1);

namespace WechatOfficialAccountCardBundle\DependencyInjection;

use Tourze\SymfonyDependencyServiceLoader\AutoExtension;

class WechatOfficialAccountCardExtension extends AutoExtension
{
    protected function getConfigDir(): string
    {
        return __DIR__ . '/../Resources/config';
    }
}

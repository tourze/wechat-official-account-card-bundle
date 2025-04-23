<?php

namespace WechatOfficialAccountCardBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;

#[AsPermission(title: '微信卡券')]
class WechatOfficialAccountCardBundle extends Bundle
{
}

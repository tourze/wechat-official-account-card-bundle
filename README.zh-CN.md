# 微信公众号卡券 Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue.svg?style=flat-square)]()
[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-official-account-card-bundle.svg?style=flat-square)]
(https://packagist.org/packages/tourze/wechat-official-account-card-bundle)
[![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)]()
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-official-account-card-bundle.svg?style=flat-square)]
(https://packagist.org/packages/tourze/wechat-official-account-card-bundle)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)]()

一个全面的 Symfony Bundle，用于管理微信公众号卡券系统，提供微信卡券的完整
生命周期管理，包括创建、发放、核销和统计等功能。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [依赖要求](#依赖要求)
- [快速开始](#快速开始)
  - [Bundle 注册](#bundle-注册)
  - [基本使用](#基本使用)
- [配置](#配置)
- [控制台命令](#控制台命令)
  - [wechat:card:sync](#wechatcardsync)
- [高级用法](#高级用法)
  - [自定义卡券创建](#自定义卡券创建)
  - [事件处理](#事件处理)
- [API 参考](#api-参考)
  - [卡券操作](#卡券操作)
  - [卡券核销](#卡券核销)
  - [卡券投放](#卡券投放)
  - [卡券统计](#卡券统计)
- [实体结构](#实体结构)
  - [核心实体](#核心实体)
  - [嵌入实体](#嵌入实体)
- [安全](#安全)
  - [数据验证](#数据验证)
  - [访问控制](#访问控制)
- [贡献](#贡献)
- [许可证](#许可证)

## 功能特性

- **完整的卡券类型支持**：支持微信所有卡券类型（团购券、代金券、折扣券、兑换券、优惠券、会员卡、景点门票、电影票、飞机票、会议门票、汽车票）
- **卡券生命周期管理**：自动同步微信卡券状态，支持创建、更新、删除操作
- **卡券码管理**：支持卡券码的导入、核销、失效处理
- **用户领取记录**：完整记录用户领取、使用、转赠历史
- **数据统计功能**：按日统计卡券的领取、使用、转赠、浏览等数据
- **自动同步机制**：通过命令行工具同步微信卡券信息
- **事件监听器**：自动处理卡券的创建和更新操作

## 安装

```bash
composer require tourze/wechat-official-account-card-bundle
```

## 依赖要求

此 Bundle 需要：
- PHP 8.1 或更高版本
- Symfony 7.3 或更高版本
- tourze/wechat-official-account-bundle
- Doctrine ORM 3.0+

## 快速开始

### Bundle 注册

在 `config/bundles.php` 中添加 bundle：

```php
<?php

return [
    // ... 其他 bundles
    WechatOfficialAccountCardBundle\WechatOfficialAccountCardBundle::class => ['all' => true],
];
```

### 基本使用

```php
<?php

use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Request\Basic\CreateRequest;
use WechatOfficialAccountCardBundle\Request\Basic\GetDetailRequest;

// 创建新卡券
$createRequest = new CreateRequest();
$createRequest->setAccount($account);
$createRequest->setCardType(CardType::GROUPON);
// ... 配置其他卡券属性

$response = $officialAccountClient->request($createRequest);

// 获取卡券详情
$getDetailRequest = new GetDetailRequest();
$getDetailRequest->setAccount($account);
$getDetailRequest->setCardId($cardId);

$cardDetail = $officialAccountClient->request($getDetailRequest);
```

## 配置

该 bundle 与微信公众号 Bundle 集成，需要正确配置微信 API 凭据。请确保已正确配置 `wechat-official-account-bundle`。

## 控制台命令

### wechat:card:sync

同步所有账号的微信卡券信息。

```bash
php bin/console wechat:card:sync
```

该命令将：
- 遍历所有微信公众号账号
- 批量获取每个账号的卡券列表
- 获取每个卡券的详细信息
- 同步更新本地数据库
- 支持增量更新，避免重复创建

## 高级用法

### 自定义卡券创建

你可以创建自定义配置的卡券：

```php
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Entity\Embed\CardDateInfo;

$card = new Card();
$card->setCardType(CardType::CASH);

// 配置基础信息
$baseInfo = new CardBaseInfo();
$baseInfo->setTitle('10元代金券')
         ->setBrandName('商户名称')
         ->setQuantity(1000)
         ->setUseLimit(1);

$card->setBaseInfo($baseInfo);
```

### 事件处理

Bundle 提供事件监听器用于自动卡券操作。你可以通过创建自己的监听器来扩展或
自定义行为。

## API 参考

### 卡券操作

- **创建卡券**：`CreateRequest` - 创建各种类型的微信卡券
- **获取卡券列表**：`BatchGetListRequest` - 分页获取账号卡券
- **获取卡券详情**：`GetDetailRequest` - 获取单个卡券的详细信息
- **更新卡券**：`UpdateRequest` - 更新卡券基本信息
- **删除卡券**：`DeleteRequest` - 删除指定卡券
- **修改库存**：`ModifyStockRequest` - 增减卡券库存

### 卡券核销

- **核销卡券**：`ConsumeRequest` - 核销用户的卡券
- **解码卡券码**：`DecryptCodeRequest` - 解密卡券 code
- **查询卡券码**：`GetCodeRequest` - 查询卡券码状态
- **获取用户卡券**：`GetUserListRequest` - 获取指定用户的卡券列表
- **设置失效**：`UnavailableRequest` - 使卡券失效
- **更新卡券码**：`UpdateCodeRequest` - 更新卡券码信息

### 卡券投放

- **创建货架**：`CreateLandingPageRequest` - 创建卡券投放页面
- **导入卡券码**：`ImportCodeRequest` - 批量导入自定义卡券码
- **查询导入数量**：`GetCodeDepositCountRequest` - 查询已导入的卡券码数量

### 卡券统计

- **获取商户信息**：`GetBizuinInfoRequest` - 获取商户统计信息
- **获取卡券统计**：`GetCardInfoRequest` - 获取卡券数据统计
- **获取会员卡详情**：`GetMemberCardDetailRequest` - 获取会员卡详细统计
- **获取会员卡信息**：`GetMemberCardInfoRequest` - 获取会员卡基础统计

## 实体结构

### 核心实体

- **Card**：卡券主实体，使用雪花算法 ID，时间戳追踪，关联微信账号
- **CardCode**：卡券码记录，状态追踪
- **CardReceive**：用户领取记录，支持转赠功能
- **CardStats**：各项指标的按日统计

### 嵌入实体

- **CardBaseInfo**：卡券基本信息（logo、名称、颜色、标题、说明、限制）
- **CardDateInfo**：有效期信息（固定时间段或相对时间）

## 安全

### 数据验证

所有实体都包含完整的验证约束以确保数据完整性：

- 字符串长度验证防止数据库溢出
- 枚举选择验证卡券类型和状态
- 正数验证数量和计数
- 必填字段验证关键属性

### 访问控制

在实现卡券操作时确保适当的访问控制：

- 在卡券创建/修改前验证用户权限
- 为卡券核销操作实施速率限制
- 使用适当的身份验证保护 API 端点
- 记录所有卡券相关操作以便审计

## 贡献

请查看 [CONTRIBUTING.md](CONTRIBUTING.md) 了解详情。

## 许可证

MIT 许可证。请查看 [License File](LICENSE) 了解更多信息。

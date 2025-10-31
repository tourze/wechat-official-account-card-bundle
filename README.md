# Wechat Official Account Card Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue.svg?style=flat-square)]()
[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-official-account-card-bundle.svg?style=flat-square)]
(https://packagist.org/packages/tourze/wechat-official-account-card-bundle)
[![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)]()
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-official-account-card-bundle.svg?style=flat-square)]
(https://packagist.org/packages/tourze/wechat-official-account-card-bundle)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)]()

A comprehensive Symfony Bundle for managing WeChat Official Account Card system, 
providing complete lifecycle management of WeChat cards including creation, 
distribution, consumption, and statistics.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Dependencies](#dependencies)
- [Quick Start](#quick-start)
  - [Bundle Registration](#bundle-registration)
  - [Basic Usage](#basic-usage)
- [Configuration](#configuration)
- [Console Commands](#console-commands)
  - [wechat:card:sync](#wechatcardsync)
- [Advanced Usage](#advanced-usage)
  - [Custom Card Creation](#custom-card-creation)
  - [Event Handling](#event-handling)
- [API Reference](#api-reference)
  - [Card Operations](#card-operations)
  - [Card Consumption](#card-consumption)
  - [Card Distribution](#card-distribution)
  - [Card Statistics](#card-statistics)
- [Entity Structure](#entity-structure)
  - [Core Entities](#core-entities)
  - [Embedded Entities](#embedded-entities)
- [Security](#security)
  - [Data Validation](#data-validation)
  - [Access Control](#access-control)
- [Contributing](#contributing)
- [License](#license)

## Features

- **Complete Card Type Support**: Support all WeChat card types (GroupBuy, Cash, 
  Discount, Gift, General Coupon, Member Card, Scenic Ticket, Movie Ticket, 
  Boarding Pass, Meeting Ticket, Bus Ticket)
- **Card Lifecycle Management**: Automatic synchronization of WeChat card status, 
  support for create, update, delete operations
- **Card Code Management**: Support for card code import, consumption, and invalidation
- **User Receive Records**: Complete tracking of user receive, use, and transfer history
- **Data Statistics**: Daily statistics for card receive, use, transfer, and view metrics
- **Auto Sync Mechanism**: Command-line tool for synchronizing WeChat card information
- **Event Listeners**: Automatic handling of card creation and update operations

## Installation

```bash
composer require tourze/wechat-official-account-card-bundle
```

## Dependencies

This bundle requires:
- PHP 8.1 or higher  
- Symfony 7.3 or higher
- tourze/wechat-official-account-bundle
- Doctrine ORM 3.0+

## Quick Start

### Bundle Registration

Add the bundle to your `config/bundles.php`:

```php
<?php

return [
    // ... other bundles
    WechatOfficialAccountCardBundle\WechatOfficialAccountCardBundle::class => ['all' => true],
];
```

### Basic Usage

```php
<?php

use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Request\Basic\CreateRequest;
use WechatOfficialAccountCardBundle\Request\Basic\GetDetailRequest;

// Create a new card
$createRequest = new CreateRequest();
$createRequest->setAccount($account);
$createRequest->setCardType(CardType::GROUPON);
// ... configure other card properties

$response = $officialAccountClient->request($createRequest);

// Get card details
$getDetailRequest = new GetDetailRequest();
$getDetailRequest->setAccount($account);
$getDetailRequest->setCardId($cardId);

$cardDetail = $officialAccountClient->request($getDetailRequest);
```

## Configuration

The bundle integrates with the WeChat Official Account Bundle and requires proper 
configuration of WeChat API credentials. Make sure you have configured the 
`wechat-official-account-bundle` properly.

## Console Commands

### wechat:card:sync

Synchronize WeChat card information for all accounts.

```bash
php bin/console wechat:card:sync
```

This command will:
- Iterate through all WeChat Official Account accounts
- Batch fetch card lists for each account
- Get detailed information for each card
- Synchronize and update local database
- Support incremental updates to avoid duplicate creation

## Advanced Usage

### Custom Card Creation

You can create cards with custom configurations:

```php
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Entity\Embed\CardDateInfo;

$card = new Card();
$card->setCardType(CardType::CASH);

// Configure base info
$baseInfo = new CardBaseInfo();
$baseInfo->setTitle('10元代金券')
         ->setBrandName('商户名称')
         ->setQuantity(1000)
         ->setUseLimit(1);

$card->setBaseInfo($baseInfo);
```

### Event Handling

The bundle provides event listeners for automatic card operations. You can extend 
or customize the behavior by creating your own listeners.

## API Reference

### Card Operations

- **Create Card**: `CreateRequest` - Create various types of WeChat cards
- **Get Card List**: `BatchGetListRequest` - Paginated retrieval of account cards
- **Get Card Detail**: `GetDetailRequest` - Get detailed information of a single card
- **Update Card**: `UpdateRequest` - Update card basic information
- **Delete Card**: `DeleteRequest` - Delete specified card
- **Modify Stock**: `ModifyStockRequest` - Increase or decrease card stock

### Card Consumption

- **Consume Card**: `ConsumeRequest` - Consume user's card
- **Decrypt Code**: `DecryptCodeRequest` - Decrypt card code
- **Get Code**: `GetCodeRequest` - Query card code status
- **Get User Cards**: `GetUserListRequest` - Get card list for specified user
- **Set Unavailable**: `UnavailableRequest` - Make card unavailable
- **Update Code**: `UpdateCodeRequest` - Update card code information

### Card Distribution

- **Create Landing Page**: `CreateLandingPageRequest` - Create card distribution page
- **Import Codes**: `ImportCodeRequest` - Batch import custom card codes
- **Get Code Count**: `GetCodeDepositCountRequest` - Query imported card code count

### Card Statistics

- **Get Business Info**: `GetBizuinInfoRequest` - Get merchant statistics
- **Get Card Stats**: `GetCardInfoRequest` - Get card data statistics
- **Get Member Card Detail**: `GetMemberCardDetailRequest` - Get member card detailed statistics
- **Get Member Card Info**: `GetMemberCardInfoRequest` - Get member card basic statistics

## Entity Structure

### Core Entities

- **Card**: Main card entity with snowflake ID, timestamp tracking, and WeChat account association
- **CardCode**: Individual card code records with status tracking
- **CardReceive**: User receive records with transfer functionality
- **CardStats**: Daily statistics for various metrics

### Embedded Entities

- **CardBaseInfo**: Card basic information (logo, name, color, title, description, limits)
- **CardDateInfo**: Validity period information (fixed time period or relative time)

## Security

### Data Validation

All entities include comprehensive validation constraints to ensure data integrity:

- String length validation to prevent database overflow
- Enum choice validation for card types and statuses
- Positive number validation for quantities and counts
- Required field validation for critical properties

### Access Control

Ensure proper access control when implementing card operations:

- Validate user permissions before card creation/modification
- Implement rate limiting for card consumption operations
- Secure API endpoints with proper authentication
- Log all card-related operations for audit trails

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
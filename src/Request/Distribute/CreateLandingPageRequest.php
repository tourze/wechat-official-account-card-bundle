<?php

namespace WechatOfficialAccountCardBundle\Request\Distribute;

use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\LandingPageBannerType;

/**
 * 创建货架接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Distributing_Coupons_Vouchers_and_Cards.html#_3-1-创建货架接口
 */
class CreateLandingPageRequest extends WithAccountRequest
{
    /**
     * @var string 页面的banner图片链接，建议尺寸为640*300
     */
    private string $banner;

    /**
     * @var string 页面的title
     */
    private string $pageTitle;

    /**
     * @var bool 页面是否可以分享，true为可分享，false为不可分享
     */
    private bool $canShare = true;

    /**
     * @var LandingPageBannerType 投放页面的场景类型
     */
    private LandingPageBannerType $bannerType;

    /**
     * @var array<array{cardId: string, thumbUrl: string}> 卡券列表，每个item包含cardId和thumbUrl
     */
    private array $cardList = [];

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/landingpage/create';
    }

    public function getRequestOptions(): ?array
    {
        $data = [
            'banner' => $this->getBanner(),
            'page_title' => $this->getPageTitle(),
            'can_share' => $this->isCanShare(),
            'scene' => $this->getBannerType()->value,
            'card_list' => array_map(function (array $card) {
                return [
                    'card_id' => $card['cardId'],
                    'thumb_url' => $card['thumbUrl'],
                ];
            }, $this->getCardList()),
        ];

        return [
            'json' => $data,
        ];
    }

    public function getBanner(): string
    {
        return $this->banner;
    }

    public function setBanner(string $banner): void
    {
        $this->banner = $banner;
    }

    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    public function setPageTitle(string $pageTitle): void
    {
        $this->pageTitle = $pageTitle;
    }

    public function isCanShare(): bool
    {
        return $this->canShare;
    }

    public function setCanShare(bool $canShare): void
    {
        $this->canShare = $canShare;
    }

    public function getBannerType(): LandingPageBannerType
    {
        return $this->bannerType;
    }

    public function setBannerType(LandingPageBannerType $bannerType): void
    {
        $this->bannerType = $bannerType;
    }

    /**
     * @return array<array{cardId: string, thumbUrl: string}>
     */
    public function getCardList(): array
    {
        return $this->cardList;
    }

    /**
     * @param array<array{cardId: string, thumbUrl: string}> $cardList
     */
    public function setCardList(array $cardList): void
    {
        $this->cardList = $cardList;
    }

    public function addCard(string $cardId, string $thumbUrl): void
    {
        $this->cardList[] = [
            'cardId' => $cardId,
            'thumbUrl' => $thumbUrl,
        ];
    }
}

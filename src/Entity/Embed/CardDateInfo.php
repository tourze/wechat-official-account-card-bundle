<?php

namespace WechatOfficialAccountCardBundle\Entity\Embed;

use Doctrine\ORM\Mapping as ORM;
use WechatOfficialAccountCardBundle\Enum\DateType;

#[ORM\Embeddable]
class CardDateInfo
{
    #[ORM\Column(type: 'integer', enumType: DateType::class, options: ['comment' => '使用时间的类型'])]
    private DateType $type = DateType::DATE_TYPE_FIX_TIME_RANGE;

    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '起用时间'])]
    private ?int $beginTimestamp = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '结束时间'])]
    private ?int $endTimestamp = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '自领取后多少天内有效'])]
    private ?int $fixedTerm = null;

    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '自领取后多少天开始生效'])]
    private ?int $fixedBeginTerm = null;

    public function getType(): DateType
    {
        return $this->type;
    }

    public function setType(DateType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBeginTimestamp(): ?int
    {
        return $this->beginTimestamp;
    }

    public function setBeginTimestamp(?int $beginTimestamp): self
    {
        $this->beginTimestamp = $beginTimestamp;

        return $this;
    }

    public function getEndTimestamp(): ?int
    {
        return $this->endTimestamp;
    }

    public function setEndTimestamp(?int $endTimestamp): self
    {
        $this->endTimestamp = $endTimestamp;

        return $this;
    }

    public function getFixedTerm(): ?int
    {
        return $this->fixedTerm;
    }

    public function setFixedTerm(?int $fixedTerm): self
    {
        $this->fixedTerm = $fixedTerm;

        return $this;
    }

    public function getFixedBeginTerm(): ?int
    {
        return $this->fixedBeginTerm;
    }

    public function setFixedBeginTerm(?int $fixedBeginTerm): self
    {
        $this->fixedBeginTerm = $fixedBeginTerm;

        return $this;
    }
}

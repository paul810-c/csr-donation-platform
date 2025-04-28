<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use Brick\Money\Money as BrickMoney;

readonly class Money
{
    public function __construct(
        private BrickMoney $money
    ) {}

    public static function fromString(string $amount, string $currency = 'EUR'): self
    {
        return new self(BrickMoney::of($amount, $currency));
    }

    public static function fromCents(int $amountInCents, string $currency = 'EUR'): self
    {
        return new self(BrickMoney::ofMinor($amountInCents, $currency));
    }

    public function getAmountInCents(): int
    {
        return $this->money->getMinorAmount()->toInt();
    }

    public function toString(): string
    {
        return (string) $this->money->getAmount();
    }
}

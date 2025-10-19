<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

final class Money
{
    private function __construct(
        private readonly int $amount,
        private readonly string $currency,
    ) {
        $this->ensureAmountIsNotNegative($amount);
        $this->ensureCurrencyIsValid($currency);
    }

    public static function fromInt(int $amount, string $currency = 'EUR'): self
    {
        return new self($amount, strtoupper($currency));
    }

    /**
     * @param array{amount:int,currency:string} $data
     */
    public static function fromArray(array $data): self
    {
        return self::fromInt($data['amount'], $data['currency']);
    }

    public static function zero(string $currency = 'EUR'): self
    {
        return new self(0, strtoupper($currency));
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(self $other): self
    {
        $this->ensureSameCurrency($other);

        return new self($this->amount + $other->amount(), $this->currency);
    }

    public function subtract(self $other): self
    {
        $this->ensureSameCurrency($other);
        $newAmount = $this->amount - $other->amount();
        if ($newAmount < 0) {
            throw new \InvalidArgumentException('Money subtraction would lead to a negative amount.');
        }

        return new self($newAmount, $this->currency);
    }

    public function multiply(int $multiplier): self
    {
        if ($multiplier < 0) {
            throw new \InvalidArgumentException('Money multiplier cannot be negative.');
        }

        return new self($this->amount * $multiplier, $this->currency);
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount() && $this->currency === $other->currency();
    }

    /**
     * @return array{amount:int,currency:string}
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }

    public function __toString(): string
    {
        return sprintf('%s %0.2f', $this->currency, $this->amount / 100);
    }

    private function ensureAmountIsNotNegative(int $amount): void
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Money amount cannot be negative.');
        }
    }

    private function ensureSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency()) {
            throw new \InvalidArgumentException('Money currency mismatch.');
        }
    }

    private function ensureCurrencyIsValid(string $currency): void
    {
        if (1 !== preg_match('/^[A-Z]{3}$/', strtoupper($currency))) {
            throw new \InvalidArgumentException('Money currency must be a 3 letters ISO code.');
        }
    }
}

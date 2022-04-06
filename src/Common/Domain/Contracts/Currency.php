<?php
namespace Kata\Common\Domain\Contracts;

interface Currency extends ValueObject
{
    const DEFAULT_CODE = 'EUR';

    public function code(): ?string;
}

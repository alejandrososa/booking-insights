<?php

namespace Kata\Common\Domain\Criteria;

interface CriteriaConverter
{
    public function convert(Criteria $criteria): array;
}

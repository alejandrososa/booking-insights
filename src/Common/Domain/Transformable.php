<?php
namespace Kata\Common\Domain;

interface Transformable
{
    public function write($data): self;
    public function read();
}

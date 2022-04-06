<?php

declare(strict_types=1);

namespace Kata\Tests\Shared\Infrastructure\Behat\Utils;

use Symfony\Component\HttpFoundation\Response;

final class ResponsePool
{
    private Response $response;

    public function store(Response $response)
    {
        $this->response = $response;
    }

    public function retrieve(): ?Response
    {
        return $this->response;
    }
}

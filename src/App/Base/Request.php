<?php declare(strict_types = 1);

namespace App\Base;

use function is_null;

/**
 * @ToDo: comply psr7 request
 */
class Request implements RequestInterface
{

    /** @var string $httpMethod */
    private $httpMethod;

    /** @var string $uriPath */
    private $uriPath;

    /** @var string[] $data */
    private $data = [];

    /**
     * Parse http request
     *
     * @return self
     */
    public function parseRequest(): self
    {
        $this->parseHttpMethod();
        $this->parseUriPath();
        $this->parseData();

        return $this;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getUri(): string
    {
        return $this->uriPath;
    }

    /**
     * Get data from $_REQUEST
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string|null $key
     * @return mixed|null
     */
    public function getData(?string $key = null)
    {
        if (is_null($key)) {
            return $this->data;
        }

        return $this->data[$key] ?? null;
    }

    private function parseHttpMethod(): void
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
    }

    private function parseUriPath(): void
    {
        $this->uriPath = $_SERVER['REQUEST_URI'];
    }

    private function parseData(): void
    {
        $this->data = $_REQUEST;
    }
}

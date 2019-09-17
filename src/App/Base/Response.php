<?php declare(strict_types = 1);

namespace App\Base;

/**
 * @ToDo: comply psr7 response
 */
class Response implements ResponseInterface
{

    /** @var string $body */
    private $body = '';

    private function getBody(): string
    {
        return $this->body;
    }

    /**
     * Setter body
     *
     * @param $body
     * @return void
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * Convert response to string.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getBody();
    }
}

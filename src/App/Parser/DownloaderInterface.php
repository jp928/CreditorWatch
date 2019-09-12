<?php declare(strict_types = 1);

namespace App\Parser;

/**
 * Interface DownloaderInterface
 *
 * @package App\Parser
 * @throws \App\Parser\App\Exceptions\DownloadException
 */
interface DownloaderInterface
{

    /**
     * This method should return the content of the url in a string
     *
     * @param string $url
     * @return string
     */
    public function download(string $url): string;
}

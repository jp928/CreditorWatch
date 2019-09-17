<?php declare(strict_types = 1);

namespace App\Transport;

use App\Exceptions\DownloadException;
use function curl_error;
use function curl_exec;
use function curl_init;
use function curl_setopt;
use function ini_get;

/**
 * @codeCoverageIgnore
 */
class Downloader implements DownloaderInterface
{

    /**
     * A simple curl implementation to get the content of the url.
     *
     * @param string $url
     * @return string
     * @throws \App\Exceptions\DownloadException
     */
    public function download(string $url): string
    {
        $ch = curl_init($url);

        if (! ini_get('open_basedir')) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36');
        curl_setopt($ch, CURLOPT_URL, $url);

        $content = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode !== 200 && $httpCode !== 201) {
            throw new DownloadException('Error retrieving "' . $url . '" with response code:' . $httpCode);
        }

        if ($content === false) {
            throw new DownloadException('Error retrieving "' . $url . '" (' . curl_error($ch) . ')');
        }

        return $content;
    }
}

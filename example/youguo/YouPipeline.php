<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace youguo;


use Pipeline\DownloadPipeline;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class YouPipeline extends DownloadPipeline
{
    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    public function processItem(Crawler $crawler = null, ResponseInterface $response = null)
    {
        $crawler->each(function (Crawler $node) {
            $uri = $node->filter('a')->attr('data-original');
            $info = parse_url($uri);
            $this->download($uri, $info['path']);
        });
    }
}
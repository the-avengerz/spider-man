<?php
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class DemoPipe extends Pipe
{
    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    public function processItem(Crawler $crawler, ResponseInterface $response = null)
    {
        $response->getBody()->rewind();
        echo $response->getBody();
    }
}
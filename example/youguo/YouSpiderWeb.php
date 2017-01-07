<?php

namespace youguo;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Spider\SpiderWeb;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class YouSpiderWeb extends SpiderWeb
{

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    function process(Crawler $crawler, ResponseInterface $response)
    {
        $total = 10;
        $this->setMaxProgress($total);
        $this->setStartProgress(1);
        for ($i = 1; $i < $total; $i++) {
            $this->emit(new PageSpiderWeb('GET', 'http://m.ugirls.com/' . $i . '.html'));
        }
    }

    /**
     * @param RequestException $requestException
     * @return mixed
     */
    function error(RequestException $requestException)
    {
        // TODO: Implement error() method.
    }
}
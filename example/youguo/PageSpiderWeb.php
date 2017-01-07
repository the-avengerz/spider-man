<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace youguo;


use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Spider\SpiderWeb;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class PageSpiderWeb
 * @package youguo
 */
class PageSpiderWeb extends SpiderWeb
{
    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    function process(Crawler $crawler, ResponseInterface $response)
    {
        $this->pipe(YouPipeline::class, '.mobilemagazine');
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
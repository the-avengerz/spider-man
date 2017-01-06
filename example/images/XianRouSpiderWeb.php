<?php
use Pipeline\JsonPipeline;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class XianRouSpiderWeb extends \Spider\SpiderWeb
{
    /**
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    function process(\Symfony\Component\DomCrawler\Crawler $crawler, \Psr\Http\Message\ResponseInterface $response)
    {
        $this->pipe(JsonPipeline::class);
    }

    /**
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @return mixed
     */
    function error(\GuzzleHttp\Exception\RequestException $requestException)
    {
        // TODO: Implement error() method.
    }
}
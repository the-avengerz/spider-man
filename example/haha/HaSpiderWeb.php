<?php
use Pipeline\ImagePipeline;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class HaSpiderWeb extends \Spider\SpiderWeb
{
    public $name = 'ha';

    /**
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    function process(\Symfony\Component\DomCrawler\Crawler $crawler, \Psr\Http\Message\ResponseInterface $response)
    {
        $this->pipe(ImagePipeline::class, '//*[@id="feed-list"]/div');
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
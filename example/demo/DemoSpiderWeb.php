<?php
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
class DemoSpiderWeb extends SpiderWeb
{
    public $name = 'demo';

    public function process(Crawler $crawler, ResponseInterface $response)
    {
        $this->pipe(DemoPipe::class, '');
    }

    function error(RequestException $requestException)
    {

    }
}
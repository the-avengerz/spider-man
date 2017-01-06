<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;
use Spider\SpiderWeb;

/**
 * @param Client $client
 * @param SpiderWeb $spiderWeb
 * @return PromiseInterface
 */
function emit (Client $client, SpiderWeb $spiderWeb) {
    return $client
        ->requestAsync($spiderWeb->method, (string)$spiderWeb->uri)
        ->then(function (ResponseInterface $response) use ($spiderWeb) {
            $crawler = new Crawler(null, (string)$spiderWeb->uri);
            $response->getBody()->rewind();
            $content = $response->getBody()->getContents();
            if (false !== strpos($content, '<')) {
                $crawler->addContent($content);
            }
            return call_user_func_array([$spiderWeb, 'parse'], [$crawler, $response]);
        }, function (RequestException $requestException) use ($spiderWeb) {
            return call_user_func([$spiderWeb, 'error'], $requestException);
        });
}

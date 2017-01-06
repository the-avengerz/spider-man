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
            $crawler = createCrawler($response);
            $spiderWeb->node = $crawler;
            $spiderWeb->response = $response;
            return call_user_func_array([$spiderWeb, 'process'], [$crawler, $response]);
        }, function (RequestException $requestException) use ($spiderWeb) {
            return call_user_func([$spiderWeb, 'error'], $requestException);
        });
}

/**
 * @param ResponseInterface $response
 * @return Crawler
 */
function createCrawler (ResponseInterface $response) {
    $crawler = new Crawler();
    $response->getBody()->rewind();
    $content = $response->getBody()->getContents();
    if (false !== strpos($content, '<')) {
        $crawler->addContent($content);
    }
    return $crawler;
}

/**
 * @param ResponseInterface $response
 * @return bool|mixed
 */
function transformResponseToJson (ResponseInterface $response) {
    $content = (string)$response->getBody();
    $json = json_decode($content);
    if (json_last_error()) {
        return false;
    }
    return $json;
}
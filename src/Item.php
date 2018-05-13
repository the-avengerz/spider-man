<?php

namespace Avenger;

use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

/**
 * Class Item
 */
abstract class Item extends Pipeline
{
    /**
     * @param Crawler|null $crawler
     * @param ResponseInterface|null $response
     * @return mixed
     */
    abstract public function parse(Crawler $crawler = null, ResponseInterface $response = null);

    /**
     * @param $method
     * @param $uri
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    public function parseItem($method, $uri)
    {
        $this->processCallback = 'parse';

        $pipeline = clone $this;

        return wait([$pipeline->promise($method, $uri)]);
    }
}
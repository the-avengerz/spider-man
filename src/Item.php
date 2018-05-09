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
    const PIPELINE_SUCCESS = 'item';

    /**
     * @param Crawler|null $crawler
     * @param ResponseInterface|null $response
     * @return mixed
     */
    abstract public function parse(Crawler $crawler = null, ResponseInterface $response = null);
}
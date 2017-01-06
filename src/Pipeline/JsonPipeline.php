<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Pipeline;


use Pipeline;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class JsonPipeline extends Pipeline
{

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    public function processItem(Crawler $crawler = null, ResponseInterface $response = null)
    {
        $json = transformResponseToJson($response);

        print_r($json);
    }
}
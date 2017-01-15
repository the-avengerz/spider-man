<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace weibo_hz;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Pipeline;
use Pipeline\ImagePipeline;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class WeiBoPipeline
 * @package weibo
 */
class WeiBoPipeline extends Pipeline
{
    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    public function process(Crawler $crawler = null, ResponseInterface $response = null)
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->pipeline(new PagePipeline('GET', 'http://m.weibo.cn/container/getIndex?type=uid&value=6049220524&containerid=1076036049220524&page=' . $i, $this->options));
        }

        return function (array $results) {
            echo count($results);
        };
    }

    /**
     * @param RequestException $exception
     * @return mixed
     */
    public function error(RequestException $exception)
    {
        // TODO: Implement error() method.
    }
}
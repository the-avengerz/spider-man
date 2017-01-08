<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace weibo;


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
        $json = transformResponseToJson($response);
        for ($i = 4; $i < $json['cardlistInfo']['total']; $i++) {
            $total = 0;
            $promises = [];
            foreach ($json['cards'] as $card) {
                foreach ($card['mblog']['pics'] as $item) {
                    $total++;
                    $promises[] = $this->pipeline(new ImagePipeline('GET', $item['large']['url']));
                }
            }
            progressBarMaxStep($total);
            wait($promises);
            state('promise.pipelines', []);
            unset($promises);
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
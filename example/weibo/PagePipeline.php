<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace weibo;


use GuzzleHttp\Exception\RequestException;
use Pipeline\ImagePipeline;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class PagePipeline extends \Pipeline
{
    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    public function process(Crawler $crawler = null, ResponseInterface $response = null)
    {
        $json = transformResponseToJson($response);

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
        unset($promises);
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
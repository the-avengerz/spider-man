<?php
/**
 * @author: RunnerLee
 * @email: runnerleer@gmail.com
 * @time: 17-2-9 15:29
 */
namespace Pipeline\Weibo\DaYe;

use GuzzleHttp\Exception\RequestException;
use Pipeline;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class ImagePipeline extends Pipeline
{

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    public function process(Crawler $crawler = null, ResponseInterface $response = null)
    {
        copy($this->uri, $this->options['download_path'] . basename($this->uri));
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
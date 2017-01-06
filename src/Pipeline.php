<?php
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
abstract class Pipeline
{
    public $item;

    public function setItem(Item $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    abstract public function processItem(Crawler $crawler = null, ResponseInterface $response = null);
}
<?php
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class DemoPipe extends Pipe
{
    /**
     * @param Item|null $item
     * @param Crawler $crawler
     * @return mixed
     */
    public function processItem(Item $item = null, Crawler $crawler)
    {
        // TODO: Implement processItem() method.
    }
}
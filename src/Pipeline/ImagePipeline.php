<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Pipeline;


use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ImagePipeline
 * @package Pipeline
 */
class ImagePipeline extends DownloadPipeline
{
    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    public function process(Crawler $crawler = null, ResponseInterface $response = null)
    {
        $file = $this->download();

        output('');
        output($file);

        return $file;
    }
}
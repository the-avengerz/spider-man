<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Pipeline;


use Item\ImageItem;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ImagePipeline
 * @package Pipeline
 */
class ImagePipeline extends DownloadPipeline
{
    /**
     * @param Crawler|null $node
     * @param ResponseInterface|null $response
     * @return mixed
     */
    public function processItem(Crawler $node = null, ResponseInterface $response = null)
    {
        foreach ($node->filter('img')->images() as $image) {
            $imageItem = new ImageItem($image);
            $this->download($image->getUri(), $imageItem->uri->getPath());
            unset($imageItem);
        }
    }
}
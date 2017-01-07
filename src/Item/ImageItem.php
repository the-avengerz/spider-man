<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Item;


use GuzzleHttp\Psr7\Uri;
use Symfony\Component\DomCrawler\Image;

/**
 * Class ImageItem
 * @package Item
 */
class ImageItem extends \Item
{
    /**
     * @var Uri
     */
    public $uri;

    /**
     * @var string
     */
    public $name;

    /**
     * ImageItem constructor.
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->uri = new Uri($image->getUri());

        $this->node = $image->getNode();

        $this->name = pathinfo($this->uri->getPath(), PATHINFO_BASENAME);
    }
}
<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Spider;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use Item;
use Pipe;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Link;


/**
 * Class SpiderWeb
 * @package Spider
 */
abstract class SpiderWeb
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var Uri
     */
    public $uri;

    /**
     * @var string
     */
    public $method = 'GET';

    /**
     * @var string|Item
     */
    public $item;

    /**
     * @var string|Pipe
     */
    public $pipe;

    /**
     * @var SpiderWeb[]
     */
    public $emits = [];

    /**
     * SpiderWeb constructor.
     * @param string $method
     * @param string $uri
     */
    public function __construct($method = 'GET', $uri = '')
    {
        $this->method = $method;

        if ($uri instanceof Uri) {
            $this->uri = $uri;
        } else if($uri instanceof Link) {
            $this->uri = new Uri($uri->getUri());
        } else {
            $this->uri = new Uri($uri);
        }
    }

    /**
     * @param $item
     * @return $this
     */
    public function item($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @param $pipe
     * @return $this
     */
    public function pipe($pipe)
    {
        $this->pipe = $pipe;

        return $this;
    }

    /**
     * @param SpiderWeb $spiderWeb
     * @return $this
     */
    public function emit(SpiderWeb $spiderWeb)
    {
        $this->emits[] = $spiderWeb;

        return $this;
    }

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    abstract function process(Crawler $crawler, ResponseInterface $response);

    /**
     * @param RequestException $requestException
     * @return mixed
     */
    abstract function error(RequestException $requestException);
}
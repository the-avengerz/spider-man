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
     * @param $pipe
     * @param Crawler $crawler
     * @return $this
     */
    public function pipe($pipe, Crawler $crawler)
    {
        if (is_string($pipe)) {
            if (!class_exists($pipe)) {
                throw new \LogicException(sprintf('Pipe %s is undefined', $pipe));
            }
            $pipe = new $pipe();
        }

        $item = null;
        if (isset($pipe->item) && null !== $pipe->item) {
            if (is_string($pipe->item)) {
                if (!class_exists($pipe->item)) {
                    throw new \LogicException(sprintf('Pipe item %s is undefined', $pipe->item));
                }
                $item = $pipe->item;
                $item = new $item;
            }
        }

        call_user_func_array([$pipe, 'processItem'], [$item, $crawler]);

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
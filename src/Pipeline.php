<?php

namespace Avenger;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
abstract class Pipeline implements SpiderInterface
{
    const PIPELINE_SUCCESS = 'process';

    /**
     * @var Uri
     */
    public $uris = [];

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * Pipeline constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->options = [
            'headers' => [
                'USER-AGENT' => faker()->userAgent,
                'CLIENT-IP' => faker()->ipv4,
                'X-FORWARDED-FOR' => faker()->ipv4,
            ],
        ];
    }

    /**
     * @param $index
     * @return string
     */
    public function getUri($index = 0)
    {
        if ( ! isset($this->uris[$index])) {
            throw new \InvalidArgumentException(sprintf('Url index: %s is undefined.', $index));
        }

        return $this->uris[$index];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param $method
     * @param $uri
     * @return PromiseInterface
     */
    protected function promise($method, $uri)
    {
        $that = $this;
        return $this->client
            ->requestAsync($method, $uri, $this->options)
            ->then(
                function (ResponseInterface $response) use ($method, $uri, $that) {
                    $content = (string) $response->getBody();

                    if ('<' !== substr($content, 0, 1)) { // html
                        $content = null;
                    }
                    $crawler = new Crawler($content, $uri);

                    progressBarStatus($method, $uri);

                    return call_user_func_array([$that, Pipeline::PIPELINE_SUCCESS], [$crawler, $response]);
                },
                function (RequestException $requestException) use ($method, $uri, $that) {
                    if ($that instanceof SpiderManErrorHandlerInterface) {
                        progressBarStatus($method, $uri);

                        return call_user_func_array([$that, Pipeline::PIPELINE_ERROR], [$requestException]);
                    }
                    throw $requestException;
                });
    }

    /**
     * @return PromiseInterface[]
     */
    public function __invoke()
    {
        if (empty($this->uris)) {
            throw new \RuntimeException(sprintf('Uris cannot be not.'));
        }

        $promises = [];
        foreach ($this->uris as $uri) {
            $method = 'GET';
            if (false !== stripos($uri, ' ')) {
                list($method, $uri) = explode(' ', $uri);
            }
            $promises[] = $this->promise($method, $uri);
        }

        return $promises;
    }

    /**
     * @param $method
     * @param $uri
     * @return array
     */
    public function pipeline($method, $uri)
    {
        $pipeline = clone $this;

        return wait([$pipeline->promise($method, $uri)]);
    }

    public function __destruct()
    {
        gc_collect_cycles();
    }
}
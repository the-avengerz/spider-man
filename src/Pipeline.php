<?php

namespace Avenger;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use JonnyW\PhantomJs\Client;
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
    protected $processCallback = 'process';

    /**
     * @var Uri
     */
    protected $uris = [];

    /**
     * @var array
     */
    protected $options = [];

    protected $client;

    /**
     * Pipeline constructor.
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;

        $this->options = [
            'headers' => [
                'USER-AGENT' => faker()->userAgent,
                'CLIENT-IP' => faker()->ipv4,
                'X-FORWARDED-FOR' => faker()->ipv4,
            ],
        ];

        $this->configure();
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
     * @return void
     */
    public function configure()
    {
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
        if ($this->client instanceof \GuzzleHttp\Client) {
            return $this->client
                ->requestAsync($method, $uri, $this->options)
                ->then(
                    function (ResponseInterface $response) use ($method, $uri, $that) {
                        $content = (string)$response->getBody();

                        if ('<' !== substr($content, 0, 1)) { // html
                            $content = null;
                        }
                        $crawler = new Crawler($content, $uri);

                        progressBarStatus($method, $uri);

                        return call_user_func_array([$that, $that->processCallback], [$crawler, $response]);
                    },
                    function (RequestException $requestException) use ($method, $uri, $that) {
                        if ($that instanceof SpiderManErrorHandlerInterface) {
                            progressBarStatus($method, $uri);

                            return call_user_func_array([$that, SpiderManErrorHandlerInterface::PIPELINE_ERROR],
                                [$requestException]);
                        }
                        throw $requestException;
                    });
        } elseif ($this->client instanceof Client) {
            $factory = $this->client->getMessageFactory();
            $request = $factory->createRequest($uri, $method);
            $response = $factory->createResponse();
            progressBarStatus($method, $uri);
            $this->client->send($request, $response);
            $content = $response->getContent();
            if ('<' !== substr($content, 0, 1)) { // html
                $content = null;
            }
            $crawler = new Crawler($content, $uri);

            return call_user_func_array(
                [$that, $that->processCallback],
                [
                    $crawler,
                    new Response(
                        $response->getStatus(),
                        [],
                        $response->getContent()
                    ),
                ]
            );
        }
    }

    /**
     * @return PromiseInterface[]
     */
    public function __invoke()
    {
        if (empty($this->uris)) {
            call_user_func_array([$this, $this->processCallback], [null, null]);

            return [];
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
     * @param $pipeline
     * @return array
     */
    public function pipeline($pipeline)
    {
        if (is_string($pipeline)) {
            $pipeline = new $pipeline($this->client);
        }

        return wait($pipeline());
    }

    public function __destruct()
    {
        gc_collect_cycles();
    }
}
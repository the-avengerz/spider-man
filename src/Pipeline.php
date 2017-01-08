<?php
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
abstract class Pipeline
{
    const PIPELINE_SUCCESS = 'process';
    const PIPELINE_ERROR = 'error';

    /**
     * @var Uri
     */
    public $uri;

    /**
     * @var string
     */
    public $method;

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var callable
     */
    public $success;

    /**
     * @var callable
     */
    public $error;

    /**
     * @var Pipeline[]
     */
    public $pipelines = [];

    /**
     * @var int
     */
    public $index = 0;

    /**
     * @var Crawler
     */
    public $node;

    /**
     * @var ResponseInterface
     */
    public $response;

    /**
     * @var Client
     */
    public $client;

    /**
     * Pipeline constructor.
     * @param $method
     * @param $uri
     * @param array $options
     */
    public function __construct($method = null, $uri = null, array $options = [])
    {
        $this->method = $method;

        if (!($uri instanceof Uri)) {
            $uri = new Uri($uri);
        }

        $this->uri = $uri;

        $this->options = $options;

        $this->success = function (ResponseInterface $response) {
            $crawler = createCrawler($this, $response);
            $this->node = $crawler;
            $this->response = $response;
            state('uri', (string)$this->uri);
            state('method', (string)$this->method);
            state('progress.bar')->advance();
            return call_user_func_array([$this, Pipeline::PIPELINE_SUCCESS], [$crawler, $response]);
        };

        $this->error = function (RequestException $requestException) {
            state('uri', (string)$this->uri);
            state('method', (string)$this->method);
            state('progress.bar')->advance();
            return call_user_func_array([$this, Pipeline::PIPELINE_ERROR], [$requestException]);
        };
    }

    /**
     * @return Uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     * @return $this
     */
    public function setUri($uri)
    {
        if (!($uri instanceof Uri)) {
            $uri = new Uri($uri);
        }

        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
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
     * @return callable
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param callable $success
     * @return $this
     */
    public function setSuccess(callable $success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @return callable
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param callable $error
     * @return $this
     */
    public function setError(callable $error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @param Client $client
     * @return PromiseInterface
     */
    public function __invoke(Client $client)
    {
        $promise = $client
            ->requestAsync($this->method, (string)$this->uri, $this->options)
            ->then($this->success, $this->error);

        unset($this);

        return $promise;
    }

    /**
     * @param Pipeline $pipeline
     * @param $index;
     * @return PromiseInterface
     */
    public function pipeline(Pipeline $pipeline, $index = 0)
    {
        $pipeline->index = $index;

        return promise(new Client(), $pipeline);
    }

    public function __destruct()
    {
        $this->error = null;
        $this->success = null;
        $this->uri = null;
        $this->pipelines = [];
        $this->method = null;

        gc_collect_cycles();
    }

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @return mixed
     */
    abstract public function process(Crawler $crawler = null, ResponseInterface $response = null);

    /**
     * @param RequestException $exception
     * @return mixed
     */
    abstract public function error(RequestException $exception);
}
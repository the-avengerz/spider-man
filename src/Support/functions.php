<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use State\Tying;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @param Pipeline $pipeline
 * @param ResponseInterface $response
 * @return Crawler
 */
function createCrawler(Pipeline $pipeline, ResponseInterface $response)
{
    $response->getBody()->rewind();
    $content = $response->getBody()->getContents();
    if ('<' !== substr($content, 0, 1)) {
        $content = '';
    }
    return new Crawler($content, (string)$pipeline->uri);
}

/**
 * @param ResponseInterface $response
 * @param bool $assoc
 * @return bool|mixed
 */
function transformResponseToJson(ResponseInterface $response, $assoc = true)
{
    $content = (string)$response->getBody();
    $json = json_decode($content, $assoc);
    if (json_last_error()) {
        return false;
    }
    return $json;
}

/**
 * @param $message
 */
function output($message)
{
    $output = state('output');

    if ($output->isQuiet()
        || $output->isVerbose()
        || $output->isVeryVerbose()
        || $output->isDebug()
    ) {
        $output->writeln($message);
    }
}

/**
 * @param $name
 * @param null $value
 * @param bool $append
 * @return bool|null
 */
function state($name, $value = null, $append = false)
{
    if (null === $value) {
        return isset(Tying::$tying[$name]) ? Tying::$tying[$name] : false;
    }

    if ($append) {
        Tying::$tying[$name][] = $value;
    } else {
        Tying::$tying[$name] = $value;
    }

    return true;
}

/**
 * @param Client $client
 * @param Pipeline $pipeline
 * @return PromiseInterface
 */
function promise (Client $client, Pipeline $pipeline) {
    $promise = $pipeline($client);
    state('promise.pipelines', $promise, true);
    return $promise;
}

/**
 * @param PromiseInterface[] $promise
 * @return array
 */
function wait(array $promise)
{
    return \GuzzleHttp\Promise\unwrap($promise);
}

/**
 * @param $step
 */
function progressBarMaxStep($step)
{
    $bar = state('progress.bar');

    $current = $bar->getMaxSteps();

    $bar->setProgress(($current + $step));

    $bar->setProgress($current);
}
<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use Avenger\State\Tying;
use Psr\Http\Message\ResponseInterface;

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
 * @param array $promise
 * @return array
 * @throws Exception
 * @throws Throwable
 */
function wait(array $promise)
{
    if ($promise[0] instanceof \GuzzleHttp\Promise\PromiseInterface) {
        return \GuzzleHttp\Promise\unwrap($promise);
    }

    return $promise;
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

/**
 * @param $method
 * @param $uri
 */
function progressBarStatus($method, $uri)
{
    state('uri', $uri);
    state('method', $method);
    state('progress.bar')->advance();
}

function faker()
{
    return state('faker');
}
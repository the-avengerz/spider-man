<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace Avenger;


use GuzzleHttp\Exception\RequestException;

/**
 * Interface SpiderManErrorHandlerInterface
 * @package Avenger
 */
interface SpiderManErrorHandlerInterface
{
    const PIPELINE_ERROR = 'onFailure';

    /**
     * @param RequestException $requestException
     * @return mixed
     */
    public function onFailure(RequestException $requestException);
}
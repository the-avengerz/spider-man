<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace State;


/**
 * Class Tying
 * @package State
 */
class Tying
{
    public static $tying = [];

    public function __set($name, $value)
    {
        static::$tying[$name] = $value;
    }

    public function __get($name)
    {
        return isset(static::$tying[$name]) ? static::$tying[$name] : false;
    }

    public function __unset($name)
    {
        if (isset(static::$tying[$name])) {
            unset(static::$tying[$name]);
        }
    }
}
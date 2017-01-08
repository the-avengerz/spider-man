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
    /**
     * @var array
     */
    public static $tying = [];

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        static::$tying[$name] = $value;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function __get($name)
    {
        return isset(static::$tying[$name]) ? static::$tying[$name] : false;
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        if (isset(static::$tying[$name])) {
            unset(static::$tying[$name]);
        }
    }
}
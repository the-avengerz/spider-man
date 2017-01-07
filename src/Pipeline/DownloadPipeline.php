<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Pipeline;


/**
 * Class DownloadPipeline
 * @package Pipeline
 */
abstract class DownloadPipeline extends \Pipeline
{
    /**
     * @param $uri
     * @param $file
     * @param $timeout
     * @return int
     */
    public function download($uri, $file, $timeout = 30)
    {
        $content = file_get_contents($uri, false, stream_context_create([
            'http' => [
                'method' => "GET",
                'timeout' => $timeout,//单位秒
            ]
        ]));

        $dir = isset($this->spiderWeb->options['download_path']) ? $this->spiderWeb->options['download_path'] : $this->spiderWeb->dir;

        $file = $dir . $file;

        $this->targetDirectory(dirname($file));

        if (file_put_contents($file, $content)) {
            return $file;
        }

        return false;
    }

    /**
     * @param $dir
     * @return bool
     */
    protected function targetDirectory($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        return true;
    }
}
<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Pipeline;

use GuzzleHttp\Exception\RequestException;
use Pipeline;
use Symfony\Component\Console\Exception\LogicException;


/**
 * Class DownloadPipeline
 * @package Pipeline
 */
abstract class DownloadPipeline extends Pipeline
{
    /**
     * @param $file
     * @param $timeout
     * @return int
     */
    public function download($file = null, $timeout = 30)
    {
        $config = state('config');
        if (!isset($config['download_path'])) {
            throw new LogicException(sprintf('Undefined download path.'));
        }

        if (null === $file) {
            $file = $this->uri->getPath();
        }

        $content = file_get_contents((string) $this->uri, false, stream_context_create([
            'http' => [
                'method' => "GET",
                'timeout' => $timeout,//单位秒
                'referer' => (string) $this->uri,
            ]
        ]));

        $file = $config['download'] . $file;

        $this->targetDirectory(dirname($file));

        if (file_put_contents($file, $content)) {
            unset($content);
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

    /**
     * @param RequestException $exception
     * @return mixed
     */
    public function error(RequestException $exception)
    {
        output($exception->getMessage());
    }
}
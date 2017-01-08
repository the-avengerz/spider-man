<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Behavior;


use GuzzleHttp\Client;
use LogicException;
use Pipeline;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Emit
 * @package Behavior
 */
class Emit extends Command
{
    const SPIDER_WEB = 'pipeline';

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * Emit constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->httpClient = new Client();
    }

    /**
     * @return void
     */
    public function configure()
    {
        $this->addArgument(Emit::SPIDER_WEB);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $pipeline = $input->getArgument(Emit::SPIDER_WEB);

        $pipeline = $this->createPipeline($pipeline);

        $progress = $this->initProgressBar($pipeline);

        $progress->start(1);

        $process = wait([$pipeline($this->httpClient)]);

        $results = [];
        if (is_array($promises = state('promise.pipelines'))) {
            $results = wait($promises);
            unset($promises);
        }

        if (is_callable($process[0])) {
            call_user_func($process[0], $results);
        }

        $progress->finish();

        return 0;
    }

    /**
     * @param Pipeline $pipeline
     * @return $this
     */
    protected function statePipeline(Pipeline $pipeline)
    {
        state('method', $pipeline->method);
        state('uri', (string)$pipeline->uri);

        return $this;
    }

    /**
     * @param Pipeline $pipeline
     * @return ProgressBar
     */
    protected function initProgressBar(Pipeline $pipeline)
    {
        $this->statePipeline($pipeline);

        $progress = state('progress.bar');

        ProgressBar::setPlaceholderFormatterDefinition('method', function () { return state('method'); });
        ProgressBar::setPlaceholderFormatterDefinition('url', function () { return state('uri'); });

        $progress->setFormat("[%method%] %url%\n%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%");

        return $progress;
    }

    /**
     * @param $name
     * @return Pipeline
     */
    public function createPipeline($name)
    {
        $config = getcwd() . '/config.php';

        if (!file_exists($config)) {
            throw new LogicException(sprintf('Unable find (config.php) in %s', $config));
        }

        $config = include_once $config;

        if (!isset($config[$name])) {
            throw new LogicException(sprintf('Spider pipeline %s is undefined.', $name));
        }

        $config = $config[$name];

        state('config', $config);

        $pipeline = isset($config['index']) ? $config['index'] : null;

        if (is_string($pipeline)) {
            $pipeline = new $pipeline($config['method'], $config['url']);

            if (!($pipeline instanceof Pipeline)) {
                throw new LogicException(sprintf('Spider pipeline index must be instance %s', Pipeline::class));
            }
        }

        $pipeline->options = isset($config['options']) ? $config['options'] : [];

        return $pipeline;
    }
}
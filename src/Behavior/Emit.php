<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Avenger\Behavior;

use GuzzleHttp\Client;
use LogicException;
use Avenger\Pipeline;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use JonnyW\PhantomJs\Client as PhantomJs;

/**
 * Class Emit
 * @package Behavior
 */
class Emit extends Command
{
    const SPIDER_WEB = 'pipeline';

    /**
     * Emit constructor.
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    /**
     * @return void
     */
    public function configure()
    {
        $this->addArgument(Emit::SPIDER_WEB);
        $this->addOption('phantom', '-p', InputOption::VALUE_NONE, 'Enable phantom js?');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     * @throws \Exception
     * @throws \Throwable
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $pipeline = $input->getArgument(Emit::SPIDER_WEB);

        // Enable phantom js
        $client = $input->hasParameterOption(['--phantom', '-p']) ? PhantomJs::getInstance() : new Client();

        $pipeline = $this->createPipeline($pipeline, $client);

        $progress = $this->initProgressBar($pipeline);

        $progress->start(1);

        wait($pipeline());

        $progress->finish();

        return 0;
    }

    /**
     * @param Pipeline $pipeline
     * @return $this
     */
    protected function statePipeline(Pipeline $pipeline)
    {
//        state('method', $pipeline->method);
//        state('uri', (string)$pipeline->uri);

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

        $progress->setFormat("<info>[%method%]</info> <comment>%url%</comment>\n%current%/%max% [%bar%] %percent:3s%%\n%elapsed:6s%/%estimated:-6s% %memory:6s%");

        return $progress;
    }

    /**
     * @param $name
     * @return Pipeline
     */
    public function createPipeline($name, $client)
    {
        $config = getcwd() . '/config.php';

        if (!file_exists($config)) {
            $config = [
                'download' => getcwd().'/downloads',
            ];
        } else {
            $config = include_once $config;
        }

        $download = $config['download'].'/'.dirname($name);

        if (!file_exists($download)) {
            mkdir($download, 0755, true);
        }

        state('config', $config);

        $pipeline = str_replace('/', '\\', $name);
        $pipeline = new $pipeline($client);

        if (!($pipeline instanceof Pipeline)) {
            throw new LogicException(sprintf('Spider pipeline index must be instance %s', Pipeline::class));
        }

        return $pipeline;
    }
}
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
use Spider\SpiderWeb;
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
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $format = '';

    /**
     * @var ProgressBar
     */
    protected $progress;

    /**
     * @var array
     */
    public static $stateTying = [
        'name' => '',
        'method' => '',
        'uri' => '',
    ];

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
        $this->addArgument('web');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $webName = $input->getArgument('web');

        $spiderWeb = $this->createSpiderWeb($webName);

        $this->progress = $this->createProgressBar($spiderWeb, $output);

        $spiderWeb->progress = $this->progress;

        $promise = emit(clone $this->httpClient, $spiderWeb, $this->progress);

        $this->progress->start();

        $process = $this->wait([$promise]);

        $promises = [];
        foreach ($spiderWeb->emits as $item) {
            $this->stateTying($item);
            $item->progress = $this->progress;
            $promises[] = emit(clone $this->httpClient, $item, $this->progress);
        }

        $results = $this->wait($promises);

        if (is_callable($process[0])) {
            call_user_func($process[0], $results);
        }

        $this->progress->finish();

        return 0;
    }

    /**
     * @param array $promises
     * @return array
     */
    protected function wait(array $promises)
    {
        return \GuzzleHttp\Promise\unwrap($promises);
    }

    /**
     * @param SpiderWeb $spiderWeb
     * @return $this
     */
    protected function stateTying(SpiderWeb $spiderWeb)
    {
        Emit::$stateTying['method'] = $spiderWeb->method;
        Emit::$stateTying['uri'] = (string)$spiderWeb->uri;

        return $this;
    }

    /**
     * @param SpiderWeb $spiderWeb
     * @param OutputInterface $output
     * @return ProgressBar
     */
    protected function createProgressBar(SpiderWeb $spiderWeb, OutputInterface $output)
    {
        $progress = new ProgressBar($output, 1);

        $this->stateTying($spiderWeb);

        ProgressBar::setPlaceholderFormatterDefinition('method', function () { return Emit::$stateTying['method']; });
        ProgressBar::setPlaceholderFormatterDefinition('url', function () { return (string) Emit::$stateTying['uri']; });

        $progress->setFormat("[%method%] %url%\n%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%");

        return $progress;
    }

    /**
     * @param $name
     * @return SpiderWeb
     */
    public function createSpiderWeb($name)
    {
        $config = getcwd() . '/config.php';

        if (!file_exists($config)) {
            throw new LogicException(sprintf('Unable find (config.php) in %s', $config));
        }

        $config = include_once $config;

        if (!isset($config[$name])) {
            throw new LogicException(sprintf('Spider web %s is undefined.', $name));
        }

        $spiderWeb = isset($config[$name]['index']) ? $config[$name]['index'] : null;

        if (is_string($spiderWeb)) {
            $spiderWeb = new $spiderWeb(
                $config[$name]['method'],
                $config[$name]['url'],
                isset($config[$name]['options']) ? $config[$name]['options'] : []
            );
        }

        $spiderWeb->options = isset($config[$name]['options']) ? $config[$name]['options'] : [];

        $spiderWeb->dir = isset($config[$name]['dir']) ? $config[$name]['dir'] : '.';

        return $spiderWeb;
    }
}
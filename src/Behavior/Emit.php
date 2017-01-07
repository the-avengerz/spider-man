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

        $this->format .= $spiderWeb->name;

        $promise = emit(clone $this->httpClient, $spiderWeb);

        $this->wait([$promise]);

        $promises = [];
        foreach ($spiderWeb->emits as $item) {
            $promises[] = emit(clone $this->httpClient, $item);
        }

        $this->wait($promises);

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

        if (!class_exists($spiderWeb)) {
            include $config[$name]['dir'] . '/' . $spiderWeb . '.php';
        }

        if (is_string($spiderWeb)) {
            $spiderWeb = new $spiderWeb(
                $config[$name]['method'],
                $config[$name]['url'],
                isset($config[$name]['options']) ? $config[$name]['options'] : []
            );
        }

        $spiderWeb->dir = isset($config[$name]['dir']) ? $config[$name]['dir'] : '.';

        return $spiderWeb;
    }
}
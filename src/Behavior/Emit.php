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
    protected $client;

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

        $this->client = new Client();
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

        $spiderWeb = $this->getWeb($webName);

        $this->format .= $spiderWeb->name;

        $promise = emit(clone $this->client, $spiderWeb);

        $this->wait([$promise]);

        $promises = [];
        foreach ($spiderWeb->emits as $item) {
            $promises[] = emit(clone $this->client, $item);
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
    public function getWeb($name)
    {
        include_once __DIR__ . '/../../example/demo/DemoSpiderWeb.php';

        return new \DemoSpiderWeb('GET', 'http://api.k780.com:88/?app=ip.get&ip=8.8.8.8&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json');
    }
}
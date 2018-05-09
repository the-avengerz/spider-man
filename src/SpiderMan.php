<?php

namespace Avenger;

use Avenger\Behavior\Emit;
use Faker\Generator;
use Faker\Provider\Internet;
use Faker\Provider\UserAgent;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class SpiderMan extends Application
{
    const VERSION = '1.0.0 (dev)';

    const NAME = 'Peter Parker';

    const BEHAVIOR = 'emit';

    /**
     * SpiderMan constructor.
     * @param string $path
     */
    public function __construct($path = __DIR__)
    {
        parent::__construct(SpiderMan::NAME, SpiderMan::VERSION);

        $this->add(new Emit(SpiderMan::BEHAVIOR, $path));

        $this->setDefaultCommand(SpiderMan::BEHAVIOR);
    }

    /**
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @return int
     * @throws Exception
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $argv = $_SERVER['argv'];

        $script = array_shift($argv);

        array_unshift($argv, SpiderMan::BEHAVIOR);
        array_unshift($argv, $script);

        return parent::run(new ArgvInput($argv), $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        state('output', $output);

        state('progress.bar', new ProgressBar($output, 1));

        $faker = new Generator();
        $faker->addProvider(new UserAgent($faker));
        $faker->addProvider(new Internet($faker));
        state('faker', $faker);

        $output->writeln('Hey One. I\'m ' . SpiderMan::NAME . '. Spider Man' . ' Version: ' . SpiderMan::VERSION);

        $exitCode = parent::doRun($input, $output);

        return $exitCode;
    }
}
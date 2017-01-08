<?php
use Behavior\Emit;
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
    const VERSION = '0.1.0 (dev)';

    const NAME = 'Peter Parker';

    const BEHAVIOR = 'emit';

    /**
     * SpiderMan constructor.
     */
    public function __construct()
    {
        parent::__construct(SpiderMan::NAME, SpiderMan::VERSION);

        $this->add(new Emit(SpiderMan::BEHAVIOR));

        $this->setDefaultCommand(SpiderMan::BEHAVIOR);
    }

    /**
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @return int
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
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        state('output', $output);

        state('progress.bar', new ProgressBar($output, 1));

        $output->writeln('Hey One. I\'m ' . SpiderMan::NAME . '. Spider Man' . ' Version: ' . SpiderMan::VERSION);

        $exitCode = parent::doRun($input, $output);

        return $exitCode;
    }
}
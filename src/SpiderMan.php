<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class SpiderMan extends Command
{
    const VERSION = '0.1.0 (dev)';

    public function __construct()
    {
        parent::__construct('spider-man');
    }

    public function configure()
    {

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('I\'m Peter. Spider Man');


    }
}
<?php


namespace App\Command;


use App\Controller\FTPController;
use phpseclib3\Net\SFTP;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDataCommand extends Command
{

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'updateData';

    /** @var FTPController */
    private $ftpController;

    public function __construct(FTPController $ftpController)
    {
        $this->ftpController = $ftpController;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->ftpController->getFiles();
        $output->writeln('Data update process executed');
        return Command::SUCCESS;
    }
}
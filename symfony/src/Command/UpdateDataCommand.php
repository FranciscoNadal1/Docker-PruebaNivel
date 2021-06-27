<?php


namespace App\Command;


use App\Controller\FetchApiController;
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

    /** @var FetchApiController */
    private $fetchApiController;

    public function __construct(FTPController $ftpController, FetchApiController $fetchApiController)
    {
        $this->ftpController = $ftpController;
        $this->fetchApiController = $fetchApiController;

        parent::__construct();
    }

    /** Obtiene los datos */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->ftpController->getFiles();
        $this->fetchApiController->consumeAndFetch("https://run.mocky.io/v3/b493377e-2454-408b-98be-e03a9a80f88d");




        $output->writeln('Data update process executed');
        return Command::SUCCESS;
    }
}
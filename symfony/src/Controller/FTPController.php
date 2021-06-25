<?php


namespace App\Controller;


use App\Middleware\MiddlewareCustom;
use App\Repository\ProductSystemRepository;
use App\Service\MappingService;
use App\Service\ProductSystemService;
use Doctrine\ORM\EntityManagerInterface;
use phpseclib3\Net\SFTP;
use SimpleXLSX;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FTPController

{    /** @var ProductSystemRepository  */
    private $productSystemRepo;

    public function __construct(MappingService $mappingService, ProductSystemService $productSystemService, MiddlewareCustom $middlewareCustom)
    {
        $this->mappingService = $mappingService;
        $this->productSystemService = $productSystemService;
        $this->middlewareCustom = $middlewareCustom;
    }


    const HOST = "localhost";
    const PORT = 2201;
    const TIMEOUT = 5;
    const USER = "admin";
    const PASSWORD = "admin";
    const BASE_FOLDER = "FTPFolder";
    const IN_FOLDER = "IN";
    const OUT_FOLDER = "OUT";
    const ERROR_FOLDER = "ERROR";

    /** @var SFTP|null */
    private $sftp;



    public function connect(){
        $this->sftp = new SFTP($this::HOST, $this::PORT, $this::TIMEOUT);
        $this->sftp->login($this::USER, $this::PASSWORD);
    }


    public function getFiles(){

        $this->connect();

        try{
            if(!$this->sftp->isConnected())
                throw new \Exception("No se ha podido conectar");

            $inFullFolder= "/" .$this::BASE_FOLDER . "/" . $this::IN_FOLDER;
            $files = ($this->sftp->nlist($inFullFolder));
            foreach($files as $file){
                if(str_ends_with($file, ".json") ||
                    str_ends_with($file, ".xml") ||
                    str_ends_with($file, ".csv") ||
                    str_ends_with($file, ".xlsx")){

                    $fileContent = $this->sftp->get($inFullFolder."/".$file);


                    if(str_ends_with($file, ".json")){

                        try{
                            $mappedJSON =  $this->mappingService->MapFromJSONFormat($fileContent);
                            $this->middlewareCustom->sendOneOrMoreJsonToQueue($mappedJSON);
                        }catch(\Exception $e){

                            // TODO Error reading json file
                        }
                    }

                    if(str_ends_with($file, ".xml")){

                        try{
                            $mappedJSON =  $this->mappingService->MapFromXMLFormat($fileContent);
                            $this->middlewareCustom->sendOneOrMoreJsonToQueue($mappedJSON);
                        }catch(\Exception $e){

                            // TODO Error reading xml file
                        }
                    }

                    if(str_ends_with($file, ".xlsx")){

                        try{
                            $xlsxFile = SimpleXLSX::parseData( $fileContent, $debug = false );

                            $mappedJSON =  $this->mappingService->convertXLSXToJSON($xlsxFile);
                            $this->middlewareCustom->sendOneOrMoreJsonToQueue($mappedJSON);
                        }catch(\Exception $e){

                            // TODO Error reading xlsx file
                        }
                    }

                }
            }
        }
        catch (\Exception $e)
        {
            echo "</br>";
            echo "\n" . $e . "\n";
            echo "</br>";

            // TODO Error connection with FTP
        }
        finally{
            $this->sftp->disconnect();
        }
    }


    public function uploadFile(){

    }
}
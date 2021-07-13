<?php


namespace App\Controller;


use App\Entity\Providers\Provider_JSON;
use App\Middleware\MiddlewareCustom;
use App\Repository\ProductSystemRepository;
use App\Service\MappingService;
use App\Service\NotificationService;
use App\Service\ProductSystemService;
use phpseclib3\Net\SFTP;
use SimpleXLSX;

class FTPController

{

    /**
     * @var MiddlewareCustom
     */
    private $middlewareCustom;

    /**
     * @var MappingService
     */
    private $mappingService;

    public function __construct(MappingService $mappingService, MiddlewareCustom $middlewareCustom)
    {
        $this->mappingService = $mappingService;
        $this->middlewareCustom = $middlewareCustom;
    }


    const USER = "admin";


    /** @var SFTP|null */
    private $sftp;



    public function connect(){
        $this->sftp = new SFTP($_ENV['FTP_HOST'], $_ENV['FTP_PORT'], $_ENV['FTP_TIMEOUT']);
        $this->sftp->login($_ENV['FTP_USER'], $_ENV['FTP_PASSWORD']);
    }

    /**
     * Esta función se encarga de conectar con el servidor FTP mediante el bundle phpseclib3.
     * Una vez conectado al servidor FTP designado, examina la carpeta especificada para
     * buscar archivos con formato xlsx, xml y json, entonces se encarga de examinar su contenido y llamar
     * a las funciones correspondientes, que se encargan de mapear y enviar el JSON común a la cola, para
     * su posterior consumo.
     *
     * Los archivos del FTP consumidos se transladan después a la carpeta "OUT" si se ha fallado en su consumición,
     * se transladan a la carpeta de "ERROR".
     *
     * Si hay problemas con estos ficheros, se envía una notificación a la cola de notificaciones.
     */
    public function getFiles(){

        try{
            $this->connect();

            if(!$this->sftp->isConnected())
                throw new \Exception("No se ha podido conectar");

            $inFullFolder= "/" .$_ENV['FTP_BASE_FOLDER'] . "/" . $_ENV['FTP_IN_FOLDER'];
            $files = ($this->sftp->nlist($inFullFolder));

            foreach($files as $file){
                if(str_ends_with($file, ".json") ||
                    str_ends_with($file, ".xml") ||
 //                   str_ends_with($file, ".csv") ||
                    str_ends_with($file, ".xlsx")){

                    $fileContent = $this->sftp->get($inFullFolder."/".$file);


                    if(str_ends_with($file, ".json")){


                        try{
                            $data = json_decode($fileContent, true);

                                foreach($data['Data'] as $Articulo){
                                    $providerObject_JSON = new Provider_JSON($Articulo);
                                    $this->middlewareCustom->sendOneOrMoreJsonToQueue($providerObject_JSON->normalizeToJson());
                                }

                            $this->moveProcessedFileOut($file);
                            $this->middlewareCustom->sendChangeNotification((string)NotificationService::newNotificationProcessedFile($file));

                        }
                        catch(\Throwable $e){
                            $this->moveProcessedFileError($file);
                            $this->middlewareCustom->sendChangeNotification((string)NotificationService::newErrorNotificationCouldNotReadJson($e->getMessage()));
                        }
                    }

                    if(str_ends_with($file, ".xml")){

                        try{
                            $mappedJSON =  $this->mappingService->MapFromXMLFormat($fileContent);
                            $this->middlewareCustom->sendOneOrMoreJsonToQueue($mappedJSON);

                            $this->moveProcessedFileOut($file);
                            $this->middlewareCustom->sendChangeNotification((string)NotificationService::newNotificationProcessedFile($file));
                        }catch(\Throwable $e){
                            $this->moveProcessedFileError($file);
                            $this->middlewareCustom->sendChangeNotification((string)NotificationService::newErrorNotificationCouldNotReadXml($e->getMessage()));
                        }
                    }

                    if(str_ends_with($file, ".xlsx")){

                        try{
                            $xlsxFile = SimpleXLSX::parseData( $fileContent, $debug = false );

                            $mappedJSON =  $this->mappingService->convertXLSXToJSON($xlsxFile);
                            $this->middlewareCustom->sendOneOrMoreJsonToQueue($mappedJSON);

                            $this->moveProcessedFileOut($file);
                            $this->middlewareCustom->sendChangeNotification((string)NotificationService::newNotificationProcessedFile($file));

                        }catch(\Throwable $e){
                            $this->middlewareCustom->sendChangeNotification((string)NotificationService::newErrorNotificationCouldNotReadXlsx($e->getMessage()));
                            $this->moveProcessedFileError($file);
                        }
                    }
                }
            }
        }
        catch (\Throwable $e)
        {
            $this->middlewareCustom->sendChangeNotification((string)NotificationService::newErrorNotificationFtpErrorConnection($e->getMessage()));
        }
        finally{
            $this->sftp->disconnect();
        }
    }

    /**
     * Se encarga de mover a la carpeta OUT los ficheros que han sido procesados automáticamente
     */
    public function moveProcessedFileOut($file){
        $inFullFolder= "/" .$_ENV['FTP_BASE_FOLDER'] . "/" . $_ENV['FTP_IN_FOLDER'];
        $inFullOutFolder= "/" .$_ENV['FTP_BASE_FOLDER'] . "/" . $_ENV['FTP_OUT_FOLDER'];

        $createdAt = new \DateTime("now");
        $createdAt = $createdAt->format('Y-m-d_h-m-s');

        $this->sftp->rename($inFullFolder."/".$file, $inFullOutFolder."/".$createdAt."_PROCESSED_".$file);
    }

    /**
     * Se encarga de mover a la carpeta ERROR los ficheros que no han podido ser procesados
     */
    public function moveProcessedFileError($file){
        $inFullFolder= "/" .$_ENV['FTP_BASE_FOLDER'] . "/" . $_ENV['FTP_IN_FOLDER'];
        $inFullErrorFolder= "/" .$_ENV['FTP_BASE_FOLDER'] . "/" . $_ENV['FTP_ERROR_FOLDER'];

        $createdAt = new \DateTime("now");
        $createdAt = $createdAt->format('Y-m-d_h-m-s');

        $this->sftp->rename($inFullFolder."/".$file, $inFullErrorFolder."/".$createdAt."_PROCESSED_".$file);
    }

    /**
     * Se encarga de guardar los json comunes que no han podido ser parseados
     * Estático para poder ser llamado desde cualquier parte y evitar dependencias circulares.
     *
     * @param string $content
     */
    public static function uploadFailedJson($content){
        $inFullErrorFolder= "/" . $_ENV['FTP_BASE_FOLDER'] . "/" . $_ENV['FTP_ERROR_FOLDER'];

        try {
        $sftp = new SFTP($_ENV['FTP_HOST'], $_ENV['FTP_PORT'], $_ENV['FTP_TIMEOUT']);
        $sftp->login($_ENV['FTP_USER'], $_ENV['FTP_PASSWORD']);

            $createdAt = new \DateTime("now");
            $createdAt = $createdAt->format('Y-m-d_h-m-s');

            $sftp->mkdir($inFullErrorFolder."/failedJson");
            $sftp->put($inFullErrorFolder . '/failedJson/'.$createdAt.'_failedJson.json', $content);
        }catch(\Throwable $t){

        }
    }
}
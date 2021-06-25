<?php


namespace App\Controller;


use App\Controller\FTPController;
use App\Entity\ProductSystem;
use App\Message\ChangeNotifications;
use App\Message\DataUpdates;
use App\MessageHandler\ChangeNotificationsHandler;
use App\Repository\ProductSystemRepository;
use App\Service\MappingService;
use App\Service\ProductSystemService;
use http\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use phpseclib3\Net\SFTP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;


class IndexController extends AbstractController
{
    public function index(FTPController $ftpController )
    {

/*
       $bus->dispatch(new ChangeNotifications($json))>with(new SerializerStamp([
           // groups are applied to the whole message, so make sure
           // to define the group for every embedded object
           'groups' => ['my_serialization_groups'],
       ]))
);
*/
        $ftpController->getFiles();

   //     $notificationsHandler();

        return $this->render('productSystemData.html.twig', array(
            /*
            'User' => $UserData,
            'UtilsCommonVars' => $utils->getVars()
            */
        ));

    }

    public function ftpConnectionTest(MappingService $mappingService, ProductSystemService $productSystemService, ProductSystemRepository $productSystemRepo)
    {
        $host = "localhost";
        $port = 2201;
        $timeout = 5;
        $user= "admin";
        $password = "passwordadmin";

        $baseFolder = "php-apache-1";

        $inFolder = "IN";
        $outFolder = "OUT";
        $errorFolder = "ERROR";

        try
        {
            $sftp = new SFTP($host, $port, $timeout);
            $sftp->login($user, $password);

            if(!$sftp->isConnected())
                throw new \Exception("No se ha podido conectar");

           $inFullFolder= "/" .$baseFolder . "/" . $inFolder;
           $files = ($sftp->nlist($inFullFolder));
           foreach($files as $file){
               if(str_ends_with($file, ".json") || str_ends_with($file, ".xml") || str_ends_with($file, ".csv") || str_ends_with($file, ".xslsx")){


                   $fileContent = $sftp->get($inFullFolder."/".$file);
                   if(str_ends_with($file, ".json")){

                       $mappedJSON =  $mappingService->MapFromJSONFormat($fileContent);
                       $productSystemService->sendOneOrMoreJson($mappedJSON);
                   }

                   if(str_ends_with($file, ".xml")){
                       $mappedJSON =  $mappingService->MapFromXMLFormat($fileContent);
                       $productSystemService->sendOneOrMoreJson($mappedJSON);
                   }
               }
           }
        }
        catch (\Exception $e)
        {
            echo "</br>";
            echo "\n" . $e . "\n";
            echo "</br>";
        }
        finally{
            $sftp->disconnect();
        }

        return $this->render('productSystemData.html.twig', array(
        ));

    }
    /*
    private function nlist_helper($dir, $recursive, $relativeDir)
    {
        $files = $this->readlist($dir, false);

        if (!$recursive || $files === false) {
            return $files;
        }

        $result = [];
        foreach ($files as $value) {
            if ($value == '.' || $value == '..') {
                $result[] = $relativeDir . $value;
                continue;
            }
            if (is_array($this->query_stat_cache($this->realpath($dir . '/' . $value)))) {
                $temp = $this->nlist_helper($dir . '/' . $value, true, $relativeDir . $value . '/');
                $temp = is_array($temp) ? $temp : [];
                $result = array_merge($result, $temp);
            } else {
                $result[] = $relativeDir . $value;
            }
        }

        return $result;
    }
    */
}
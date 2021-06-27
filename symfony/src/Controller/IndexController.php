<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;


class IndexController extends AbstractController
{

    /**
     * Llamando a la url /fetchFTP se ejecuta el proceso de obtención de datos del FTP
     */
    public function fetchFTP(FTPController $ftpController)
    {
            $ftpController->getFiles();

        return $this->render('productSystemData.html.twig', array(
        ));

    }


    /**
     * Llamando a la url /fetchRemoteApi se ejecuta el proceso de obtención de datos del Rest Api
     */
    public function fetchApi(FetchApiController $fetchApiController )
    {

        $fetchApiController->consumeAndFetch("https://run.mocky.io/v3/b493377e-2454-408b-98be-e03a9a80f88d");
        return $this->render('productSystemData.html.twig', array(
        ));

    }


}
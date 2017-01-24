<?php
/**
 * Created by PhpStorm.
 * User: basecom
 * Date: 09.12.16
 * Time: 09:59
 */
namespace AnnaKlipApi;

use Shopware\Components\Plugin;

class AnnaKlipApi extends Plugin
{

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        //Bei Aufruf der URL/klip wird Funktion ausgeführt
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_Klip' => 'onGetControllerPath',
            'Enlight_Controller_Action_PostDispatch_Backend_Index' => 'onPostDispatchBackendIndex'
        ];
    }

    /**
     * @return string
     */
    public function onGetControllerPath()
    {
        //Authorization checken, wenn nicht, dann Aufforderung zum Authorisieren
        if ( !isset($_SERVER['PHP_AUTH_USER']) ) {
            header('WWW-Authenticate: Basic realm="You Shall Not Pass"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        //Aufforderung zum Authorisieren, erst nachsehen od Username vorhanden, dann gucken ob Passwort (API key) passt, wenn passt, weiter zum Controller,
        //wenn nicht Nachricht von falscher Authentifizierung
        else {
            if ( $this->getUsername($_SERVER['PHP_AUTH_USER'])==true && $this->getPasswort($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])==true ) {
                return __DIR__ . '/Controllers/Frontend/KlipController.php';
            }
            else {
                echo '{
                        "success": false,
                        "message": "Invalid or missing auth"
                    
                       }';
                die();
            }

        }
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onPostDispatchBackendIndex(\Enlight_Controller_ActionEventArgs $args)
    {

    }

    //checken, ob Username in Datenbank vorhanden
    private function getUsername($typedname)
    {
        $name='0';
        $sql='SELECT
        username
        FROM
        s_core_auth';
        $stmt = Shopware()->Db()->query($sql);
        $result=$stmt->fetchAll();

        $i=0;
        while($i<count($result)) {
            if($result[$i]['username']==$typedname) {
                $name=$result[$i]['username'];
                $i=count($result);
            } else
                $i++;
        }

        if($name=='0')
            return false;
        else
            return true;
    }

    //Passwort mit Username vergleichen
    private function getPasswort($username, $typedpasswort)
    {
        $sql = 'SELECT
        apiKey
        FROM
        s_core_auth
        WHERE
        username="' . $username . '"';

        $stmt = Shopware()->Db()->query($sql);

        $result = $stmt->fetchAll();

        $passwort = $result[0]['apiKey'];

        if ($typedpasswort == $passwort)
            return true;
        else
            return false;
    }


}









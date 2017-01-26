<?php

namespace baseTShopfolio;

use Shopware\Components\Plugin;

/**
 * @author Anna Sophia Sommer <sommer@basecom.de>
 */
class baseTShopfolio extends Plugin
{

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        //After calling URL/klip function onGetControllerPath is carried out
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
        //checks if user is authorized, if not asks User to authorize himself
        if ( !isset($_SERVER['PHP_AUTH_USER']) ) {
            header('WWW-Authenticate: Basic realm="You Shall Not Pass"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }
        //request to the user to authorize himself, checks first if username in user database, then if password(API-Key) fits with username, if yes
        // Controller is called, if not message that authentication failed
        else {
            if ( $this->getUsername($_SERVER['PHP_AUTH_USER'])==true && $this->getPassword($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])==true ) {
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

    //checken, if username in database
    /**
     * @param $typedname
     * @return bool
     */
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

    //chacks if password fits with the username
    /**
     * @param $username
     * @param $typedpasswort
     * @return bool
     */
    private function getPassword($username, $typedpasswort)
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









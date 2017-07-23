<?php
namespace Controllers;

class SecurityController
{

    public static function checkAuthAction()
    {


        \ Twig_Autoloader::register();
        $loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $twig = new \Twig_Environment($loader, array(
            'cache' => false,
        ));


        if (isset($_POST['login']))
            return true;

        if ((!isset($_SESSION['user_id']))) {

            echo $twig->render('registration.html.twig'); die();
        }
        else
        return $_SESSION['user_id'];
    }
}
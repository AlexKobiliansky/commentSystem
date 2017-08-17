<?php
namespace Controllers;

class SecurityController
{
    private $loader;

    private $twig;

    public function __construct($connector)
    {
        \ Twig_Autoloader::register();
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    public function checkAuthAction()
    {
        if (isset($_POST['login'])) {
            return true;
        }
        if (!isset($_SESSION['user_id'])) {
            echo $this->twig->render('authorization.html.twig');
            die();
        } else {
            return $_SESSION['user_id'];
        }
    }

    public function checkAvailableAction($controller, $action)
    {
        $controllers = [
            'Controllers\SecurityController',
        ];
        $actions = [
            'registerPageAction',
            'authPageAction',
        ];

        if (in_array($controller, $controllers) && (in_array($action, $actions))) {
            return true;
        } else {
            return false;
        }
    }

    public function registerPageAction()
    {
        return $this->twig->render('registration.html.twig');
    }

    public function authPageAction()
    {
        return $this->twig->render('authorization.html.twig');
    }
}

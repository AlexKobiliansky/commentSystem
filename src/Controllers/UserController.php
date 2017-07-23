<?php
namespace Controllers;

use Controllers\CommentsController;
use Repositories\UserRepository;
use Repositories\CommentsRepository;


class UserController
{
    private $repository;

    private $loader;

    private $twig;

    private $commentsRepository;

    private $commentsController;

    public function __construct($connector)
    {
        $this->commentsRepository = new CommentsRepository($connector);
        $this->commentsController = new CommentsController($connector);
        $this->repository = new UserRepository($connector);
        \ Twig_Autoloader::register();
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }


    public function newAction()
    {

        $login = htmlspecialchars(trim($_POST['login']));
        $password = htmlspecialchars(trim($_POST['password']));
        $confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));

        if($this->repository->checkRegisterData($login, $password, $confirmPassword) !== true) {
            return $this->twig->render('registration.html.twig', [
                'message' => $this->repository->checkRegisterData($login, $password, $confirmPassword),
            ]);
        }

        if (($this->repository->find($login)) !== false) {
            return $this->twig->render('registration.html.twig', [
                'message' => 'User with such login already exists. Please try again',
                    ]);
            }

        $ext = array_pop(explode('.',$_FILES['myfile']['name'])); // extension
        $filename = time().'.'.$ext; // new name with extension

        $full_path = $_SERVER['DOCUMENT_ROOT'].'/web/avatars/'.$filename;
        move_uploaded_file($_FILES['myfile']['tmp_name'], $full_path);

            $this->repository->insert([
                    'login'    => $login,
                    'password' => md5($password),
                    'image'    => $filename,
                ]);

            $user = $this->repository->find($login);

            $_SESSION['user_id'] = $user->getId();

            $comments = $this->commentsRepository->findAll();
            return $this->twig->render('comments.html.twig', [
                'comments' => $comments,
            ]);
        }


    public function loginAction()
    {
        $login = htmlspecialchars(trim($_POST['login']));
        $password = htmlspecialchars(trim($_POST['password']));

        if($this->repository->checkAuthData($login, $password) !== true) {
            return $this->twig->render('authorization.html.twig', [
                'message' => $this->repository->checkAuthData($login, $password),
            ]);
        }

        $user = $this->repository->authentificate([
            'login'    => $login,
            'password' => md5($password),
        ]);

        if ($user === false) {
            return $this->twig->render('authorization.html.twig', [
                'message' => 'Cannot find User with such login and password. Please check credentials',
            ]);
        }

        $_SESSION['user_id'] = $user->getId();
        $comments = $this->commentsRepository->findAll();

        return $this->twig->render('comments.html.twig', [
                    'comments' => $comments,
                    'current_user' => $_SESSION['user_id'],
            ]);
    }


    public function logoutAction()
    {
        session_destroy();
        return $this->twig->render('authorization.html.twig');
    }
}

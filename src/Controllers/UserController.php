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
        if (!empty($_POST['login'])) {

            $this->repository->insert(
                [
                    'login' => $_POST['login'],
                ]
            );

            $user = $this->repository->find($_POST['login']);

            $_SESSION['user_id'] = $user->getId();

            $comments = $this->commentsRepository->findAll();
            return $this->twig->render('comments.html.twig', [
                'comments' => $comments,
            ]);
        }

        return $this->twig->render('registration.html.twig',
            [
                'message' => 'You comment is empty. Please type something and try again',
            ]
        );
    }

    public function logoutAction()
    {
        session_destroy();
        return $this->twig->render('registration.html.twig');
    }
}

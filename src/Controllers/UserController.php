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

        if (empty($_POST['login'])) {
            return $this->twig->render('registration.html.twig', [
                    'message' => 'Please enter your login',
                ]
            );
        } elseif (empty($_POST['password'])) {
            return $this->twig->render('registration.html.twig', [
                    'message' => 'Please enter your passsword',
                ]
            );
        } elseif (empty($_POST['confirmPassword']) || ($_POST['password'] !== $_POST['confirmPassword'])) {
            return $this->twig->render('registration.html.twig', [
                    'message' => 'Mismatch passwords. Please check and try again',
                ]
            );
        } else {

            if (($this->repository->find($_POST['login'])) !== false) {
                return $this->twig->render('registration.html.twig', [
                        'message' => 'User with such login already exists. Please try again',
                    ]
                );
            }

            $this->repository->insert(
                [
                    'login' => $_POST['login'],
                    'password' => $_POST['password'],
                ]
            );

            $user = $this->repository->find($_POST['login']);

            $_SESSION['user_id'] = $user->getId();

            $comments = $this->commentsRepository->findAll();
            return $this->twig->render('comments.html.twig', [
                'comments' => $comments,
            ]);
        }

        return $this->twig->render('registration.html.twig', [
                'message' => 'You comment is empty. Please type something and try again',
            ]
        );
    }

    public function loginAction()
    {
        if (empty($_POST['login'])) {
            return $this->twig->render('authorization.html.twig', [
                'message' => 'Please enter your login',
                ]);
        } elseif (empty($_POST['password'])) {
            return $this->twig->render('authorization.html.twig', [
                    'message' => 'Please enter your passsword',
                ]);
        } else {

            $user = $this->repository->authentificate([
                'login'    => $_POST['login'],
                'password' => $_POST['password']
            ]);

            if ($user === false) {
                return $this->twig->render('authorization.html.twig', [
                        'message' => 'Cannot find User with such login and password. Please check credentials',
                    ]
                );
            } else {
                $_SESSION['user_id'] = $user->getId();

                $comments = $this->commentsRepository->findAll();
                return $this->twig->render('comments.html.twig', [
                    'comments' => $comments,
                ]);
            }
        }

    }

    public function logoutAction()
    {
        session_destroy();
        return $this->twig->render('authorization.html.twig');
    }
}

<?php

namespace Controllers;

use Repositories\CommentsRepository;

class CommentsController
{
    private $repository;

    private $loader;

    private $twig;

    public function __construct($connector)
    {
        $this->repository = new CommentsRepository($connector);
        \ Twig_Autoloader::register();
        $this->loader = new \Twig_Loader_Filesystem('src/Views/templates/');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => false,
        ));
    }

    public function indexAction()
    {
        $commentsData = $this->repository->findAll();

        return $this->twig->render('comments.html.twig', ['comments' => $commentsData]);
    }


    public function newAction()
    {
        if (!empty($_POST['content'])) {

            $this->repository->insert(
                [
                    'content' => $_POST['content'],
                ]
            );
            return $this->indexAction();
        }

        return $this->twig->render('comments.html.twig',
            [
                'message' => 'You comment is empty. Please type something and try again',
            ]
        );
    }

        public function editAction()
        {
            if (isset($_POST['content'])) {

                $this->repository->update(
                    [
                        'content' => $_POST['content'],
                        'id'      => (int) $_GET['id'],
                    ]
                );
                return $this->indexAction();
            }

            $comment = $this->repository->find((int) $_GET['id']);

            return $this->twig->render('comments_form.html.twig',
                [
                    'content' => $comment->getContent(),
                    'id'      => $comment->getId(),
                ]
            );
        }

        public function deleteAction()
        {
            if (!empty($_GET['id'])) {
                $id = (int) $_GET['id'];
                $this->repository->remove(['id' => $id]);
                return $this->indexAction();
            }

            return $this->twig->render('comments.html.twig');
        }
}
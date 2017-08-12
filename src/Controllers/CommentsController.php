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
        return $this->twig->render('comments.html.twig', [
            'comments' => $commentsData,
            'current_user' => $_SESSION['user_id'],
        ]);
    }

    public function newAction()
    {
        sleep(1);
        if (!empty($_POST['content'])) {
            $comment = $this->repository->insert(
                [
                    'content' => $_POST['content'],
                    'likes'   => 0,
                ]);

            return $this->twig->render('comment_block.html.twig', [
                'comment'     => $comment,
                'current_user' => $_SESSION['user_id'],
            ]);
        }

        return "empty";
        /*return $this->twig->render('comments.html.twig',
            [
                'message' => 'You comment is empty. Please type something and try again',
            ]);*/
    }

    public function editAction()
    {
        sleep(1);
        if (isset($_POST['content'])) {
            $comment = $this->repository->find($_GET['id']);
            $likes = $comment->getLikes();

            $this->repository->update(
                [
                    'content' => $_POST['content'],
                    'id'      => (int) $_GET['id'],
                    'likes'   => $likes
                ]);

            return $this->indexAction();
        }

        $comment = $this->repository->find((int) $_GET['id']);

        return $this->twig->render('comments_form.html.twig',
            [
                'content'     => $comment->getContent(),
                'id'          => $comment->getId(),
                'dateCreated' => $comment->getDateCreated(),
            ]);
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

    public function createSubcommentAction()
    {
        if (!empty($_POST['content'])) {
            $this->repository->createSubcomment(
                [
                    'content'   => $_POST['content'],
                    'parent_id' => $_GET['parent_id'],
                    'likes'     => 0,
                ]);

            return $this->indexAction();
        }

        return $this->twig->render('comments.html.twig',
            [
                'message' => 'You comment is empty. Please type something and try again',
            ]);
    }

    public function likeAction()
    {
        $comment = $this->repository->find($_GET['id']);
        if ($this->repository->checkCounter([
            'comment_id' => $_GET['id'],
            'user_id'    =>$_SESSION['user_id'],
        ])) {
            $likes = $comment->getLikes()+1;
            $color = 'steeltblue';
        } else {
            $likes = $comment->getLikes()-1;
            $color= 'lightblue';
        }

        $this->repository->update([
            'content' => $comment->getContent(),
            'id'      => (int) $_GET['id'],
            'likes'   => $likes
        ]);

        return json_encode([$likes, $color]);
    }
}

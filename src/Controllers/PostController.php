<?php

namespace Controllers;

use Layer\Manager\CategoryManager;
use Layer\Manager\PostManager;

class PostController
{
    /**
     * @var PostManager
     */
    private $postManager;

    /**
     * @var CategoryManager
     */
    private $categoryManager;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * PostController constructor.
     * @param PostManager $postManager
     * @param CategoryManager $categoryManager
     * @param \Twig_Environment $twig
     */
    public function __construct(PostManager $postManager, CategoryManager $categoryManager, \Twig_Environment $twig)
    {

        $this->postManager = $postManager;
        $this->categoryManager = $categoryManager;
        $this->twig = $twig;
    }

    /**
     * @return string
     */
    public function indexAction()
    {
        $posts = $this->postManager->findAll();

        return $this->twig->render('Post/index.html.twig', [
            'posts' => $posts,
        ]);

    }

    /**
     * @param $id
     * @return string
     */
    public function showAction($id)
    {
        return $this->twig->render('Post/show.html.twig', [
            'post' => $this->postManager->find($id),
        ]);
    }

    /**
     * @return string
     */
    public function createAction()
    {
        if (isset($_POST['post'])) {
            $post = $this->postManager->createObject($_POST['post']);
            $this->postManager->insert($post);
        }

        $categories = $this->categoryManager->findAll();

        return $this->twig->render('Post/create.html.twig', [
            'categories' => $categories,
        ]);
    }

    public function editAction($id)
    {

    }
}
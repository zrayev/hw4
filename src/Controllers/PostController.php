<?php

namespace Controllers;

use Entity\Category;
use Entity\Post;
use Entity\Tag;
use Layer\Manager\CategoryManager;
use Layer\Manager\PostManager;
use Layer\Manager\TagManager;

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
     * @var TagManager
     */
    private $tagManager;

    /**
     * PostController constructor.
     * @param PostManager $postManager
     * @param CategoryManager $categoryManager
     * @param \Twig_Environment $twig
     * @param TagManager $tagManager
     */
    public function __construct(PostManager $postManager, CategoryManager $categoryManager, \Twig_Environment $twig, TagManager $tagManager)
    {
        $this->postManager = $postManager;
        $this->categoryManager = $categoryManager;
        $this->twig = $twig;
        $this->tagManager = $tagManager;
    }

    /**
     * @return string
     */
    public function indexAction()
    {
        $posts = $this->postManager->findAll();

        return $this->twig->render('Post/index.html.twig', [
            'posts' => $posts,
            'tags' => $this->tagManager->findAll(),
            'categories' => $this->categoryManager->findAll(),
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

    /**
     * @param $id
     * @return string
     */
    public function editAction($id)
    {
        /** @var Post $post */
       $post = $this->postManager->find($id);

        if (isset($_POST['post'])) {
            $post
                ->setBody($_POST['post']['body'])
                ->setTitle($_POST['post']['title'])
                ->setCategory($_POST['post']['category_id'])
                ->setTags($_POST['post']['tag_ids'])
            ;
            $this->postManager->update($post);
        }

        return $this->twig->render('Post/edit.html.twig', [
            'post' => $post,
            'categories' => $this->categoryManager->findAll(),
            'tags' => $this->tagManager->findAll(),
        ]);
    }

    /**
     * @param $tagId
     * @return string
     */
    public function listByTagAction($tagId)
    {
        /** @var Tag $tag */
        $tag = $this->tagManager->find($tagId);
        /** @var Post[] $posts */
        $posts = $this->postManager->findByTag($tag);

        return $this->twig->render('Post/index.html.twig', [
            'posts' => $posts,
            'tags' => $this->tagManager->findAll(),
            'categories' => $this->categoryManager->findAll(),
        ]);
    }

    /**
     * @param $categoryId
     * @return string
     */
    public function listByCategoryAction($categoryId)
    {
        /** @var Category $category */
        $category = $this->categoryManager->find($categoryId);
        /** @var Post[] $posts */
        $posts = $this->postManager->findByCategory($category);

        return $this->twig->render('Post/index.html.twig', [
            'posts' => $posts,
            'tags' => $this->tagManager->findAll(),
            'categories' => $this->categoryManager->findAll(),
        ]);
    }
}
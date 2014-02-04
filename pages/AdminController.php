<?php

use Blog\Controller;

Class AdminController extends Controller
{

    /**
     * get Article action :
     * Affiche la page /admin
     * 
     *
     * @return string  html rendu par twig
     */
    public function getArticle()
    {
        $data = array();

        $data['user'] = $this->isLogged();

        $user = $this->app['session']->get('user');

        //Check the admin status of the user. If not, redirected to home.
        if('admin' != $user['status']){
            $articles = $this->app['sql']->query('SELECT * FROM  articles');
            $data['articles'] = $articles->fetchAll();
            return $this->app['twig']->render('home.twig', $data);
        }

        //Get tags available
        $tags = $this->app['sql']->query('SELECT
                tag.id as tagId,
                tag.name
            FROM
                 tag');

        $data['tags'] = $tags->fetchAll();
            

        return $this->app['twig']->render('admin.twig', $data);
    }


    /**
     * [postArticle description]
     * @return [type] [description]
     */
    public function postArticle()
    {

        $title = $this->app['request']->get('title');
        $article = $this->app['request']->get('article');
        $tags = $this->app['request']->get('tags');
        print_r($tags);

        if (!empty($title) && !empty($article)) {
            $sql = "INSERT INTO articles (
                id ,
                title ,
                body
            )
            VALUES (
                NULL ,
                :title,
                :body
            )";

            $arguments = array(
                ':title' => $title,
                ':body' => $article,
            );

            $request = $this->app['sql']->prepareExec($sql, $arguments);
        }

        if (!empty($tags)){

            $lastId = $this->app['sql']->lastId();
            
            foreach ($tags as &$value) {
                 $sql = "INSERT INTO articles_tag (
                        id ,
                        id_articles,
                        id_tag
                    )
                    VALUES (
                        NULL ,
                        :lastId,
                        :tag
                    )";

                    $arguments = array(
                        ':lastId' => $lastId,
                        ':tag' => $value,
                    );

                    $this->app['sql']->prepareExec($sql, $arguments);
                }
            }

        return $this->getArticle();
    }

}

<?php

use Blog\Controller;

Class TagsCreatorController extends Controller
{

    /**
     * get Article action :
     * Affiche la page /admin
     * 
     *
     * @return string  html rendu par twig
     */
    public function getTags()
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
            

        return $this->app['twig']->render('tagscreator.twig', $data);
    }


    /**
     * [postArticle description]
     * @return [type] [description]
     */
    public function postTags()
    {

        $tag = $this->app['request']->get('tag');

        if (!empty($tag)) {
            $sql = "INSERT INTO tag (
                id ,
                name
            )
            VALUES (
                NULL ,
                :name
            )";

            $arguments = array(
                ':name' => $tag,
            );

            $this->app['sql']->prepareExec($sql, $arguments);
        }

        return $this->getTags();
    }

}

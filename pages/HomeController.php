<?php

use Blog\Controller;

Class HomeController extends Controller
{
	public function displayArticle () 
	{
		$data = array();

		$data['user'] = $this->isLogged();

		$articles = $this->app['sql']->query('SELECT
                           articles.id as articlesId,
                           title,
                           body,
                           tag.id as tagId,
                           tag.name
                       FROM articles
                       LEFT JOIN articles_tag
                       ON articles.id = articles_tag.id_articles
                       LEFT JOIN tag
                       ON articles_tag.id_tag = tag.id
                       ORDER BY articlesId
                       ');

		//Putting the request result into a new table
		$result = array();
		while ($row = $articles->fetch()) {
			$result[$row['articlesId']]['title'] = $row['title'];
			$result[$row['articlesId']]['body'] = $row['body'];
			$result[$row['articlesId']]['tags'][$row['tagId']] = $row['name'];
		}
		
		$data['articles'] = $result;

		return $this->app['twig']->render('home.twig', $data);

	}

	public function displayArticlesByTag($tag)
	{
	//Ma requete SQL
	//


	$articles = $this->app['sql']->query("SELECT
					articles.id as articlesId,
					title,
					body,
					tag.id as tagId,
					tag.name
				FROM articles
				LEFT JOIN articles_tag
					ON articles.id = articles_tag.id_articles
				LEFT JOIN tag
					ON articles_tag.id_tag = tag.id
				ORDER BY articlesId
				");

	$result = array();
	while ($row = $articles->fetch()) {
	
		if($row['tagId'] == $tag){
			$result[$row['articlesId']]['title'] = $row['title'];
			$result[$row['articlesId']]['body'] = $row['body'];
			$result[$row['articlesId']]['tags'][$row['tagId']] = $row['name'];
		}
	}

	
	$data['articles'] = $result;

		return $this->app['twig']->render('home.twig', $data);
	}
}
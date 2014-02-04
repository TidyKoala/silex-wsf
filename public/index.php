<?php

// Autoload Composer
require_once __DIR__.'/../vendor/autoload.php';

//Init silex application
$app = new Silex\Application();


//Debug
$app['debug'] = true;

//load config
require_once __DIR__.'/../config/database.php';

//init Database
$app['sql'] = new Blog\Sql(
    $config['server'],
    $config['database'],
    $config['id'],
    $config['password']
);

//Register twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

//register service url generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

//register service session
$app->register(new Silex\Provider\SessionServiceProvider());

//Création route home
$app->get('/', function () use ($app) {
    $c = new HomeController($app);
    return $c->displayArticle();
})
->bind('home');

//Création route /admin
$app->get('/admin', function () use ($app) {
    $c = new AdminController($app);
    return $c->getArticle();
})
->bind('getAdmin');

//route post /admin
$app->post('/admin', function () use ($app) {
    $c = new AdminController($app);
    return $c->postArticle();
})
->bind('postAdmin');

//route user
$app->get('/login', function () use ($app) {
    $c = new UserController($app);
    return $c->getLogin();
})
->bind('login');


$app->post('/login', function () use ($app) {
    $c = new UserController($app);
    return $c->postLogin();
})
->bind('postLogin');


$app->get('/register', function () use ($app) {
    $c = new UserController($app);
    return $c->getRegister();
})
->bind('register');


$app->post('/register', function () use ($app) {
    $c = new UserController($app);
    return $c->postRegister();
})
->bind('postRegister');

//Route to tags creation page
$app->get('/tagscreator', function () use ($app) {
    $c = new TagsCreatorController($app);
    return $c->getTags();
})
->bind('tagscreator');

$app->post('/tagscreator', function () use ($app) {
    $c = new TagsCreatorController($app);
    return $c->postTags();
})
->bind('postTagscreator');


//Creation route for filter by tag on homepage
$app->get('/{tag}', function ($tag) use ($app) {
    $c = new HomeController($app);
    return $c->displayArticlesByTag($tag);
});

//run app
$app->run();

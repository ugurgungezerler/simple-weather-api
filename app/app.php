<?php
/**
 * Local variables
 *
 * @var \Phalcon\Mvc\Micro $app
 */

use Phalcon\Mvc\Micro\Collection as MicroCollection;

$di->setShared('app', function () use ($app) {
    return $app;
});


$app->get(
  '/', function () {
    echo $this['view']->render('index');
})->setName('/');

//$app->get('/user', function () {
//    return $this->response($this->auth);
//})->setName('profile');

//AuthController
$auth = new MicroCollection();
$auth->setHandler(new AuthController);
$auth->setPrefix('/auth');
$auth->post('/login', 'login', 'login');
$auth->post('/register', 'register', 'register');
$app->mount($auth);

//UserController
$auth = new MicroCollection();
$auth->setHandler(new UserController);
$auth->setPrefix('/user');
$auth->get('/', 'profile', 'profile');
$auth->patch('/update', 'update', '');
$app->mount($auth);


/**
 * Not found handler
 */
$app->notFound(
  function () use ($app) {
      $app->response->setStatusCode(404, "Not Found")->sendHeaders();
      $app->response->setJsonContent(['message'=>'404 not found']);
      $app->response->send();
  });

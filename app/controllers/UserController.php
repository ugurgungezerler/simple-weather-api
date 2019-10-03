<?php

use Phalcon\Mvc\Controller;

class UserController extends BaseController
{
    public function profile()
    {
        return $this->response($this->app->auth);
    }

    public function update()
    {

    }


}
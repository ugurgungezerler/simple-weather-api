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
        $rawBody = $this->request->getJsonRawBody(true);

        $user = $this->app->auth;
        // Check selected city is exists
        $cityId = $rawBody['city_id'];
        if ($cityId) {
            $city = Cities::findFirst([
              'conditions' => 'id = ?1',
              'bind' => [
                1 => $cityId,
              ]
            ]);
            if (!$city) {
                return $this->abort('Selected city is not exists');
            }
            $user->city_id = $cityId;
        }
        $user->lang = $rawBody['lang'];
        $user->os = $rawBody['os'];
        $user->device_token = isset($rawBody['device_token']) ? $rawBody['device_token'] : '';

        if (!$user->update()) {
            return $this->validationResponse($user);
        }
        //hide password?
        unset($user->password);

        return $this->response($user, 'User Updated');
    }



}
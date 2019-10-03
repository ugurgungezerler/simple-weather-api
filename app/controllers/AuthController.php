<?php

use Phalcon\Mvc\Controller;

/**
 * Class AuthController
 */
class AuthController extends BaseController
{
    /**
     * Register API
     *
     * @return \Phalcon\Http\Response
     */
    public function register()
    {
        $rawBody = $this->request->getJsonRawBody(true);

        // Check selected city is exists
        $cityId = $rawBody['city_id'];
        if ($cityId) {
            $city = Cities::findFirst(["id = $cityId"]);
            if (!$city) {
                return $this->abort('Selected city is not exists');
            }
        }

        $user = new Users();
        $user->email = $rawBody['email'];
        $user->password = $this->security->hash($rawBody['password']);
        $user->lang = $rawBody['lang'];
        $user->os = $rawBody['os'];
        $user->device_token = isset($rawBody['device_token']) ? $rawBody['device_token'] : '';

        if (!$user->save()) {
            return $this->validationResponse($user);
        }

        //hide password?
        unset($user->password);

        $token = $this->createToken($this, $user);

        return $this->response(['token' => $token, 'user' => $user], 'Register completed successfully.');

    }

    /**
     * Login API
     *
     * @return \Phalcon\Http\Response
     */
    public function login()
    {
        $rawBody = $this->request->getJsonRawBody(true);

        $email = $rawBody['email'];
        $password = $rawBody['password'];

        $user = Users::findFirst(["email = '$email'"]);
        if ($user) {
            if ($this->security->checkHash($password, $user->password)) {

                $token = $this->createToken($this, $user);

                //hide password from $this->response?
                unset($user->password);

                return $this->response(['token' => $token, 'user' => $user], 'success');
            } else {
                return $this->abort('The wrong email or password.');
            }
        } else {
            // To protect against timing attacks. Regardless of whether a user
            // exists or not, the script will take roughly the same amount as
            // it will always be computing a hash.
            $this->security->hash(rand());
        }

        return $this->abort('The wrong email or password.');
    }
}
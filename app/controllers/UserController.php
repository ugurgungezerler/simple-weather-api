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

        $cityId = $rawBody['city_id'];
        $cityId && $user->city_id = $cityId;

        $user->lang = $rawBody['lang'];
        $user->os = $rawBody['os'];
        $user->device_token = isset($rawBody['device_token']) ? $rawBody['device_token'] : '';

        if (!$user->update()) {
            return $this->validationResponse($user);
        }
        //hide password?
        $user->password = null;

        return $this->response($user, 'User Updated');
    }

    public function redeem()
    {
        $rawBody = $this->request->getJsonRawBody(true);
        if (isset($rawBody['code'])) {
            $code = $rawBody['code'];
            $user = $this->app->auth;

            if ($user->is_premium) {
                return $this->abort('User is already premium');
            }

            $coupon = Coupons::findFirst([
              'conditions' => 'code = ?1',
              'bind' => [
                1 => $code,
              ]
            ]);
            if (!$coupon) {
                return $this->abort('Coupon is not valid.');
            }


            try {
                $coupon->validOrFail();
            } catch (Exception $e) {
                return $this->abort($e->getMessage());
            }


            $coupon->remain--;
            $coupon->update();

            $user->is_premium = 1;
            $user->update();

            return $this->response(null, 'Coupon Activated!');

        }

        return $this->abort('Wrong code');
    }


}
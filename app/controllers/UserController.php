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

    public function redeem()
    {
        $rawBody = $this->request->getJsonRawBody(true);
        if (isset($rawBody['code'])) {
            $code = $rawBody['code'];
            $user = $this->app->auth;

            if ($user->is_premium) {
                return $this->abort('User already premium');
            }

            $coupon = Coupons::findFirst([
              'conditions' => 'code = ?1',
              'bind' => [
                1 => $code,
              ]
            ]);

            $expires = DateTime::createFromFormat('Y-m-d H:i:s', $coupon->expire_at);

            if (new DateTime() > $expires) {
                return $this->abort('Coupon is expired');
            }

            if ($coupon->remain < 1) {
                return $this->abort('Coupon is not valid');
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
<?php
require_once('UnitTestCase.php');

/**
 * Class UnitTest
 */
class UnitTest extends UnitTestCase
{

    /**
     * Rest API Login Test
     */
    public function testLogin()
    {

        $response = $this->provider->post(
          'auth/login',
          json_encode([
            'email' => 'john0@gmail.com',
            'password' => '12345',
          ])
        );
        $this->assertEquals(
          $response->header->statusCode,
          '200'
        );

    }

    /**
     * Rest Register Test
     */
    public function testRegister()
    {
        $response = $this->register();
        $this->assertEquals(
          $response->header->statusCode,
          '201'
        );
    }


    /**
     * Profile Test
     */
    public function testProfile()
    {
        //TODO : inject Users Modal instead register
        $response = $this->register();
        $data = json_decode($response->body);
        $this->provider->header->set('Authorization', $data->data->token);

        $response = $this->provider->get(
          'user'
        );

        $this->assertEquals(
          $response->header->statusCode,
          '200'
        );
    }

    /**
     * Get coupons and activate test
     */
    public function testRedeemCoupon()
    {
        //TODO : inject  Users Modal instead register
        $response = $this->register();
        $user = json_decode($response->body);
        $this->provider->header->set('Authorization', $user->data->token);

        //TODO : inject  Coupons Modal instead register
        $response = $this->provider->get(
          'dummy/coupons'
        );
        $dummyCoupons = json_decode($response->body);
        $firstCoupon = $dummyCoupons->data[0]->code;

        $response = $this->provider->post(
          'user/redeem',
          json_encode([
            'code' => $firstCoupon
          ])
        );

        $this->assertEquals(
          $response->header->statusCode,
          '200'
        );
    }

    /**
     * @return \Phalcon\Http\Client\Response
     */
    public function register()
    {
        $response = $this->provider->post(
          'auth/register',
          json_encode([
            'email' => "{$this->generateRandomString()}@gmail.com",
            'password' => '12345',
            'lang' => 'en',
            'os' => 'ios', // TODO : Random functions
            'city_id' => 2
          ])
        );
        return $response;
    }

    /**
     * @param int $length
     * @return string
     */
    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
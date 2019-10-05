<?php
require_once('UnitTestCase.php');

class UnitTest extends UnitTestCase
{

    public function testLogin()
    {

        $response = $this->provider->post(
          'auth/login',
          [
            'email' => 'john0@gmail.com',
            'password' => '12345',
          ]
        );

        $this->assertEquals(
          $response->header->statusCode,
          '200'
        );

//        echo $response->body;
//
//        echo $response->body;
//        echo $response->header->get('Content-Type');
//        echo $response->header->statusCode;
    }

    public function testRegister()
    {
        $response = $this->provider->post(
          'auth/register',
          [
            'email' => "{$this->generateRandomString}@gmail.com",
            'password' => '12345',
            'lang' => array_rand(["tr", "en"]),
            'os' => array_rand(["ios", "android"]),
            'city_id' => array_rand([1, 2, 3, 4, 5])
          ]
        );
        $this->assertEquals(
          $response->header->statusCode,
          '200'
        );
    }

    public static function generateRandomString($length = 10)
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
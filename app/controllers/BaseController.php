<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class BaseController extends Controller
{
    public function response($data = [], $message = "", $statusCode = 200)
    {
        $response = new Response();
        $response->setStatusCode($statusCode);
        $response->setContentType('application/json');
        $response->setJsonContent(['message' => $message, 'data' => $data]);
        return $response;
    }

    public function abort($message, $data = [], $statusCode = 400)
    {
        return $this->response($data, $message, $statusCode);
    }

    public function validationResponse($result)
    {
        $messages = [];
        foreach ($result->getMessages() as $r) {
            $messages[] = $r->getMessage();
        }
        return $this->abort($messages[0], $messages);
    }

    public function createToken($app, $user)
    {
        $signer = new Sha256();
        $privateKey = $app->config->jwt->secret;
        $time = time();
        $token = (new Builder())->issuedBy($app->config->app->url)// Configures the issuer (iss claim)
        ->permittedFor($app->config->app->url)// Configures the audience (aud claim)
        ->identifiedBy($app->config->jwt->identifier, true)// Configures the id (jti claim), replicating as a header item
        ->issuedAt($time)// Configures the time that the token was issue (iat claim)
        ->expiresAt($time + $app->config->jwt->life)// Configures the expiration time of the token (exp claim)
        ->withClaim('uid', $user->id)// Configures a new claim, called "uid"
        ->getToken($signer, new \Lcobucci\JWT\Signer\Key($privateKey)); // Retrieves the generated token

        return (string)$token;
    }
}
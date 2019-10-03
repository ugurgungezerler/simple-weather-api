<?php

use Lcobucci\JWT\ValidationData;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Lcobucci\JWT\Parser;

/**
 * Class AuthMiddleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Before middleware
     *
     * @param Event $event
     * @param Micro $app
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Micro $app)
    {
        $authorizeExceptions = [
          '/',
          'login',
          'register'
        ];

        if (!in_array($app->router->getMatchedRoute()->getName(), $authorizeExceptions)) {
            $result = $this->authorize($app);
            if (is_null($result)) {
                $app->response->setJsonContent(['message' => 'Please authorize with valid API token!']);
                $app->response->setStatusCode(401, 'Please authorize with valid API token!');
                $app->response->send();
                die();
            }
        }

        // We accept only application/json content in POST and PUT methods
        if (in_array($app->request->getMethod(),
            ['POST', 'PUT', 'PATCH']) AND $app->request->getHeader('Content-Type') != 'application/json') {
            $app->response->setJsonContent(['message' => 'Only application/json is accepted for Content-Type in POST requests.']);
            $app->response->setStatusCode(400);
            $app->response->send();
            die();
        }

        return true;
    }

    /**
     * Authorize user token from header
     *
     * @param Micro $app
     * @return null|\Phalcon\Mvc\Model\ResultInterface|Users
     */
    private function authorize(Micro $app)
    {
        $app->token = null;
        $authorizationHeader = $app->request->getHeader('Authorization');
        $auth = null;
        if (strlen($authorizationHeader) > 20) {
            $app->token = $authorizationHeader;
            try {
                $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
                $data->setIssuer($app->config->app->url);
                $data->setAudience($app->config->app->url);
                $data->setId($app->config->jwt->identifier);
                $token = (new Parser)->parse($app->token);

                if (!$token->validate($data)) {
                    throw new Exception('Token is expired');
                }

                //get user
                $uid = $token->getClaim('uid');
                $auth = Users::findFirst([
                  'conditions' => 'id = ?1',
                  'bind' => [
                    1 => $uid,
                  ]
                ]);

                //inject authenticated user to app
                $app->auth = $auth;
            } catch (Exception $e) {

            }
        }

        return $auth;
    }

    /**
     * @param Micro $app
     * @return bool
     */
    public function call(Micro $app)
    {
        return true;
    }
}
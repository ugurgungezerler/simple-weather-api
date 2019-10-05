<?php
use Phalcon\Di;
use Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{
    /**
     * @var bool
     */
    private $_loaded = false;

    public $provider;

    public $apiUrl = 'http://127.0.0.1/';

    public function __construct()
    {
        parent::__construct();
        $this->provider = \Phalcon\Http\Client\Request::getProvider();
        $this->provider->setBaseUri($this->apiUrl);
        $this->provider->header->set('Accept', 'application/json');

    }

    public function setUp()
    {
        parent::setUp();

        // Load any additional services that might be required during testing
        $di = Di::getDefault();

        // Get any DI components here. If you have a config, be sure to pass it to the parent

        $this->setDi($di);

        $this->_loaded = true;
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->_loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError(
              "Please run parent::setUp()."
            );
        }
    }
}
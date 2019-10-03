<?php

use Phalcon\Mvc\Model\Query;

class NotificationCron
{

    public function __construct()
    {
        $cron = new Notification();
        $cron->check();
        $cron->send();
    }


}
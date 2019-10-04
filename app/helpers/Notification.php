<?php

use Phalcon\Mvc\Model\Query;

/**
 * Class Notification
 */
class Notification
{
    /**
     * @var DateTime
     */
    public $date;
    /**
     * @var null|\Phalcon\DiInterface
     */
    public $di;
    /**
     * @var db
     */
    public $db;
    /**
     * @var mixed
     */
    public $config;

    /**
     * @var
     */
    public $notificationUsersData;

    //when users get the notification
    /**
     * @var string
     */
    public $time;

    /**
     * Notification constructor.
     */
    public function __construct()
    {
        $this->date = new DateTime("now", new \DateTimeZone("UTC"));
        $this->di = Phalcon\DI::getDefault();
        $this->db = $this->di->getShared('db');
        $this->config = $this->di->get('config');
        $this->time = $this->config->app->notification_time;
    }

    /**
     *
     */
    public function getUsers()
    {
        $date = $this->date->format('Y-m-d H:i');
        $query = $this->db->query("SELECT
              cities.*,
              weather_infos.date,
              weather_infos.celsius,
              users.email,
              users.device_token,
              time( CONVERT_TZ( '$date', '+00:00', timezone ) ) AS time,
              date( CONVERT_TZ( '$date', '+00:00', timezone ) ) AS _DATE 
            FROM
              cities
              LEFT JOIN weather_infos ON cities.id = weather_infos.city_id
              LEFT JOIN users ON users.city_id = cities.id 
            HAVING
              TIME = '$this->time' 
              AND _DATE = weather_infos.date");
        $query->setFetchMode(Phalcon\Db::FETCH_ASSOC);
        $result = $query->fetchAll($query);

        $this->notificationUsersData = $result;
    }


    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->notificationUsersData);
    }

    /**
     * @return string
     */
    public function send()
    {
        //TODO : Chunk data and send Notifications with queue
//        foreach ($this->notificationUsersData as $notification)
//          'message' => "Today's weather in {$data->name} {$data->celsius}C"
//            $notification->send()->queue('push');
//        }
        if ($this->getCount()) {
            return "Send Successfully - ({$this->getCount()}) Notification";
        }else{
            return "There is no city at time $this->time or no users in city";
        }
    }
}
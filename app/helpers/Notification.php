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
     * @var mixed
     */
    public $config;

    /**
     * @var
     */
    public $readyCities;
    /**
     * @var
     */
    public $readyCityIds;

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
        $this->config = $this->di->get('config');
        $this->time = $this->config->app->notification_time;
    }

    /**
     *
     */
    public function check()
    {
        //Get Cities with current local date
        $this->getReadyCities();

        $this->injectWeatherInfosToCities();

        $this->createNotificationData();

    }

    /**
     *
     */
    private function injectWeatherInfosToCities()
    {
        $implodeIds = implode(',', $this->readyCityIds);

        $onlyDate = $this->date->format('Y-m-d');
        $weatherInfo = WeatherInfos::find([
          'conditions' => "date='{$onlyDate}' AND city_id IN ({$implodeIds})",
        ]);

        foreach ($weatherInfo->toArray() as $info) {
            $this->readyCities[$info['city_id']]['date'] = $info['date'];
            $this->readyCities[$info['city_id']]['celsius'] = $info['celsius'];
        }
    }


    /**
     * Relations ile refaktör edilebilir.
     *
     * Burada relations ile birden çok sorgu yerine key
     *
     * yöntemi ile tek seferde data oluşturulmuştur.
     *
     */
    private function getReadyCities()
    {

        $query = new Query(
          "SELECT Cities.*, CONVERT_TZ('{$this->date->format('Y-m-d H:i')}','+00:00',timezone) AS time FROM Cities",
          $this->di
        );
        $cities = $query->execute();

        //Filter cities local time 09:00
        $readyCities = [];
        foreach ($cities as $city) {
            $date = new DateTime($city->time);
            if ($date->format('H:i') === $this->time) {
                $readyCities[$city->cities->id] = [];
                $readyCities[$city->cities->id]['name'] = $city->cities->name;
                $readyCities[$city->cities->id]['timezone'] = $city->cities->timezone;
                $readyCities[$city->cities->id]['id'] = $city->cities->id;
            }
        };

        $this->readyCities = $readyCities;

        $this->readyCityIds = array_keys($this->readyCities);

        if (!count($this->readyCityIds)) {
            echo "No city ready";
            exit;
        }


    }

    /**
     *
     */
    private function createNotificationData()
    {
        $implodeIds = implode(',', $this->readyCityIds);

        $usersForNotification = Users::find([
          'conditions' => 'city_id IN (' . $implodeIds . ')',
        ]);
        $data = [];

        foreach ($usersForNotification as $user) {
            $city = $this->readyCities[$user->city_id];
            $data[] = [
              'device_token' => $user->device_token,
              'message' => "Today's weather in {$city['name']} {$city['celsius']}C"
            ];
        }

        $this->notificationUsersData = $data;
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

//        foreach ($this->notificationUsersData as $notification) {
//            $notification->send()->queue('push');
//        }
        return "Send Successfully - ({$this->getCount()}) Notification";
    }
}
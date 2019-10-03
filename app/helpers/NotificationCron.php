<?php

use Phalcon\Mvc\Model\Query;

class NotificationCron
{
    public $date;
    public $di;

    public $readyCities;
    public $readyCityIds;

    public $notificationUsersData;

    public function __construct($table = false)
    {
        $this->date = new DateTime("now", new \DateTimeZone("UTC"));
        $this->di = Phalcon\DI::getDefault();
    }

    public function calc()
    {
        //Get Cities with current local date
        $this->getReadyCities();

        $this->injectWeatherInfosToCities();

        $this->createNotificationData();

    }

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
            if ($date->format('H:i') === '20:15') {
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

    public function getCount()
    {
        return count($this->notificationUsersData);
    }

    public function send()
    {
        //TODO : Chunk data and send Notifications with queue

//        foreach ($this->notificationUsersData as $notification) {
//            $notification->send()->queue('push');
//        }
        return "Send Successfully - ({$this->getCount()}) Notification";
    }
}
<?php

class DummyController extends BaseController
{

    public function indexAction()
    {
        try {
            $cities = Cities::find();
            if (count($cities)) {
                return $this->abort('Dummy data already inserted');
            }
            $batch = new BatchInsert('cities');
            $batch->columns = ['name', 'timezone'];
            $batch->data = Cities::createCityDummyData();
            $batch->insert();

            $batch = new BatchInsert('users');
            $batch->columns = ['email', 'password', 'lang', 'os', 'city_id'];
            $batch->data = Users::createDummyData();
            $batch->insert();


            $batch = new BatchInsert('coupons');
            $batch->columns = ['code', 'remain', 'expire_at'];
            $batch->data = Coupons::createDummyData();
            $batch->insert();


            $batch = new BatchInsert('weather_infos');
            $batch->columns = ['city_id', 'date', 'celsius'];
            $batch->data = Cities::createWeatherInfoDummyData();
            $batch->insert();
        } catch (Exception $e) {
            return $this->abort('Something went wrong');
        }
        return $this->response(null, 'Success');
    }

}


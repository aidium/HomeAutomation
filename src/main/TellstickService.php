<?php
require_once 'common.php';

class TellstickService
{
    function __construct() 
    {
        if (!isset($_SESSION['accessToken'])) 
        {
            die('We have no access token');
        }

        $this->consumer = new HTTP_OAuth_Consumer(constant('PUBLIC_KEY'), constant('PRIVATE_KEY'), $_SESSION['accessToken'], $_SESSION['accessTokenSecret']);
    }

    public function listSensors() 
    {
        $response = $this->consumer->sendRequest(constant('REQUEST_URI').'/sensors/list', array(), 'GET');
        return json_decode($response->getBody());
    }

    public function readSensor($id) 
    {
        $params = array(
            'id' => $id,
        );
        $response = $consumer->sendRequest(constant('REQUEST_URI').'/sensor/info', $params, 'GET');
        return json_decode($response->getBody());
    }

    public function listSwitches()
    {
        $response = $this->consumer->sendRequest(constant('REQUEST_URI').'/devices/list', array(), 'GET');
        return json_decode($response->getBody());
    }

    public function turnOnSwitch($id)
    {
        $params = array(
            'id' => $id,
        );
        $response = $this->consumer->sendRequest(constant('REQUEST_URI').'/device/turnOn', $params, 'POST');
        return json_decode($response->getBody())->status == 'success';
    }

    public function turnOffSwitch($id)
    {
        $params = array(
            'id' => $id,
        );
        $response = $this->consumer->sendRequest(constant('REQUEST_URI').'/switches/turnOff', $params, 'POST');
        return json_decode($response->getBody())->status == 'success';
    }
}

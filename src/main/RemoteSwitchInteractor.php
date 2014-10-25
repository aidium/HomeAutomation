<?php

class RemoteSwitchInteractor 
{
    public function __construct($service)
    {
        $this->status = array();
        $this->service = $service;
    }

    public function listSwitches()
    {
        return $this->service->listSwitches();
    }

    public function switchOn($id)
    {
        $this->service->turnOnSwitch($id);

        $this->status = array_merge($this->status, array($id => true));
    }

    public function switchOff($id)
    {
        $this->service->turnOffSwitch($id);

        $this->status = array_merge($this->status, array($id => false));
    }

    public function isOn($id)
    {
        return $this->status[$id];
    }
}
?>
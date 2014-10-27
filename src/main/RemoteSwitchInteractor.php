<?php

class RemoteSwitchInteractor 
{
    public function __construct($service, $repository)
    {
        $this->status = array();
        $this->service = $service;
        $this->repository = $repository;
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

    public function switchOnForMinutes($id, $minutes)
    {
        $this->switchOn($id);

        $this->repository->storeOffJob($id, $this->formatForCron(time() + $minutes * 60));
    }

    private function formatForCron($timestamp)
    {
        return date("i H d m Y", $timestamp);
    }

    public function switchOff($id)
    {
        $this->service->turnOffSwitch($id);

        $this->status = array_merge($this->status, array($id => false));

        $job = $this->repository->findJobOfTypeAndId('switchOff', $id);
        $this->repository->removeJob($job);
    }

    public function isOn($id)
    {
        return $this->status[$id];
    }

    public function hasSwitchOffJobFor($id)
    {
        return $this->repository->findJobOfTypeAndId('switchOff', $id) != null;
    }
}
?>
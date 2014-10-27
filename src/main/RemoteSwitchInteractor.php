<?php

class RemoteSwitchInteractor 
{
    public function __construct($tellstickService, $timeService, $repository)
    {
        $this->status = array();
        $this->tellstickService = $tellstickService;
        $this->timeService = $timeService;
        $this->repository = $repository;
    }

    public function listSwitches()
    {
        return $this->tellstickService->listSwitches();
    }

    public function switchOn($id)
    {
        $this->tellstickService->turnOnSwitch($id);

        $this->status = array_merge($this->status, array($id => true));
    }

    public function switchOnForMinutes($id, $minutes)
    {
        $this->switchOn($id);

        $this->repository->storeOffJob($id, $this->formatForCron($this->timeService->currentTime() + $minutes * 60));
    }

    private function formatForCron($timestamp)
    {
        return date("i H d m Y", $timestamp);
    }

    public function switchOff($id)
    {
        $this->tellstickService->turnOffSwitch($id);

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

    public function update()
    {
        $now = $this->timeService->currentTime();
        $this->switchOff('Switch1');
    }
}
?>
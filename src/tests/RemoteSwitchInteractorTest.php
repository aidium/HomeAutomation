<?php
include "src/main/RemoteSwitchInteractor.php";

class RemoteSwitchInteractorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @before
     */
    public function createInteractor()
    {
        $this->repositoryMock = $this->getMock('Repository', array('storeOffJob', 'storeOnJob', 'removeJob', 'storeBlockWarmerJob', 'findJobOfTypeAndId'));
        $this->serviceMock = $this->getMock('Service', array('listSensors', 'readSensor', 'listSwitches', 'turnOnSwitch', 'turnOffSwitch'));
        $this->interactor = new RemoteSwitchInteractor($this->serviceMock, $this->repositoryMock);
    }

    public function test_shouldBeAbleListAllSwitches() 
    {
        $this->serviceMock->method('listSwitches')
             ->willReturn(array('Switch1'));

        $arrayOfSwitches = $this->interactor->listSwitches();
        $this->assertContains("Switch1", $arrayOfSwitches);
    }

    public function test_shouldBeAbleToSwitchOnSwitch() 
    {
        $this->interactor->switchOn("Switch1");

        $this->assertTrue($this->interactor->isOn("Switch1"));
    }

    public function test_shouldBeAbleToSwitchOnMultipleSwitches() 
    {
        $this->serviceMock->expects($this->exactly(2))
                 ->method('turnOnSwitch')
                 ->withConsecutive(
                    $this->equalTo('Switch1'),
                    $this->equalTo('Switch2'));

        $this->interactor->switchOn("Switch1");
        $this->interactor->switchOn("Switch2");

        $this->assertTrue($this->interactor->isOn("Switch1"));
        $this->assertTrue($this->interactor->isOn("Switch2"));
    }

    public function test_givenAnSwitchOnSwitch_shouldBeAbleToTurnItOff()
    {
        $this->serviceMock->expects($this->once())
                 ->method('turnOffSwitch')
                 ->with($this->equalTo('Switch1'));

        $this->interactor->switchOn("Switch1");
        $this->interactor->switchOn("Switch2");

        $this->interactor->switchOff("Switch1");

        $this->assertFalse($this->interactor->isOn("Switch1"));
        $this->assertTrue($this->interactor->isOn("Switch2"));
    }

    public function test_switchingOnATimedSwitch_shouldCreateSwitchOffJob()
    {
        date_default_timezone_set('UTC');

        $this->serviceMock->expects($this->once())
                 ->method('turnOnSwitch')
                 ->with($this->equalTo('Switch1'));

        $this->repositoryMock->expects($this->once())
                 ->method('storeOffJob')
                 ->with(
                    $this->equalTo('Switch1'), 
                    $this->equalTo(date("i H d m Y", mktime(date('H'), date('i') + 30, 0, date('m'), date('d'), date('Y'))))
                    );

        $this->repositoryMock->method('findJobOfTypeAndId')
                 ->willReturn('OffSwitch1');


        $this->interactor->switchOnForMinutes("Switch1", 30);

        $this->assertTrue($this->interactor->isOn("Switch1"));
        $this->assertTrue($this->interactor->hasSwitchOffJobFor("Switch1"));
    }

    public function test_givenAnTimedSwitch_shouldCansleJobWhenSwitchedOf()
    {
        $this->repositoryMock->expects($this->once())
                 ->method('removeJob')
                 ->with($this->equalTo('OffSwitch1'));

        $this->repositoryMock->method('findJobOfTypeAndId')
             ->will($this->onConsecutiveCalls('OffSwitch1', null));

        $this->interactor->switchOnForMinutes("Switch1", 30);

        $this->interactor->switchOff("Switch1");

        $this->assertFalse($this->interactor->isOn("Switch1"));
        $this->assertFalse($this->interactor->hasSwitchOffJobFor("Switch1"));
    }
}
?>
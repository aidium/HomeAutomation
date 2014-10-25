<?php
include "src/main/RemoteSwitchInteractor.php";

class RemoteSwitchInteractorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @before
     */
    public function createInteractor()
    {
        $this->serviceMock = $this->getMock('Service', array('listSensors', 'readSensor', 'listSwitches', 'turnOnSwitch', 'turnOffSwitch'));
        $this->interactor = new RemoteSwitchInteractor($this->serviceMock);
        $this->assertNotNull($this->interactor);
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
}
?>
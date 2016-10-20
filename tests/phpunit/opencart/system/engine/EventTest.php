<?php

class EventTest extends OpenCartTest {
	public function testEventOrderedExecution() {
		$eventMock = $this->getMockBuilder('Event')
			->setMethods(array('createAction'))
			->disableOriginalConstructor()
			->getMock();

		$actionMock = $this->getMockBuilder('Action')
			->disableOriginalConstructor()
			->getMock();
			
		$actionMock->expects($this->exactly(3))
			->method('execute');

		$eventMock->expects($this->at(0))
			->method('createAction')
			->with($this->equalTo('SomeExtraAction'), $this->equalTo(array()))
			->will($this->returnValue($actionMock));

		$eventMock->expects($this->at(1))
			->method('createAction')
			->with($this->equalTo('SomeAction'), $this->equalTo(array()))
			->will($this->returnValue($actionMock));

		$eventMock->expects($this->at(2))
			->method('createAction')
			->with($this->equalTo('SomeAnotherAction'), $this->equalTo(array()))
			->will($this->returnValue($actionMock));

		$eventMock->register('some.event', 'SomeAction', 10);
		$eventMock->register('some.event', 'SomeAnotherAction', 1);
		$eventMock->register('some.event', 'SomeExtraAction', 100);

		$eventMock->trigger('some.event');
	}
}
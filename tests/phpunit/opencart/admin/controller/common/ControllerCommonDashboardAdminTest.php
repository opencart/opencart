<?php

class ControllerCommonDashboardAdminTest extends OpenCartTest
{
    public function testShowDashboardWithLoggedInUser() {

        $this->login('admin','admin');

        $response = $this->dispatchAction('common/dashboard');
        $this->assertRegExp('/Total Sales/', $response->getOutput());

        $this->logout();

    }
}

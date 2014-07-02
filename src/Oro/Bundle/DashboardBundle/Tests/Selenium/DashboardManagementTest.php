<?php

namespace Oro\Bundle\DashboardBundle\Tests\Selenium;

use Oro\Bundle\TestFrameworkBundle\Test\Selenium2TestCase;
use Oro\Bundle\DashboardBundle\Tests\Selenium\Pages\Dashboards;

/**
 * Class DashboardsTest
 *
 * @package Oro\Bundle\DashboardBundle\Tests\Selenium
 */
class DashboardsManagementTest extends Selenium2TestCase
{
    /**
     * @return string
     */
    public function testCreate()
    {
        $dashboardName = 'Dashboard_Management' . mt_rand();
        $login = $this->login();
        /** @var Dashboards $login */
        $login->openDashboards('Oro\Bundle\DashboardBundle')
            ->add()
            ->setLabel($dashboardName)
            ->setClone('Blank Dashboard')
            ->save()
            ->assertMessage('Dashboard saved')
            ->assertTitle("{$dashboardName} - Manage dashboards - Dashboards");

        return $dashboardName;
    }

    /**
     * @depends testCreate
     * @param $dashboardName
     */
    public function testView($dashboardName)
    {
        $login = $this->login();
        /** @var Dashboards $login */
        $login = $login->openDashboards('Oro\Bundle\DashboardBundle')
            ->filterBy('Label', $dashboardName)
            ->action(array($dashboardName), 'View')
            ->assertTitle("{$dashboardName} - Manage dashboards - Dashboards");

        $this->assertTrue($login->isEmpty());
        $login = $login->addWidget('Recent Emails');
        $this->assertTrue($login->widgetExists(array('Recent Emails')));
        $login = $login->removeWidget('Recent Emails');
        $this->assertFalse($login->widgetExists(array('Recent Emails')));
    }
}

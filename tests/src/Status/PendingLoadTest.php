<?php namespace Ctimt\SqlControl\Status;

use Ctimt\SqlControl\Status\PendingLoad;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-12 at 15:08:21.
 */
class PendingLoadTest extends \CtimtTest\SqlControl\Mocks\TestStatus
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->configure(new PendingLoad(),'Pending Load',false,true);
    }

}
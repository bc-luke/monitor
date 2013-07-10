<?php

namespace Monitor;

class CallbackInvokingDiskMonitorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $mockLogger;

    public function setUp()
    {
        $this->mockLogger = $this->getMock('\Psr\Log\LoggerInterface');
    }

    /**
     * Tests that a warning is logged when available disk space falls below a given threshold.
     *
     * Here, the default value of the callback has been replaced with a closure that simply returns the value
     * <code>500</code>. This allows for testing of the case where a small amount of disk space is available without
     * really reducing the available disk space.
     */
    public function testWarningLoggedWhenAvailableSpaceBelowThreshold()
    {
        $this->mockLogger->expects($this->once())->method('warning');
        $diskMonitor = new CallbackInvokingDiskMonitor($this->mockLogger, 1000, '/');
        $diskMonitor->setDiskFreeSpaceCallback(function () {
           return 500;
        });
        $diskMonitor->run();
    }

    /**
     * Tests that an error is logged when available disk space cannot be determined.
     *
     * Here, the behaviour of the callback method {@link FunctionWrappingDiskMonitor::$diskFreeSpaceCallback} has been
     * controlled such that it will return the value <code>false</code>. This allows for testing of the case where the
     * method {@link disk_free_space()} fails.
     */
    public function testErrorLoggedWhenCheckingDiskSpaceProducesError()
    {
        $this->mockLogger->expects($this->once())->method('error');

        $diskMonitor = new CallbackInvokingDiskMonitor($this->mockLogger, 1000, '/');
        $diskMonitor->setDiskFreeSpaceCallback(function () {
            return false;
        });
        $diskMonitor->run();
    }
}
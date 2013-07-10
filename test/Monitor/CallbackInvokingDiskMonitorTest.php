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
        $diskMonitor = new CallbackInvokingDiskMonitor($this->mockLogger, 1000, '/', function () {
           return 500;
        });

        $diskMonitor->run();
    }

    /**
     * Tests that an error is logged when available disk space cannot be determined.
     *
     * Here, the default value of the callback has been replaced with a closure that simply returns the value
     * <code>false</code>. This allows for testing of the case where the method {@link disk_free_space()} fails.
     */
    public function testErrorLoggedWhenCheckingDiskSpaceProducesError()
    {
        $this->mockLogger->expects($this->once())->method('error');

        $diskMonitor = new CallbackInvokingDiskMonitor($this->mockLogger, 1000, '/', function () {
            return false;
        });

        $diskMonitor->run();
    }

    /**
     * Tests that an informational message is logged when available disk space is within the threshold.
     *
     * Here, the default value of the callback has been replaced with a closure that simply returns the value
     * <code>1500</code>. This allows for testing of the case where an acceptable amount of disk space is available.
     */
    public function testInfoMessageLoggedWhenDiskSpaceWithinThreshold()
    {
        $this->mockLogger->expects($this->once())->method('info');

        $diskMonitor = new CallbackInvokingDiskMonitor($this->mockLogger, 1000, '/', function () {
            return 1500;
        });

        $diskMonitor->run();
    }
}
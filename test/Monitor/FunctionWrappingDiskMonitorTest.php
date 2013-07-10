<?php

namespace Monitor;

class FunctionWrappingDiskMonitorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * A subclass of the class under test, instantiated by the {@link setUp()} method.
     *
     * Its implementation of the wrapper function {@link FunctionWrappingDiskMonitor::diskFreeSpace()} has been mocked
     * such that the global function {@link disk_free_space()} may be isolated and controlled.
     *
     * @var \Monitor\FunctionWrappingDiskMonitor
     */
    private $diskMonitor;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $mockLogger;

    /**
     * Because {@link disk_free_space()} has been wrapped in a member function of the class under test,
     * {@link FunctionWrappingDiskMonitor}, its behaviour can be controlled by mocking the wrapper method, and
     * expectations of it may be set in tests.
     */
    public function setUp()
    {
        $this->mockLogger = $this->getMock('\Psr\Log\LoggerInterface');
        $this->diskMonitor = $this->getMock('\Monitor\FunctionWrappingDiskMonitor', array('diskFreeSpace'),
            array($this->mockLogger, 1000, '/'));
    }

    /**
     * Tests that a warning is logged when available disk space falls below a given threshold.
     *
     * Here, the behaviour of the wrapper method {@link FunctionWrappingDiskMonitor::diskFreeSpace()} has been
     * controlled such that it will return the value <code>500</code>. This allows for testing of the case where a small
     * amount of disk space is available without really reducing the available disk space.
     */
    public function testWarningLoggedWhenAvailableSpaceBelowThreshold()
    {
        $this->mockLogger->expects($this->once())->method('warning');

        $this->diskMonitor->expects($this->once())
            ->method('diskFreeSpace')
            ->with('/')
            ->will($this->returnValue(500));

        $this->diskMonitor->run();
    }

    /**
     * Tests that an error is logged when available disk space cannot be determined.
     *
     * Here, the behaviour of the wrapper method {@link FunctionWrappingDiskMonitor::diskFreeSpace()} has been
     * controlled such that it will return the value <code>false</code>. This allows for testing of the case where the
     * function {@link disk_free_space()} fails.
     */
    public function testErrorLoggedWhenCheckingDiskSpaceProducesError()
    {
        $this->mockLogger->expects($this->once())->method('error');

        $this->diskMonitor->expects($this->once())
            ->method('diskFreeSpace')
            ->with('/')
            ->will($this->returnValue(false));

        $this->diskMonitor->run();

    }

    /**
     * Tests that an informational message is logged when available disk space is within the threshold.
     *
     * Here, the behaviour of the wrapper method {@link FunctionWrappingDiskMonitor::diskFreeSpace()} has been
     * controlled such that it will return the value <code>1500</code>. This allows for testing of the case where an
     * acceptable amount of disk space is available.
     */
    public function testInfoMessageLoggedWhenDiskSpaceWithinThreshold()
    {
        $this->mockLogger->expects($this->once())->method('info');

        $this->diskMonitor->expects($this->once())
            ->method('diskFreeSpace')
            ->with('/')
            ->will($this->returnValue(1500));

        $this->diskMonitor->run();
    }
}
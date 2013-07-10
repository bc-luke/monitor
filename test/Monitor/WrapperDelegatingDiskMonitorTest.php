<?php

namespace Monitor;

class WrapperDelegatingDiskMonitorTest extends \PHPUnit_Framework_TestCase
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
     * Here, the behaviour of the wrapper method {@link \Util\FilesystemUtil::diskFreeSpace()} has been controlled such
     * that it will return the value <code>500</code>. This allows for testing of the case where a small amount of disk
     * space is available without really reducing the available disk space.
     */
    public function testWarningLoggedWhenAvailableSpaceBelowThreshold()
    {
        $this->mockLogger->expects($this->once())->method('warning');

        $mockFilesystemUtilClass = $this->getMockClass('\Util\FilesystemUtil', array('diskFreeSpace'));
        $mockFilesystemUtilClass::staticExpects($this->once())
            ->method('diskFreeSpace')
            ->with('/')
            ->will($this->returnValue(500));

        $diskMonitor = new WrapperDelegatingDiskMonitor($this->mockLogger, 1000, '/');
        $diskMonitor->setFilesystemUtilClass($mockFilesystemUtilClass);

        $diskMonitor->run();

    }

    /**
     * Tests that an error is logged when available disk space cannot be determined.
     *
     * Here, the behaviour of the wrapper method {@link \Util\FilesystemUtil::diskFreeSpace()} has been controlled such
     * that it will return the value <code>false</code>. This allows for testing of the case where the function
     * {@link disk_free_space()} fails.
     */
    public function testErrorLoggedWhenCheckingDiskSpaceProducesError()
    {
        $this->mockLogger->expects($this->once())->method('error');

        $mockFilesystemUtilClass = $this->getMockClass('\Util\FilesystemUtil', array('diskFreeSpace'));
        $mockFilesystemUtilClass::staticExpects($this->once())
            ->method('diskFreeSpace')
            ->with('/')
            ->will($this->returnValue(false));

        $diskMonitor = new WrapperDelegatingDiskMonitor($this->mockLogger, 1000, '/');
        $diskMonitor->setFilesystemUtilClass($mockFilesystemUtilClass);

        $diskMonitor->run();

    }

    /**
     * Tests that an informational message is logged when available disk space is within the threshold.
     *
     * Here, the behaviour of the wrapper method {@link \Util\FilesystemUtil::diskFreeSpace()} has been controlled such
     * that it will return the value <code>1500</code>. This allows for testing of the case where the function
     * {@link disk_free_space()} fails.
     */
    public function testInfoMessageLoggedWhenDiskSpaceWithinThreshold()
    {
        $this->mockLogger->expects($this->once())->method('info');

        $mockFilesystemUtilClass = $this->getMockClass('\Util\FilesystemUtil', array('diskFreeSpace'));
        $mockFilesystemUtilClass::staticExpects($this->once())
            ->method('diskFreeSpace')
            ->with('/')
            ->will($this->returnValue(1500));

        $diskMonitor = new WrapperDelegatingDiskMonitor($this->mockLogger, 1000, '/');
        $diskMonitor->setFilesystemUtilClass($mockFilesystemUtilClass);

        $diskMonitor->run();
    }

}
<?php

namespace Monitor;

use Psr\Log\LoggerInterface;

/**
 * This disk monitor isolates its use {@link disk_free_space()} by invoking it via a <code>callable</code> variable.
 *
 * @package Monitor
 */
class CallbackInvokingDiskMonitor extends AbstractDiskMonitor
{

    /**
     * An implementation of {@link disk_free_space()}.
     *
     * @var callable
     */
    protected $diskFreeSpaceCallback;

    /**
     * @param LoggerInterface $logger
     * @param $threshold
     * @param string $path
     * @param callable|null $diskFreeSpaceCallback
     */
    public function __construct(LoggerInterface $logger, $threshold, $path = '/', $diskFreeSpaceCallback = null)
    {
        parent::__construct($logger, $threshold, $path);
        if (is_null($diskFreeSpaceCallback)) {
            $this->diskFreeSpaceCallback = function ($directory) {
                return \disk_free_space($directory);
            };
        }
    }

    /**
     * Runs the disk monitor.
     */
    public function run()
    {
        $result = call_user_func($this->diskFreeSpaceCallback, $this->directory);
        if ($result === false) {
            $this->logger->error("Could not determine free disk space for path {$this->directory}");
        } else if ($result < $this->threshold) {
            $this->logger->warning("{$this->directory} is almost out of space.");
        }
    }

    /**
     * Sets an implementation of {@link disk_free_space()}.
     *
     * @param callable $diskFreeSpaceCallback An implementation of {@link disk_free_space()}.
     */
    public function setDiskFreeSpaceCallback($diskFreeSpaceCallback)
    {
        $this->diskFreeSpaceCallback = $diskFreeSpaceCallback;
    }


}
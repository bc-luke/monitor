<?php

namespace Monitor;

/**
 * This disk monitor isolates its use of {@link disk_free_space()} by invoking it using a variable function.
 *
 * @link http://php.net/manual/en/functions.variable-functions.php Variable functions
 * @package Monitor
 */
class VariableFunctionInvokingDiskMonitor extends AbstractDiskMonitor
{

    /**
     * The name of a function that provides the available disk space for a given directory in bytes.
     *
     * @var string
     */
    protected $diskFreeSpaceFunction = 'disk_free_space';

    /**
     * Runs the disk monitor.
     */
    public function run()
    {
        $diskFreeSpaceFunction = $this->diskFreeSpaceFunction;
        $result = $diskFreeSpaceFunction($this->directory);
        if ($result === false) {
            $this->logger->error("Could not determine free disk space for path {$this->directory}");
        } else if ($result < $this->threshold) {
            $this->logger->warning("{$this->directory} is almost out of space.");
        }
    }

    /**
     * Sets the name of a function that provides the available disk space for a given directory in bytes.
     *
     * @param callable $diskFreeSpaceFunction The name of a function that provides the available disk space for a given
     * directory in bytes.
     * @return $this
     */
    public function setDiskFreeSpaceFunction($diskFreeSpaceFunction)
    {
        $this->diskFreeSpaceFunction = $diskFreeSpaceFunction;
        return $this;
    }
}
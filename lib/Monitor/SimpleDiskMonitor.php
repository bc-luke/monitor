<?php

namespace Monitor;

/**
 * This disk monitor calls {@link disk_free_space()} directly, and is therefore not easily tested.
 *
 * @package Monitor
 */
class SimpleDiskMonitor extends AbstractDiskMonitor
{

    /**
     * Runs the disk monitor.
     */
    public function run()
    {
        $result = disk_free_space($this->directory); // Calls the global function directly.
        if ($result === false) {
            $this->logger->error("Could not determine free disk space for path {$this->directory}");
        } else if ($result < $this->threshold) {
            $this->logger->warning("{$this->directory} is almost out of space.");
        }
    }
}
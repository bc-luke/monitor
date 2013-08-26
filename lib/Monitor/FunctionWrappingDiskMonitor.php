<?php

namespace Monitor;

/**
 * This disk monitor isolates its use of {@link disk_free_space()} by wrapping it in a protected member function.
 *
 * @package Monitor
 */
class FunctionWrappingDiskMonitor extends AbstractDiskMonitor
{

    /**
     * Runs the disk monitor.
     */
    public function run()
    {
        $result = $this->diskFreeSpace($this->directory);
        if ($result === false) {
            $this->logger->error("Could not determine free disk space for directory {$this->directory}");
        } else if ($result < $this->threshold) {
            $this->logger->warning("{$this->directory} is almost out of space.");
        } else {
            $this->logger->info("Checked free space for path {$this->directory}; everything is fine.");
        }
    }

    /**
     * Wraps the procedural function {@link disk_free_space()}.
     *
     * @param string $directory A directory of the filesystem or disk partition.
     * @return float|bool The number of available bytes as a <code>float</code> or <code>false</code> on failure.
     */
    protected function diskFreeSpace($directory)
    {
        return \disk_free_space($directory);
    }
}
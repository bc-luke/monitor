<?php

namespace Monitor;

/**
 * This disk monitor isolates its use of {@link disk_free_space()} by calling upon a wrapper class
 * {@link \Util\FilesystemUtil}.
 *
 * @package Monitor
 */
class WrapperDelegatingDiskMonitor extends AbstractDiskMonitor {

    /**
     * The name of the class that provides filesystem-related functions.
     *
     * @var string
     */
    protected $filesystemUtilClass = '\Util\FilesystemUtil';

    /**
     * Runs the disk monitor.
     */
    public function run()
    {
        $filesystemUtilClass = $this->filesystemUtilClass;

        $result = $filesystemUtilClass::diskFreeSpace($this->directory);
        if ($result === false) {
            $this->logger->error("Could not determine free disk space for path {$this->directory}");
        } else if ($result < $this->threshold) {
            $this->logger->warning("{$this->directory} is almost out of space.");
        } else {
            $this->logger->info("Checked free space for path {$this->directory}; everything is fine.");
        }
    }

    /**
     * Sets the name of the class that provides filesystem-related functions.
     *
     * @param string $filesystemUtilClass A class that wraps the procedural function {@link disk_free_space()}.
     * @return $this
     */
    public function setFilesystemUtilClass($filesystemUtilClass)
    {
        $this->filesystemUtilClass = $filesystemUtilClass;
        return $this;
    }
}
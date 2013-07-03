<?php

namespace Monitor;

/**
 * An interface that monitors should implement so that they may be run by a scheduler.
 *
 * @package Monitor
 */
interface Monitor {

    /**
     * Runs the monitor.
     */
    public function run();
}
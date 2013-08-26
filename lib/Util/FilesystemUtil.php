<?php

namespace Util;

/**
 * A collection of functions wrapping filesystem-related procedural functions.
 *
 * The functions provided within may be used to break direct dependencies on the functions they wrap, thereby creating
 * seams which can serve to isolate the dependencies of a class under test.
 *
 * @package Util
 */
class FilesystemUtil
{

    /**
     * Wraps the procedural function {@link disk_free_space()}.
     *
     * @param string $directory A directory of the filesystem or disk partition.
     * @return float|bool The number of available bytes as a <code>float</code> or <code>false</code> on failure.
     */
    public static function diskFreeSpace($directory)
    {
        return \disk_free_space($directory);
    }

}
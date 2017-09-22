<?php
/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * See the GNU Lesser General Public License for more details.
 */

namespace enzolarosa;

use enzolarosa\BaseTask as AbstractTask;

class MultipleThread
{

    protected $_activeThreads = array();

    protected $maxThreads = 5;

    public function __construct($maxThreads = 5)
    {
        $this->maxThreads = $maxThreads;
    }

    public function start(AbstractTask $task)
    {
        $pid = pcntl_fork();
        if ($pid == -1) {
            throw new \Exception('[Pid:' . getmypid() . '] Could not fork process');
        } // Parent thread
        elseif ($pid) {
            $this->_activeThreads[$pid] = true;

            // Reached maximum number of threads allowed
            if ($this->maxThreads == count($this->_activeThreads)) {
                // Parent Process : Checking all children have ended (to avoid zombie / defunct threads)
                while (!empty($this->_activeThreads)) {
                    $endedPid = pcntl_wait($status);
                    if (-1 == $endedPid) {
                        $this->_activeThreads = array();
                    }
                    unset($this->_activeThreads[$endedPid]);
                }
            }
        } else {
            $task->initialize();

            // On success
            if ($task->process()) {
                $task->onSuccess();
            } else {
                $task->onFailure();
            }

            posix_kill(getmypid(), 9);
        }
        pcntl_wait($status, WNOHANG);
    }

}

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

class ExampleTask extends BaseTask
{

    public function initialize()
    {
        return true;
    }

    public function onSuccess()
    {
        return true;
    }

    public function onFailure()
    {
        return false;
    }

    public function process(array $params = array())
    {
        sleep(1);
        echo '[Pid:' . getmypid() . '] Task executed at ' . date('Y-m-d H:i:s') . PHP_EOL;
        return true;
    }
}

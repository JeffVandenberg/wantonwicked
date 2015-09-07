<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/7/2015
 * Time: 12:02 AM
 */

namespace classes\character\sheet;


class Power
{
    var $power_name;
    var $power_note;
    var $power_level;
    var $power_id;

    function getPowerName()
    {
        return $this->power_name;
    }

    function setPowerName($power_name)
    {
        $this->power_name = $power_name;
    }

    function getPowerNote()
    {
        return $this->power_note;
    }

    function setPowerNote($power_note)
    {
        $this->power_note = $power_note;
    }

    function getPowerLevel()
    {
        return $this->power_level;
    }

    function setPowerLevel($power_level)
    {
        $this->power_level = $power_level;
    }

    function getPowerID()
    {
        return $this->power_id;
    }

    function setPowerID($power_id)
    {
        $this->power_id = $power_id;
    }
}
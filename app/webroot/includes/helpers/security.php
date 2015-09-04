<?php
use classes\core\helpers\UserdataHelper;

class Security
{
	public static function HasAnySTPermissions()
	{
		return UserdataHelper::IsSt($userdata);
	}
}
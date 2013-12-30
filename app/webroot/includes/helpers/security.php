<?php
class Security
{
	public static function HasAnySTPermissions()
	{
		return ((!$userdata['is_asst']) && (!$userdata['is_gm']) && (!$userdata['is_head']) && (!$userdata['is_admin']));
	}
}
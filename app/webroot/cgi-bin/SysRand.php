<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/19/14
 * Time: 12:37 PM
 */
function SysRand($min, $max)
{
    $bytes = mcrypt_create_iv(4, MCRYPT_DEV_URANDOM);
    if ($bytes === false || strlen($bytes) != 4) {
        throw new RuntimeException("Unable to get 4 bytes");
    }
    $ary = unpack("Nint", $bytes);
    $val = $ary['int'] & 0x7FFFFFFF; // 32-bit safe
    $fp  = (float)$val / 2147483647.0; // convert to [0,1]
    return (int) round($fp * $max) + $min;
}

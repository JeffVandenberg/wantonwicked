<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/13/14
 * Time: 9:38 PM
 */

$response = array(
    'status' => false,
    'message' => 'Ghost Disabled'
);
header('content-type: application/json');
echo json_encode($response);
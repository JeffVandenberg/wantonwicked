<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 9/7/2016
 * Time: 5:44 PM
 */


use Cake\ORM\TableRegistry;

$characters = TableRegistry::get('Characters');
$character = $characters->get(1);
var_dump($character);

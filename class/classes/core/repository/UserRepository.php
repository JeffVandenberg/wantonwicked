<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/11/2015
 * Time: 10:51 PM
 */

namespace classes\core\repository;


class UserRepository extends AbstractRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('classes\core\data\User');
    }
}
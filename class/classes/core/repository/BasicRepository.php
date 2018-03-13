<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 8/21/13
 * Time: 3:45 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\repository;


use classes\core\repository\AbstractRepository;

class BasicRepository extends AbstractRepository 
{
    public function __construct($className)
    {
        parent::__construct($className);
    }
}

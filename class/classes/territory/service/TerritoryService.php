<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/2/2017
 * Time: 9:03 AM
 */

namespace classes\territory\service;


use classes\core\repository\RepositoryManager;
use classes\territory\repository\TerritoryRepository;

/**
 * Class TerritoryService
 * @package classes\territory\service
 */
class TerritoryService
{
    /**
     * @var TerritoryRepository
     */
    public $repo;

    /**
     * TerritoryService constructor.
     */
    public function __construct()
    {
        $this->repo = RepositoryManager::GetRepository('classes\territory\data\Territory');
    }

    /**
     * @return mixed
     */
    public function listTerritoriesWithPopulation()
    {
        return $this->repo->listTerritoriesWithPopulation();
    }
}

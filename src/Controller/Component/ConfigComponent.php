<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/20/14
 * Time: 10:54 PM
 */
namespace App\Controller\Component;

use App\Model\Table\ConfigurationsTable;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;


class ConfigComponent extends Component
{
    public function Read($key) {
        $configuration = TableRegistry::get('Configurations');
        /* @var ConfigurationsTable $configuration */

        $result = $configuration->find()->select(['value'])->where(['Configurations.key' => $key])->toArray();
        if(count($result)) {
            return $result[0]->value;
        }
        return null;
    }
} 

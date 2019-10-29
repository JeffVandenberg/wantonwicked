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
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Exception;

class ConfigComponent extends Component
{
    /**
     * @param string $key Key to read
     * @return string|null
     */
    public function read($key)
    {
        $configuration = TableRegistry::getTableLocator()->get('Configurations');
        /* @var ConfigurationsTable $configuration */

        $result = $configuration->find()->select(['value'])->where(['Configurations.key' => $key])->toArray();
        if (count($result)) {
            return $result[0]->value;
        }

        return null;
    }

    /**
     * @param string $key Key to read
     * @return string|int
     * @throws Exception
     */
    public function readGlobal(string $key)
    {
        $key = strtolower($key);

        switch ($key) {
            case 'city':
                if ($this->getController()->getRequest()->getQuery('city')) {
                    return $this->getController()->getRequest()->getQuery('city');
                } elseif ($this->getController()->getRequest()->getCookie('city')) {
                    return $this->getController()->getRequest()->getCookie('city');
                } else {
                    return Configure::read('City.location');
                }
        }
        throw new Exception('Config is unable to handle: ' . $key);
    }
}

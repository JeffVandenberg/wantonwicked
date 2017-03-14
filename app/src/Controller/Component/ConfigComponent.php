<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/20/14
 * Time: 10:54 PM
 */namespace app\Controller\Component;

use Cake\Controller\Component;


class ConfigComponent extends Component
{
    public function Read($key) {
        use App\Model\Configuration;
        $configuration = new Configuration();

        $config = $configuration->find('first', array(
            'conditions' => array(
                'Configuration.key' => $key
            )
        ));
        return $config['Configuration']['value'];
    }
} 
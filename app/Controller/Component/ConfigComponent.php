<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/20/14
 * Time: 10:54 PM
 */

class ConfigComponent extends Component
{
    public function Read($key) {
        App::uses('Configuration', 'Model');
        $configuration = new Configuration();

        $config = $configuration->find('first', array(
            'conditions' => array(
                'Configuration.key' => $key
            )
        ));
        return $config['Configuration']['value'];
    }
} 
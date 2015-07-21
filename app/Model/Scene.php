<?php
App::uses('AppModel', 'Model');

/**
 * Scene Model
 *
 * @property User $RunBy
 * @property User $CreatedBy
 * @property User $UpdatedBy
 * @property SceneCharacter $SceneCharacter
 * @property SceneRequest $SceneRequest
 */
class Scene extends AppModel
{

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name'          => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'created_by_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'updated_by_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'RunBy'     => array(
            'className'  => 'User',
            'foreignKey' => 'run_by_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => ''
        ),
        'CreatedBy' => array(
            'className'  => 'User',
            'foreignKey' => 'created_by_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => ''
        ),
        'UpdatedBy' => array(
            'className'  => 'User',
            'foreignKey' => 'updated_by_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => ''
        ),
        'SceneStatus' => array(
            'className'  => 'SceneStatus',
            'foreignKey' => 'scene_status_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => ''
        ),
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'SceneCharacter' => array(
            'className'    => 'SceneCharacter',
            'foreignKey'   => 'scene_id',
            'dependent'    => false,
            'conditions'   => '',
            'fields'       => '',
            'order'        => '',
            'limit'        => '',
            'offset'       => '',
            'exclusive'    => '',
            'finderQuery'  => '',
            'counterQuery' => ''
        ),
        'SceneRequest'   => array(
            'className'    => 'SceneRequest',
            'foreignKey'   => 'scene_id',
            'dependent'    => false,
            'conditions'   => '',
            'fields'       => '',
            'order'        => '',
            'limit'        => '',
            'offset'       => '',
            'exclusive'    => '',
            'finderQuery'  => '',
            'counterQuery' => ''
        )
    );

    public function save(&$model)
    {
        if($model['Scene']['slug'] == '') {
            $slug = strtolower(Inflector::slug($model['Scene']['name']));

            $slugCount = $this->find('count', array(
                'conditions' => array(
                    'Scene.slug like ' => $slug .'%'
                )
            ));

            if($slugCount > 0) {
                $slug .= $slugCount;
            }

            $model['Scene']['slug'] = $slug;
        }

        return parent::save($model);
    }
}
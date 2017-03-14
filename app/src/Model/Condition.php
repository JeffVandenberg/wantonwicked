<?php
namespace app\Model;

App::uses('AppModel', 'Model');

/**
 * Condition Model
 *
 */
class Condition extends AppModel
{
    public function findCondition($slug, $findType = 'first', $options = null)
    {
        $searchOptions = [
            'conditions' => [
                'Condition.slug' => $slug
            ],
            'contain' => false
        ];

        if(is_array($options)) {
            $searchOptions = array_merge($searchOptions, $options);
        }
        return $this->find($findType, $searchOptions);
    }

    public function saveCondition($model)
    {
        if ($model['Condition']['slug'] == '') {
            $slug = strtolower(Inflector::slug($model['Condition']['name']));

            $slugCount = $this->find('count', array(
                'conditions' => array(
                    'Condition.slug like ' => $slug . '%'
                )
            ));

            if ($slugCount > 0) {
                $slug .= $slugCount;
            }

            $model['Condition']['slug'] = $slug;
        }

        return parent::save($model);
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = [
        'name' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Conditions must have a name',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'source' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Please provided a source',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'is_persistent' => [
            'boolean' => [
                'rule' => ['boolean'],
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'description' => [
            'notBlank' => [
                'rule' => ['notBlank'],
                'message' => 'Please provide a description',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'created_by' => [
            'numeric' => [
                'rule' => ['numeric'],
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
        'updated_by' => [
            'numeric' => [
                'rule' => ['numeric'],
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ],
        ],
    ];

    public $belongsTo = [
        'CreatedBy' => [
            'className' => 'User',
            'foreignKey' => 'created_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ],
        'UpdatedBy' => [
            'className' => 'User',
            'foreignKey' => 'updated_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ],
        'ConditionType' => [
            'className' => 'ConditionType',
            'foreignKey' => 'condition_type_id'
        ]
    ];
}

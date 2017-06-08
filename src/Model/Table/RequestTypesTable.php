<?php
namespace App\Model\Table;

use App\Model\Entity\RequestType;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestTypes Model
 *
 * @property \Cake\ORM\Association\HasMany $Requests
 * @property \Cake\ORM\Association\BelongsToMany $Groups
 *
 * @method RequestType get($primaryKey, $options = [])
 * @method RequestType newEntity($data = null, array $options = [])
 * @method RequestType[] newEntities(array $data, array $options = [])
 * @method RequestType|bool save(EntityInterface $entity, $options = [])
 * @method RequestType patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method RequestType[] patchEntities($entities, array $data, array $options = [])
 * @method RequestType findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestTypesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('request_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Requests', [
            'foreignKey' => 'request_type_id'
        ]);
        $this->belongsToMany('Groups', [
            'foreignKey' => 'request_type_id',
            'targetForeignKey' => 'group_id',
            'joinTable' => 'groups_request_types'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }
}

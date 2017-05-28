<?php
namespace App\Model\Table;

use App\Model\Entity\CharacterStatus;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CharacterStatuses Model
 *
 * @property \Cake\ORM\Association\HasMany $Characters
 *
 * @method CharacterStatus get($primaryKey, $options = [])
 * @method CharacterStatus newEntity($data = null, array $options = [])
 * @method CharacterStatus[] newEntities(array $data, array $options = [])
 * @method CharacterStatus|bool save(EntityInterface $entity, $options = [])
 * @method CharacterStatus patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method CharacterStatus[] patchEntities($entities, array $data, array $options = [])
 * @method CharacterStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class CharacterStatusesTable extends Table
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

        $this->setTable('character_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Characters', [
            'foreignKey' => 'character_status_id'
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

        $validator
            ->integer('sort_order')
            ->requirePresence('sort_order', 'create')
            ->notEmpty('sort_order');

        return $validator;
    }
}

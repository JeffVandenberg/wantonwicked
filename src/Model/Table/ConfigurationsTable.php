<?php
namespace App\Model\Table;

use App\Model\Entity\Configuration;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Configurations Model
 *
 * @method Configuration get($primaryKey, $options = [])
 * @method Configuration newEntity($data = null, array $options = [])
 * @method Configuration[] newEntities(array $data, array $options = [])
 * @method Configuration|bool save(EntityInterface $entity, $options = [])
 * @method Configuration patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Configuration[] patchEntities($entities, array $data, array $options = [])
 * @method Configuration findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfigurationsTable extends Table
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

        $this->setTable('configurations');
        $this->setDisplayField('key');
        $this->setPrimaryKey('key');
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
            ->allowEmpty('key', 'create');

        $validator
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('data_type', 'create')
            ->notEmpty('data_type');

        return $validator;
    }
}

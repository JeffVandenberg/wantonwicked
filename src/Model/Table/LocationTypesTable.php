<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LocationTypes Model
 *
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\HasMany $Locations
 *
 * @method \App\Model\Entity\LocationType get($primaryKey, $options = [])
 * @method \App\Model\Entity\LocationType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LocationType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LocationType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LocationType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LocationType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LocationType findOrCreate($search, callable $callback = null, $options = [])
 */
class LocationTypesTable extends Table
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

        $this->setTable('location_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Muffin/Slug.Slug', [
            'onUpdate' => true
        ]);

        $this->hasMany('Locations', [
            'foreignKey' => 'location_type_id'
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
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('icon', 'create')
            ->notEmpty('icon');

        return $validator;
    }
}

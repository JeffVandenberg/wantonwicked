<?php
namespace App\Model\Table;

use App\Model\Entity\Roll;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WodDierolls Model
 *
 * @method Roll get($primaryKey, $options = [])
 * @method Roll newEntity($data = null, array $options = [])
 * @method Roll[] newEntities(array $data, array $options = [])
 * @method Roll|bool save(EntityInterface $entity, $options = [])
 * @method Roll patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Roll[] patchEntities($entities, array $data, array $options = [])
 * @method Roll findOrCreate($search, callable $callback = null, $options = [])
 */
class RollsTable extends Table
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

        $this->setTable('wod_dierolls');
        $this->setDisplayField('Description');
        $this->setPrimaryKey('Roll_ID');
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
            ->integer('Roll_ID')
            ->allowEmpty('Roll_ID', 'create');

        $validator
            ->integer('Character_ID')
            ->requirePresence('Character_ID', 'create')
            ->notEmpty('Character_ID');

        $validator
            ->dateTime('Roll_Date')
            ->requirePresence('Roll_Date', 'create')
            ->notEmpty('Roll_Date');

        $validator
            ->requirePresence('Character_Name', 'create')
            ->notEmpty('Character_Name');

        $validator
            ->requirePresence('Description', 'create')
            ->notEmpty('Description');

        $validator
            ->integer('Dice')
            ->requirePresence('Dice', 'create')
            ->notEmpty('Dice');

        $validator
            ->requirePresence('10_Again', 'create')
            ->notEmpty('10_Again');

        $validator
            ->requirePresence('9_Again', 'create')
            ->notEmpty('9_Again');

        $validator
            ->requirePresence('8_Again', 'create')
            ->notEmpty('8_Again');

        $validator
            ->requirePresence('1_Cancel', 'create')
            ->notEmpty('1_Cancel');

        $validator
            ->requirePresence('Used_WP', 'create')
            ->notEmpty('Used_WP');

        $validator
            ->requirePresence('Used_PP', 'create')
            ->notEmpty('Used_PP');

        $validator
            ->requirePresence('Result', 'create')
            ->notEmpty('Result');

        $validator
            ->requirePresence('Note', 'create')
            ->notEmpty('Note');

        $validator
            ->integer('Num_of_Successes')
            ->requirePresence('Num_of_Successes', 'create')
            ->notEmpty('Num_of_Successes');

        $validator
            ->requirePresence('Chance_Die', 'create')
            ->notEmpty('Chance_Die');

        $validator
            ->requirePresence('Bias', 'create')
            ->notEmpty('Bias');

        $validator
            ->requirePresence('Is_Rote', 'create')
            ->notEmpty('Is_Rote');

        return $validator;
    }
}

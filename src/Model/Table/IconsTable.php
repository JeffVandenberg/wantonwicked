<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Icons Model
 *
 * @method \App\Model\Entity\Icon get($primaryKey, $options = [])
 * @method \App\Model\Entity\Icon newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Icon[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Icon|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Icon patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Icon[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Icon findOrCreate($search, callable $callback = null, $options = [])
 */
class IconsTable extends Table
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

        $this->setTable('icons');
        $this->setDisplayField('icon_name');
        $this->setPrimaryKey('id');
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
            ->requirePresence('icon_name', 'create')
            ->notEmpty('icon_name');

        $validator
            ->requirePresence('player_viewable', 'create')
            ->notEmpty('player_viewable');

        $validator
            ->requirePresence('staff_viewable', 'create')
            ->notEmpty('staff_viewable');

        $validator
            ->requirePresence('admin_viewable', 'create')
            ->notEmpty('admin_viewable');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        return $rules;
    }

    public function listAvailableIcons()
    {
        $list = $this->find('all')
            ->select([
                'Icons.icon_id',
                'Icons.icon_name'
            ])
            ->where([
                'Icons.player_viewable' => 'Y'
            ])
            ->order([
                'Icons.icon_name'
            ])
            ->enableHydration(false)
            ->toArray();

        $icons = [];
        foreach ($list as $item) {
            $icons[$item['icon_id']] = $item['icon_name'];
        }

        return $icons;
    }
}

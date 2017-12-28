<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PlotStatuses Model
 *
 * @property \App\Model\Table\PlotsTable|\Cake\ORM\Association\HasMany $Plots
 *
 * @method \App\Model\Entity\PlotStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\PlotStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PlotStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PlotStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PlotStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PlotStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PlotStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class PlotStatusesTable extends Table
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

        $this->setTable('plot_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Plots', [
            'foreignKey' => 'plot_status_id'
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

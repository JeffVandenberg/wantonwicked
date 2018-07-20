<?php

namespace App\Model\Table;

use App\Model\Entity\RequestStatusHistory;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RequestStatusHistories Model
 *
 * @property \App\Model\Table\RequestsTable|\Cake\ORM\Association\BelongsTo $Requests
 * @property \App\Model\Table\RequestStatusesTable|\Cake\ORM\Association\BelongsTo $RequestStatuses
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $CreatedBies
 *
 * @method RequestStatusHistory get($primaryKey, $options = [])
 * @method RequestStatusHistory newEntity($data = null, array $options = [])
 * @method RequestStatusHistory[] newEntities(array $data, array $options = [])
 * @method RequestStatusHistory|bool save(EntityInterface $entity, $options = [])
 * @method RequestStatusHistory patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method RequestStatusHistory[] patchEntities($entities, array $data, array $options = [])
 * @method RequestStatusHistory findOrCreate($search, callable $callback = null, $options = [])
 */
class RequestStatusHistoriesTable extends Table
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

        $this->setTable('request_status_histories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_on' => 'new'
                ]
            ]
        ]);
        $this->belongsTo('Requests', [
            'foreignKey' => 'request_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RequestStatuses', [
            'foreignKey' => 'request_status_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CreatedBy', [
            'foreignKey' => 'created_by_id',
            'joinType' => 'INNER',
            'className' => 'Users'
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
        $rules->add($rules->existsIn(['request_id'], 'Requests'));
        $rules->add($rules->existsIn(['request_status_id'], 'RequestStatuses'));
        $rules->add($rules->existsIn(['created_by_id'], 'CreatedBy'));

        return $rules;
    }

    public function getActivityReport($startDate, $endDate)
    {
        $query = $this->find();
        return $query
            ->select([
                'CreatedBy.username',
                'RequestStatuses.name',
                'total' => $query->func()->count('*')
            ])
            ->contain([
                'CreatedBy',
                'RequestStatuses'
            ])
            ->where([
                'created_on >=' => $startDate,
                'created_on <=' => $endDate
            ])
            ->group([
                'CreatedBy.username',
                'RequestStatuses.name'
            ])
            ->order([
                'CreatedBy.username',
                'RequestStatuses.name'
            ])
            ->enableHydration(false)
            ->toArray();
    }

    public function getTimeReport()
    {
        $sql = <<<SQL
SELECT
    character_type,
    AVG(UNIX_TIMESTAMP(first_view)-UNIX_TIMESTAMP(created)) AS first_view,
    AVG(UNIX_TIMESTAMP(terminal_status)-UNIX_TIMESTAMP(created)) AS terminal_status,
    AVG(UNIX_TIMESTAMP(closed)-UNIX_TIMESTAMP(created)) AS closed
FROM
    (
    SELECT
        C.character_type,
        (
            SELECT
                min(created_on)
            FROM
                request_status_histories AS RSH
            WHERE
                RSH.request_id = R.id
                AND RSH.request_status_id = 1
            GROUP BY
                RSH.request_id
        ) AS created,
        (
            SELECT
                min(created_on)
            FROM
                request_status_histories AS RSH
            WHERE
                RSH.request_id = R.id
                AND RSH.request_status_id = 2
            GROUP BY
                RSH.request_id
        ) AS first_view,
        (
            SELECT
                min(created_on)
            FROM
                request_status_histories AS RSH
            WHERE
                RSH.request_id = R.id
                AND RSH.request_status_id IN (4,5)
            GROUP BY
                RSH.request_id
        ) AS terminal_status,
        (
            SELECT
                min(created_on)
            FROM
                request_status_histories AS RSH
            WHERE
                RSH.request_id = R.id
                AND RSH.request_status_id = 7
            GROUP BY
                RSH.request_id
        ) AS closed
    FROM
        requests AS R
        INNER JOIN characters AS C ON R.character_id = C.id
    ) AS A
WHERE
    created IS NOT NULL
GROUP BY
    character_type
SQL;

        return $this->getConnection()->execute($sql);
    }
}

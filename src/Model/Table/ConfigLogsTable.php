<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigLogs Model
 *
 * @method \App\Model\Entity\ConfigLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigLog|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigLog findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfigLogsTable extends Table
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

        $this->setTable('config_logs');
        $this->setDisplayField('id');
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
            ->scalar('user')
            ->maxLength('user', 255)
            ->requirePresence('user', 'create')
            ->notEmpty('user');

        $validator
            ->scalar('ip')
            ->maxLength('ip', 255)
            ->requirePresence('ip', 'create')
            ->notEmpty('ip');

        $validator
            ->scalar('controller')
            ->maxLength('controller', 255)
            ->requirePresence('controller', 'create')
            ->notEmpty('controller');

        $validator
            ->scalar('action')
            ->maxLength('action', 255)
            ->requirePresence('action', 'create')
            ->notEmpty('action');

        $validator
            ->scalar('params')
            ->maxLength('params', 255)
            ->requirePresence('params', 'create')
            ->notEmpty('params');

        $validator
            ->scalar('request')
            ->maxLength('request', 4294967295)
            ->requirePresence('request', 'create')
            ->notEmpty('request');

		$validator
            ->scalar('date')
            ->maxLength('date', 255)
            ->requirePresence('date', 'create')
            ->notEmpty('date');
			
        return $validator;
    }
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigParametres Model
 *
 * @method \App\Model\Entity\ConfigParametre get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigParametre newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigParametre[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigParametre|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigParametre|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigParametre patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigParametre[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigParametre findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfigParametresTable extends Table
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

        $this->setTable('config_parametres');
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
            ->integer('pourcentage')
            ->allowEmpty('pourcentage');

        $validator
            ->decimal('cout_personnel')
            ->allowEmpty('cout_personnel');

        $validator
            ->decimal('cout_kilometres')
            ->allowEmpty('cout_kilometres');

        $validator
            ->decimal('cout_repas')
            ->allowEmpty('cout_repas');

        return $validator;
    }
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigEnvironnements Model
 *
 * @property \App\Model\Table\DispositifsTable|\Cake\ORM\Association\HasMany $Dispositifs
 *
 * @method \App\Model\Entity\ConfigEnvironnement get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigEnvironnement newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigEnvironnement[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigEnvironnement|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigEnvironnement|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigEnvironnement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigEnvironnement[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigEnvironnement findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfigEnvironnementsTable extends Table
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

        $this->setTable('config_environnements');
        $this->setDisplayField('environnement');
        $this->setPrimaryKey('id');
		
		$this->addBehavior('Listing');

        $this->hasMany('Dispositifs', [
            'foreignKey' => 'config_environnement_id'
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
            ->decimal('indice')
            ->allowEmpty('indice');

        $validator
            ->scalar('environnement')
            ->maxLength('environnement', 4294967295)
            ->allowEmpty('environnement');

        return $validator;
    }
}

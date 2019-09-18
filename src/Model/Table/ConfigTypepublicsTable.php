<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigTypepublics Model
 *
 * @property \App\Model\Table\DispositifsTable|\Cake\ORM\Association\HasMany $Dispositifs
 *
 * @method \App\Model\Entity\ConfigTypepublic get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigTypepublic newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigTypepublic[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigTypepublic|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigTypepublic|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigTypepublic patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigTypepublic[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigTypepublic findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfigTypepublicsTable extends Table
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

        $this->setTable('config_typepublics');
        $this->setDisplayField('designation');
        $this->setPrimaryKey('id');
		
		$this->addBehavior('Listing');

        $this->hasMany('Dispositifs', [
            'foreignKey' => 'config_typepublic_id'
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
            ->scalar('designation')
            ->maxLength('designation', 4294967295)
            ->allowEmpty('designation');

        return $validator;
    }
}

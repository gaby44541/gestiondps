<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigEtats Model
 *
 * @property \App\Model\Table\DemandesTable|\Cake\ORM\Association\HasMany $Demandes
 *
 * @method \App\Model\Entity\ConfigEtat get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigEtat newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigEtat[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigEtat|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigEtat|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigEtat patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigEtat[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigEtat findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfigEtatsTable extends Table
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

        $this->setTable('config_etats');
        $this->setDisplayField('designation');
        $this->setPrimaryKey('id');

        $this->hasMany('Demandes', [
            'foreignKey' => 'config_etat_id'
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
            ->scalar('designation')
            ->maxLength('designation', 255)
            ->requirePresence('designation', 'create')
            ->notEmpty('designation');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->scalar('class')
            ->maxLength('class', 255)
            ->requirePresence('class', 'create')
            ->notEmpty('class');

        $validator
            ->integer('ordre')
            ->requirePresence('ordre', 'create')
            ->notEmpty('ordre');

        return $validator;
    }
	
	/**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function reorder()
    {
		$list = $this->find('all',['order'=>['ordre'=>'asc']]);
		$i = 0;
		foreach( $list as $item ){
			$item->ordre = $i;
			$this->save($item);
			$i++;
		}
    }

	public function alone($step = 0)
	{
		$step = (int) $step;
		return $this->find()->where([
            'ordre' => $step
        ])->combine('id','designation')->toArray();

	}

	public function firstid()
	{
		$array = $this->alone(0);
		return key($array);
	}

	public function searchid($id = 0)
	{
		$id = (int) $id;
		return $this->find()->where([
            'id' => $id
        ])->combine('id','designation')->toArray();

	}
}

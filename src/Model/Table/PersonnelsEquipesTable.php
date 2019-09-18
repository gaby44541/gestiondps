<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PersonnelsEquipes Model
 *
 * @property \App\Model\Table\EquipesTable|\Cake\ORM\Association\BelongsTo $Equipes
 * @property \App\Model\Table\PersonnelsTable|\Cake\ORM\Association\BelongsTo $Personnels
 *
 * @method \App\Model\Entity\PersonnelsEquipe get($primaryKey, $options = [])
 * @method \App\Model\Entity\PersonnelsEquipe newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PersonnelsEquipe[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PersonnelsEquipe|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PersonnelsEquipe|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PersonnelsEquipe patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PersonnelsEquipe[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PersonnelsEquipe findOrCreate($search, callable $callback = null, $options = [])
 */
class PersonnelsEquipesTable extends Table
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

        $this->setTable('personnels_equipes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Equipes', [
            'foreignKey' => 'equipe_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Personnels', [
            'foreignKey' => 'personnel_id',
            'joinType' => 'INNER'
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
            ->boolean('chef')
            ->requirePresence('chef', 'create')
            ->allowEmpty('chef');

        $validator
            ->scalar('selection')
            ->maxLength('selection', 255)
            ->requirePresence('selection')
            ->notEmpty('selection');

        $validator
            ->integer('disponibilite')
            ->requirePresence('disponibilite')
            ->allowEmpty('disponibilite');

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
        $rules->add($rules->existsIn(['equipe_id'], 'Equipes'));
        $rules->add($rules->existsIn(['personnel_id'], 'Personnels'));

        return $rules;
    }
}

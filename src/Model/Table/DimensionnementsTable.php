<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dimensionnements Model
 *
 * @property \App\Model\Table\DemandesTable|\Cake\ORM\Association\BelongsTo $Demandes
 * @property \App\Model\Table\DispositifsTable|\Cake\ORM\Association\HasMany $Dispositifs
 *
 * @method \App\Model\Entity\Dimensionnement get($primaryKey, $options = [])
 * @method \App\Model\Entity\Dimensionnement newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Dimensionnement[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dimensionnement|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dimensionnement|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dimensionnement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Dimensionnement[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dimensionnement findOrCreate($search, callable $callback = null, $options = [])
 */
class DimensionnementsTable extends Table
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

        $this->setTable('dimensionnements');
        $this->setDisplayField('intitule');
        $this->setPrimaryKey('id');

		$this->addBehavior('Listing');
		$this->addBehavior('Duplicatable');
		$this->addBehavior('Arrays',[
			'fields' => ['assis','acces','documents_officiels','secours_presents']
		]);

        $this->belongsTo('Demandes', [
            'foreignKey' => 'demande_id',
            'joinType' => 'LEFT'
        ]);
        $this->hasOne('Dispositifs', [
			'dependent' => true,
            'foreignKey' => 'dimensionnement_id'
        ]);
        $this->belongsTo('Caserne', [
            'foreignKey' => 'dimensionnement_id'
        ]);
        $this->belongsTo('Hopital', [
            'foreignKey' => 'dimensionnement_id'
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
            ->scalar('intitule')
            ->maxLength('intitule', 255)
            ->requirePresence('intitule', 'create')
            ->notEmpty('intitule');

        $validator
            ->dateTime('horaires_debut')
            ->notEmpty('horaires_debut');

        $validator
            ->dateTime('horaires_fin')
            ->notEmpty('horaires_fin');

        $validator
            ->scalar('lieu_manifestation')
            ->maxLength('lieu_manifestation', 500)
            ->requirePresence('lieu_manifestation', 'create')
            ->notEmpty('lieu_manifestation');

        $validator
            ->scalar('risques_particuliers')
            ->maxLength('risques_particuliers', 255)
            ->allowEmpty('risques_particuliers');

        $validator
            ->scalar('contact_portable')
            ->maxLength('contact_portable', 255)
            ->allowEmpty('contact_portable');

        $validator
            ->scalar('contact_present')
            ->maxLength('contact_present', 255)
            ->allowEmpty('contact_present');

        $validator
            ->scalar('contact_fonction')
            ->maxLength('contact_fonction', 255)
            ->allowEmpty('contact_fonction');

        $validator
            ->scalar('contact_telephone')
            ->maxLength('contact_telephone', 255)
            ->allowEmpty('contact_telephone');

        $validator
            ->integer('public_effectif')
            ->requirePresence('public_effectif', 'create')
            ->notEmpty('public_effectif');

        $validator
            ->scalar('public_age')
            ->maxLength('public_age', 255)
            ->requirePresence('public_age', 'create')
            ->notEmpty('public_age');

        $validator
            ->integer('acteurs_effectif')
            ->requirePresence('acteurs_effectif', 'create')
            ->notEmpty('acteurs_effectif');

        $validator
            ->scalar('acteurs_age')
            ->maxLength('acteurs_age', 255)
            ->requirePresence('acteurs_age', 'create')
            ->notEmpty('acteurs_age');

        $validator
			->scalar('assis')
            ->maxLength('assis', 255)
            ->allowEmpty('assis');

        $validator
            ->boolean('circuit')
            ->requirePresence('circuit', 'create')
            ->notEmpty('circuit');

        $validator
            ->boolean('ouvert')
            ->allowEmpty('ouvert');

        $validator
            ->decimal('superficie')
            ->allowEmpty('superficie');

        $validator
            ->integer('distance_maxi')
            ->allowEmpty('distance_maxi');

        $validator
            ->scalar('acces')
            ->maxLength('acces', 255)
            ->allowEmpty('acces');

        $validator
            ->scalar('besoins_particuliers')
            ->maxLength('besoins_particuliers', 255)
            ->allowEmpty('besoins_particuliers');

        $validator
            ->scalar('pompier')
            ->maxLength('pompier', 255)
            ->notEmpty('pompier');

        $validator
            ->scalar('id_pompier')
            ->maxLength('pompier', 255)
            ->notEmpty('pompier');

        $validator
            ->integer('pompier_delai')
            ->notEmpty('pompier_delai');

        $validator
            ->scalar('hopital')
            ->maxLength('hopital', 255)
            ->notEmpty('hopital');

        $validator
            ->scalar('id_hopital')
            ->maxLength('hopital', 255)
            ->notEmpty('hopital');

        $validator
            ->integer('hopital_delai')
            ->notEmpty('hopital_delai');

        $validator
            ->scalar('medecin')
            ->maxLength('medecin', 255)
            ->allowEmpty('medecin');

        $validator
            ->scalar('medecin_telephone')
            ->maxLength('medecin_telephone', 255)
            ->allowEmpty('medecin_telephone');

        $validator
            ->scalar('infirmier')
            ->maxLength('infirmier', 255)
            ->allowEmpty('infirmier');

        $validator
            ->scalar('kiné')
            ->maxLength('kiné', 255)
            ->allowEmpty('kiné');

        $validator
            ->scalar('medecin_autres')
            ->maxLength('medecin_autres', 255)
            ->allowEmpty('medecin_autres');

		$validator
            ->scalar('secours_presents')
            ->maxLength('secours_presents', 500)
            ->allowEmpty('secours_presents');

		$validator
            ->scalar('documents_officiels')
            ->maxLength('documents_officiels', 500)
            ->allowEmpty('documents_officiels');

        $validator
            ->scalar('ambulancier')
            ->maxLength('ambulancier', 255)
            ->allowEmpty('ambulancier');

        $validator
            ->scalar('ambulancier_telephone')
            ->maxLength('ambulancier_telephone', 255)
            ->requirePresence('ambulancier_telephone', 'create')
            ->allowEmpty('ambulancier_telephone');

        $validator
            ->scalar('autres_public')
            ->maxLength('autres_public', 255)
            ->allowEmpty('autres_public');

        $validator
            ->scalar('autres')
            ->maxLength('autres', 255)
            ->allowEmpty('autres');

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
        $rules->add($rules->existsIn(['demande_id'], 'Demandes'));

        return $rules;
    }

	/*
	public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
	{

		if (isset($data['assis'])&&is_array($data['assis'])) {
			$data['assis'] = implode(',',$data['assis']);
			//var_dump($data['assis']);
		}
		if (isset($data['secours_presents'])&&is_array($data['secours_presents'])) {
			$data['secours_presents'] = implode(',',$data['secours_presents']);
			//var_dump($data['secours_presents']);
		}
		if (isset($data['documents_officiels'])&&is_array($data['documents_officiels'])) {
			$data['documents_officiels'] = implode(',',$data['documents_officiels']);
			//var_dump($data['documents_officiels']);
		}
	}
	*/
}

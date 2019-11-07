<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Organisateurs Model
 *
 * @property \App\Model\Table\DemandesTable|\Cake\ORM\Association\HasMany $Demandes
 *
 * @method \App\Model\Entity\Organisateur get($primaryKey, $options = [])
 * @method \App\Model\Entity\Organisateur newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Organisateur[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Organisateur|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Organisateur|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Organisateur patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Organisateur[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Organisateur findOrCreate($search, callable $callback = null, $options = [])
 */
class OrganisateursTable extends Table
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

        $this->setTable('organisateurs');
        $this->setDisplayField('nom');
        $this->setPrimaryKey('id');

		$this->addBehavior('PublishUnique');
		$this->addBehavior('Listing');

        $this->hasMany('Demandes', [
            'foreignKey' => 'organisateur_id'
        ]);
    }

	public function findPublished(Query $query, array $options)
    {
        $query->where([
            'publish' => 1
        ]);
        return $query;
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
            ->scalar('uuid')
            ->maxLength('uuid', 255)
            ->allowEmpty('uuid');

        $validator
            ->integer('raison_sociale')
            ->allowEmpty('raison_sociale');

        $validator
            ->scalar('nom')
            ->maxLength('nom', 255)
            ->notEmpty('nom');

        $validator
            ->scalar('fonction')
            ->maxLength('fonction', 255)
            ->notEmpty('fonction');

        $validator
            ->scalar('adresse')
            ->maxLength('adresse', 255)
            ->notEmpty('adresse');

        $validator
            ->scalar('adresse_suite')
            ->maxLength('adresse_suite', 255)
            ->allowEmpty('adresse_suite');

        $validator
            ->scalar('code_postal')
            ->maxLength('code_postal', 255)
            ->notEmpty('code_postal');

        $validator
            ->scalar('ville')
            ->maxLength('ville', 255)
            ->notEmpty('ville');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 255)
            ->allowEmpty('telephone');

        $validator
            ->scalar('portable')
            ->maxLength('portable', 255)
            ->notEmpty('portable');

        $validator
            ->scalar('mail')
            ->maxLength('mail', 255)
			->requirePresence('mail')
            ->notEmpty('mail');

        $validator
            ->scalar('representant_prenom')
            ->maxLength('representant_prenom', 255)
			->requirePresence('representant_prenom')
            ->notEmpty('representant_prenom');

        $validator
            ->scalar('representant_nom')
            ->maxLength('representant_nom', 255)
			->requirePresence('representant_nom')
            ->notEmpty('representant_nom');

        $validator
            ->boolean('publish')
            ->allowEmpty('publish');

        return $validator;
    }
}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Antennes Model
 *
 * @property |\Cake\ORM\Association\HasMany $Demandes
 * @property \App\Model\Table\PersonnelsTable|\Cake\ORM\Association\BelongsToMany $Personnels
 *
 * @method \App\Model\Entity\Antenne get($primaryKey, $options = [])
 * @method \App\Model\Entity\Antenne newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Antenne[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Antenne|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Antenne|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Antenne patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Antenne[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Antenne findOrCreate($search, callable $callback = null, $options = [])
 */
class AntennesTable extends Table
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

        $this->setTable('antennes');
        $this->setDisplayField('antenne');
        $this->setPrimaryKey('id');

        $this->hasMany('Demandes', [
            'foreignKey' => 'antenne_id'
        ]);
        $this->belongsToMany('Personnels', [
            'foreignKey' => 'antenne_id',
            'targetForeignKey' => 'personnel_id',
            'joinTable' => 'personnels_antennes'
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
            ->scalar('antenne')
            ->maxLength('antenne', 255)
            ->notEmpty('antenne')
			->requirePresence('antenne');

        $validator
            ->scalar('adresse')
            ->maxLength('adresse', 255)
            ->notEmpty('adresse')
			->requirePresence('adresse');

        $validator
            ->scalar('adresse_suite')
            ->maxLength('adresse_suite', 255)
            ->allowEmpty('adresse_suite');

        $validator
            ->scalar('code_postal')
            ->maxLength('code_postal', 255)
            ->notEmpty('code_postal')
			->requirePresence('code_postal');

        $validator
            ->scalar('ville')
            ->maxLength('ville', 255)
            ->notEmpty('ville')
			->requirePresence('ville');

        $validator
            ->scalar('telephone')
            ->maxLength('telephone', 255)
            ->allowEmpty('telephone');

        $validator
            ->scalar('portable')
            ->maxLength('portable', 255)
            ->allowEmpty('portable');

        $validator
            ->scalar('mail')
            ->maxLength('mail', 255)
            ->notEmpty('mail')
			->requirePresence('mail');

        $validator
            ->scalar('fax')
            ->maxLength('fax', 255)
            ->allowEmpty('fax');

        $validator
            ->scalar('rib_etablissemnt')
            ->maxLength('rib_etablissemnt', 255)
            ->allowEmpty('rib_etablissemnt');

        $validator
            ->scalar('rib_guichet')
            ->maxLength('rib_guichet', 255)
            ->allowEmpty('rib_guichet');

        $validator
            ->scalar('rib_compte')
            ->maxLength('rib_compte', 255)
            ->allowEmpty('rib_compte');

        $validator
            ->scalar('rib_rice')
            ->maxLength('rib_rice', 255)
            ->allowEmpty('rib_rice');

        $validator
            ->scalar('rib_domicile')
            ->maxLength('rib_domicile', 255)
            ->allowEmpty('rib_domicile');

        $validator
            ->scalar('rib_bic')
            ->maxLength('rib_bic', 255)
            ->allowEmpty('rib_bic');

        $validator
            ->scalar('rib_iban')
            ->maxLength('rib_iban', 255)
            ->allowEmpty('rib_iban');

        $validator
            ->scalar('cheque')
            ->maxLength('cheque', 255)
            ->allowEmpty('cheque');

        $validator
            ->scalar('etat')
            ->maxLength('etat', 255)
            ->notEmpty('etat');

        $validator
            ->scalar('technique_nom')
            ->maxLength('technique_nom', 255)
            ->notEmpty('technique_nom')
			->requirePresence('technique_nom');
			
		$validator
            ->scalar('technique_mail')
            ->maxLength('technique_mail', 255)
            ->notEmpty('technique_mail')
			->requirePresence('technique_mail');

        $validator
            ->scalar('tresorier_nom')
            ->maxLength('tresorier_nom', 255)
            ->notEmpty('tresorier_nom')
			->requirePresence('tresorier_nom');
			
        $validator
            ->scalar('tresorier_mail')
            ->maxLength('tresorier_mail', 255)
            ->notEmpty('tresorier_mail')
			->requirePresence('tresorier_mail');
			
        return $validator;
    }
}

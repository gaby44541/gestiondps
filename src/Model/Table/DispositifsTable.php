<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
use Cake\Log\Log;
use Cake\Utility\Hash;

/**
 * Dispositifs Model
 *
 * @property \App\Model\Table\DimensionnementsTable|\Cake\ORM\Association\BelongsTo $Dimensionnements
 * @property \App\Model\Table\ConfigTypepublicsTable|\Cake\ORM\Association\BelongsTo $ConfigTypepublics
 * @property \App\Model\Table\ConfigEnvironnementsTable|\Cake\ORM\Association\BelongsTo $ConfigEnvironnements
 * @property \App\Model\Table\ConfigDelaisTable|\Cake\ORM\Association\BelongsTo $ConfigDelais
 * @property \App\Model\Table\EquipesTable|\Cake\ORM\Association\HasMany $Equipes
 *
 * @method \App\Model\Entity\Dispositif get($primaryKey, $options = [])
 * @method \App\Model\Entity\Dispositif newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Dispositif[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dispositif|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dispositif|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dispositif patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Dispositif[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dispositif findOrCreate($search, callable $callback = null, $options = [])
 */
class DispositifsTable extends Table
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
        //Log::write('debug', 'DispositifsTable - initialize');
        $this->setTable('dispositifs');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

		$this->addBehavior('Listing');

        $this->belongsTo('Dimensionnements', [
            'foreignKey' => 'dimensionnement_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ConfigTypepublics', [
            'foreignKey' => 'config_typepublic_id'
        ]);
        $this->belongsTo('ConfigEnvironnements', [
            'foreignKey' => 'config_environnement_id'
        ]);
        $this->belongsTo('ConfigDelais', [
            'foreignKey' => 'config_delai_id'
        ]);
        $this->hasMany('Equipes', [
			'dependent' => true,
            'foreignKey' => 'dispositif_id'
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
        //Log::write('debug', 'DispositifsTable - validationDefault');

        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title')
            ->notEmpty('title');

        $validator
            ->decimal('ris')
            ->allowEmpty('ris');

        $validator
            ->scalar('recommandation_type')
            ->maxLength('recommandation_type', 255)
            ->allowEmpty('recommandation_type');

        $validator
            ->scalar('recommandation_poste')
            ->maxLength('recommandation_poste', 4294967295)
            ->allowEmpty('recommandation_poste');

        $validator
            ->integer('personnels_public')
            ->allowEmpty('personnels_public');

        $validator
            ->scalar('organisation_public')
            ->maxLength('organisation_public', 4294967295)
			->requirePresence('organisation_public', 'update')
            ->notEmpty('organisation_public','update');
			//->requirePresence('organisation_public', 'create')

        $validator
            ->integer('personnels_acteurs')
            ->allowEmpty('personnels_acteurs');

        $validator
            ->scalar('organisation_acteurs')
            ->maxLength('organisation_acteurs', 4294967295)
            ->requirePresence('organisation_acteurs', 'update')
            ->notEmpty('organisation_acteurs','update');

        $validator
            ->integer('personnels_total')
            ->allowEmpty('personnels_total');

        $validator
            ->scalar('organisation_poste')
            ->maxLength('organisation_poste', 4294967295)
			->allowEmpty('organisation_poste');
            //->requirePresence('organisation_poste', 'update')
            //->notEmpty('organisation_poste','update');

        $validator
            ->scalar('organisation_transport')
            ->maxLength('organisation_transport', 4294967295)
			->allowEmpty('organisation_transport');
            //->requirePresence('organisation_transport')
            //->notEmpty('organisation_transport');

        $validator
            ->scalar('consignes_generales')
            ->maxLength('consignes_generales', 4294967295)
			->allowEmpty('consignes_generales');
           // ->requirePresence('consignes_generales', 'update')
           // ->notEmpty('consignes_generales','update');

        $validator
            ->integer('assiste')
            ->allowEmpty('assiste');

        $validator
            ->integer('leger')
            ->allowEmpty('leger');

        $validator
            ->integer('medicalise')
            ->allowEmpty('medicalise');

        $validator
            ->integer('evacue')
            ->allowEmpty('evacue');

        $validator
            ->scalar('rapport')
            ->maxLength('rapport', 4294967295)
            ->allowEmpty('rapport');
			//->requirePresence('rapport', 'create')

        $validator
            ->scalar('accord_siege')
            ->maxLength('accord_siege', 255)
            ->allowEmpty('accord_siege');

        $validator
            ->integer('nb_chef_equipe')
            ->allowEmpty('nb_chef_equipe');

        $validator
            ->integer('nb_pse2')
            ->allowEmpty('nb_pse2');

        $validator
            ->integer('nb_pse1')
            ->allowEmpty('nb_pse1');

        $validator
            ->integer('nb_lat')
            ->allowEmpty('nb_lat');

        $validator
            ->integer('nb_medecin')
            ->allowEmpty('nb_medecin');

        $validator
            ->integer('nb_infirmier')
            ->allowEmpty('nb_infirmier');

        $validator
            ->integer('nb_cadre_operationnel')
            ->allowEmpty('nb_cadre_operationnel');

        $validator
            ->integer('nb_stagiaire')
            ->allowEmpty('nb_stagiaire');

        $validator
            ->integer('numero_coa')
            ->allowEmpty('numero_coa');

        //Log::write('debug', 'DispositifsTable - validationDefault - fin');


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
       // Log::write('debug', 'DispositifsTable - buildRules');

        $rules->add($rules->existsIn(['dimensionnement_id'], 'Dimensionnements'));
        $rules->add($rules->existsIn(['config_typepublic_id'], 'ConfigTypepublics'));
        $rules->add($rules->existsIn(['config_environnement_id'], 'ConfigEnvironnements'));
        $rules->add($rules->existsIn(['config_delai_id'], 'ConfigDelais'));

       // Log::write('debug', 'DispositifsTable - buildRules - fin');

        return $rules;
    }

	public function precreate($dimensionnement=0)
	{
        //Log::write('debug', 'DispositifsTable - precreate');

		$dimension = TableRegistry::get('Dimensionnements');
		$dimension = $dimension->findById($dimensionnement)->first();

		$demande = TableRegistry::get('Demandes');
		$demande = $demande->findById($dimension->id)->first();

		$parametre = TableRegistry::get('ConfigParametres');
		$parametre = $parametre->find('all',['order'=>['id'=>'asc']])->all()->last();

		$data = [];
		$data['title'] = $demande->manifestation .' - '.$dimension->intitule;
		$data['consignes_generales'] = $parametre->consignes_generales;
		$data['organisation_transport'] = $parametre->organisation_transport;
		$data['personnels_acteurs'] = 4;
		$data['accord_siege'] =  uniqid(date('Ymd').'-');

		$p1 = $this->typepublic($dimension->assis,$dimension->circuit);
		$e1 = $this->environnement($dimension->superficie,$dimension->distance_maxi);
		$e2 = $this->typepublic($dimension->pompier_distance,$dimension->hopital_distance);

		$data['config_typepublic_id'] = $this->indice('ConfigTypepublics',$p1);
		$data['config_environnement_id'] = $this->indice('ConfigEnvironnements',$e1);
		$data['config_delai_id'] = $this->indice('ConfigDelais',$e1);
		$data['ris'] = $this->ris($dimension->public_effectif,$p1,$e1,$e2);

		$effectif = $this->effectif($data['ris']);

		$data['personnels_public'] = $effectif;
		$data['recommandation_poste'] = $this->typeposte($effectif);

       // Log::write('debug', 'DispositifsTable - precreate fin');

	}
	protected function typeposte($effectif=0,$sort='type')
	{
		$tmp = TableRegistry::get('ConfigPostes');
		$result = $tmp->find('all',['conditions'=>['maxi >='=>$effectif,'mini <'=>$effectif]])->first();

		return $result->{$sort};
	}

	protected function indice($model='',$indice=0)
	{
		$tmp = TableRegistry::get($model);
		$result = $tmp->findByIndice($indice)->first();

		return $result->id;
	}
	protected function getindice($model='',$id=0)
	{
		$tmp = TableRegistry::get($model);
		$result = $tmp->findById($id)->first();

		return $result->indice;
	}
	protected function typepublic($assis=0,$circuit=0)
	{
		if(!$assis){
			$p1 = 0.25;
		}else{
			$p1 = 0.35;
		}
		if($assis && $circuit){
			$p1 = 0.40;
		}

		$tmp = TableRegistry::get('ConfigTypepublics');
		$tmp = $tmp->findByIndice($p1)->first();

		return $p1;
	}

	protected function delais($kmsp=0,$kmh=0)
	{
		$temps_sp = ($kmsp / 80 ) * 60;
		$temps_h = ($kmh / 80 ) * 60;
		$moyenne = ($temps_sp + $temps_h) /2;

		$moyenne = ceil($moyenne);

		if($moyenne <= 10){
			$e2 = 0.25;
		}elseif($moyenne > 10&&$moyenne <= 20){
			$e2 = 0.30;
		}elseif($moyenne > 20&&$moyenne <= 30){
			$e2 = 0.35;
		}elseif($moyenne > 30){
			$e2 = 0.40;
		}

		return $e2;
	}

	protected function environnement($superficie=0,$distance=0)
	{
		$superficie = $superficie * 100;
		$distance = (int) $distance;

		if($superficie <= 2){
			$e1 = 0.30;
		}elseif($superficie > 2&&$superficie <= 5){
			$e1 = 0.35;
		}elseif($superficie > 5){
			$e1 = 0.40;
		}

		if($distance <= 150){
			$d1 = 0.25;
		}elseif($distance > 150&&$distance <= 300){
			$d1 = 0.30;
		}elseif($distance > 300&&$distance <= 600){
			$d1 = 0.35;
		}elseif($distance > 600){
			$d1 = 0.40;
		}

		$e1 = ($d1 < $e1) ? $e1 : $d1;

		return $e1;
	}
	protected function ris($p=0,$p1=0,$e1=0,$e2=0)
	{
		if($p>100000){
			$p = 100000+(($p-100000)/2);
		}
		$ris = ($p1+$e1+$e2) * ($p/1000);

		return $ris;
	}

	protected function effectif($ris)
	{
		if($ris <= 0.25){
			$effectif = 0;
		}elseif($ris > 0.25&&$ris <= 1.125){
			$effectif = 2;
		}elseif($ris > 1.125&&$ris <= 4){
			$effectif = 4;
		}elseif($ris > 4){
			$effectif = ceil($ris);
			if($effectif%2 == 1){
				$effectif = $effectif +1;
			}
		}
		return $effectif;
	}

	public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
	{
		//$this->precreate($data['dimensionnement_id']);
        //Log::write('debug', 'DispositifsTable - beforeMarshal');
		$parametre = TableRegistry::get('ConfigParametres');
		$parametre = $parametre->find('all',['order'=>['id'=>'asc']])->all()->last();

		if (isset($data['consignes_generales'])) {
			if (empty($data['consignes_generales'])) {
				$data['consignes_generales'] = $parametre->consignes_generales;
			}
		}
		if (isset($data['organisation_transport'])) {
			if (empty($data['organisation_transport'])) {
				$data['organisation_transport'] = $parametre->organisation_transport;
			}
		}
		if (isset($data['accord_siege'])) {
			if (empty($data['accord_siege'])) {
				$data['accord_siege'] = uniqid(date('Ymd').'-');
			}
		}

		if (!isset($data['rapport'])) {
			$data['rapport'] = ' ';
		}

		if (!isset($data['organisation_public'])) {
			$data['organisation_public'] = 'A définir';
		}

		if (!isset($data['organisation_acteurs'])) {
			$data['organisation_acteurs'] = 'A définir';
		}

		if (!isset($data['organisation_poste'])) {
			$data['organisation_poste'] = 'A définir';
		}

		if (!isset($data['consignes_generales'])) {
			$data['consignes_generales'] = 'A définir';
		}

		$dimension = TableRegistry::get('Dimensionnements');
		$dimension = $dimension->findById($data['dimensionnement_id'])->first();

		//Log::write('debug', $dimension );

		$p1 = $this->getindice('ConfigTypepublics',$data['config_typepublic_id']);
		$e1 = $this->getindice('ConfigEnvironnements',$data['config_environnement_id']);
		$e2 = $this->getindice('ConfigDelais',$data['config_delai_id']);

		$data['ris'] = $this->ris($dimension->public_effectif,$p1,$e1,$e2);

		$effectif = (int) $this->effectif( $data['ris'] );

		$data['recommandation_type'] = $this->typeposte($effectif);
		$data['recommandation_poste'] = $this->typeposte($effectif,'recommandations');

		if (!isset($data['personnels_public'])) {
			$data['personnels_public'] = 0;
		}

		if(isset($data['personnels_public'])){
			if( $effectif <= (int) $data['personnels_public'] ){
				$effectif = (int) $data['personnels_public'];
			}
		}

		$data['personnels_public'] = (int) $effectif;

		if( empty($data['personnels_public']) ) {
			$data['organisation_public'] = 'Aucun dispositif public nécessaire.';
		}

		//Log::write('debug', $data['personnels_public'] );

		if (!isset($data['personnels_acteurs'])) {
			if( ! empty( $dimension->acteurs_effectif ) ){
				$data['personnels_acteurs'] = 4;
				$data['organisation_acteurs'] = 'Le logiciel a indiqué un minimum de 4, à vous d\'ajuster selon les besoins du terrain et la configuration de la manifestation.';
			} else {
				$data['personnels_acteurs'] = 0;
			}
		}

		$data['personnels_total'] = (int) $data['personnels_public'] + (int) $data['personnels_acteurs'];


		$tmp = [];

		if(isset( $data['organisation_poste'] )){
			$tmp = explode('---',$data['organisation_poste'] );
		}

		if(!isset($tmp[1])){
			$tmp[1] = '';
		}

        $texte_organisation = 'Le poste sera composé de '.$data['personnels_total'].' personnels, avec  ';
        if($data['nb_chef_equipe'] > 0){
            $texte_organisation = $texte_organisation.$data['nb_chef_equipe'].' chef(s) d\'équipe, ';
        }
        if($data['nb_pse2'] > 0){
            $texte_organisation = $texte_organisation.$data['nb_pse2'].' PSE2, ';
        }
        if($data['nb_pse1'] > 0){
            $texte_organisation = $texte_organisation.$data['nb_pse1'].' PSE1, ';
        }
        if($data['nb_lat'] > 0){
            $texte_organisation = $texte_organisation.$data['nb_lat'].' LAT, ';
        }
        if($data['nb_medecin'] > 0){
            $texte_organisation = $texte_organisation.$data['nb_medecin'].' médecin(s), ';
        }
        if($data['nb_infirmier'] > 0){
            $texte_organisation = $texte_organisation.$data['nb_infirmier'].' infirmier(s), ';
        }
        if($data['nb_cadre_operationnel'] > 0){
            $texte_organisation = $texte_organisation.$data['nb_cadre_operationnel'].' cadre(s) opérationnel(s), ';
        }
        if($data['nb_stagiaire'] > 0){
            $texte_organisation = $texte_organisation.$data['nb_stagiaire'].' stagiaire(s) (les stagiaires ne sont pas compris dans le personnel), ';
        }
        // On supprime le dernier caractère qui est une virgule.
        $texte_organisation = substr_replace($texte_organisation ,"", -2);

		$data['organisation_poste'] = $texte_organisation.'.
---'.$tmp[1];

        //Log::write('debug', 'DispositifsTable - beforeMarshal fin');


	}

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function generateSave($id=0)
    {
           // Log::write('debug', 'DispositifsTable - generateSave');


        $dimensionnements = $this->Dimensionnements->find('all',['contain'=>['Dispositifs']])->where(['demande_id'=>$id])->toArray();

		$parametre = TableRegistry::get('ConfigParametres');
		$parametre = $parametre->find('all',['order'=>['id'=>'asc']])->all()->last();

		foreach($dimensionnements as $dimensionnement){

			$preset = [];

			if(empty($dimensionnement->dispositif)){

				$preset['dimensionnement_id'] = $dimensionnement->id;
				$preset['title'] = $dimensionnement->intitule. ' - Dispositif';

				$assis = explode(',',$dimensionnement->assis);

				$preset['config_typepublic_id'] = 4;

				if(in_array('Assis',$assis)){
					$preset['config_typepublic_id'] = 1;
				}

				if(in_array('Statique',$assis)){
					$preset['config_typepublic_id'] = 1;
				}

				if(in_array('Debout',$assis)){
					$preset['config_typepublic_id'] = 2;
				}

				if(in_array('Debout',$assis)&&in_array('Statique',$assis)){
					$preset['config_typepublic_id'] = 3;
				}

				if(in_array('Assis',$assis)&&in_array('Dynamique',$assis)){
					$preset['config_typepublic_id'] = 4;
				}

				if(in_array('Debout',$assis)&&in_array('Dynamique',$assis)){
					$preset['config_typepublic_id'] = 4;
				}

				$environnements = [];
				$environnements[1] = ['ville','rue','bâtiment','salle','facile','dégagé'];
				$environnements[2] = ['gradin','tribune','chapiteau'];
				$environnements[3] = ['difficile','pente','champ','ville'];
				$environnements[4] = ['public','talu','escalier','chemin','forêt','foret','accidenté'];

				$preset['config_environnement_id'] = 0;

				foreach($environnements as $key => $environnement){
					foreach($environnement as $item){
						if(strpos($dimensionnement->access,$item)!==false){
							$preset['config_environnement_id'] = $key;
						}
					}
				}
				if($dimensionnement->superficie >= 2 && $dimensionnement->superficie < 5){
					if($preset['config_environnement_id'] < 3){
						$preset['config_environnement_id'] = 3;
					}
				}
				if($dimensionnement->superficie >= 5){
					if($preset['config_environnement_id'] < 4){
						$preset['config_environnement_id'] = 4;
					}
				}
				if($dimensionnement->distance_maxi < 150){
					if($preset['config_environnement_id'] < 1){
						$preset['config_environnement_id'] = 1;
					}
				}
				if($dimensionnement->superficie >= 150 && $dimensionnement->superficie < 300){
					if($preset['config_environnement_id'] < 2){
						$preset['config_environnement_id'] = 2;
					}
				}
				if($dimensionnement->superficie >= 300 && $dimensionnement->superficie < 600){
					if($preset['config_environnement_id'] < 3){
						$preset['config_environnement_id'] = 3;
					}
				}
				if($dimensionnement->superficie >= 600){
					if($preset['config_environnement_id'] < 4){
						$preset['config_environnement_id'] = 4;
					}
				}

				if(empty($preset['config_environnement_id'])){
					$preset['config_environnement_id'] = 3;
				}

				$pompier = (int) $dimensionnement->pompier_distance;
				$hopital = (int) $dimensionnement->hopital_distance;

				$pompier = empty($pompier) ? 20 : $pompier;
				$hopital = empty($hopital) ? 20 : $hopital;

				$pompier = $pompier / 1.1;
				$hopital = $hopital / 1.1;

				$moyenne = ($pompier + $hopital)/2;

				if($moyenne<10){
					$preset['config_delai_id'] = 1;
				} elseif($moyenne>=10 && $moyenne <20){
					$preset['config_delai_id'] = 2;
				} elseif($moyenne>=20 && $moyenne <30){
					$preset['config_delai_id'] = 3;
				} else{
					$preset['config_delai_id'] = 4;
				}

				$preset['consignes_generales'] = '';
				$preset['organisation_transport'] = '';

				$preset['personnels_acteurs'] = empty($dimensionnement->acteurs_effectif)? 0 : 4;

				$preset['accord_siege'] =  uniqid(date('Ymd').'-');

                //Log::write('debug', 'generateSave - nb chef dequipe : '.$preset['nb_chef_equipe']);




				$this->generateSaveAlone($preset);
			}
		}
           // Log::write('debug', 'DispositifsTable - generateSave - fin');

	}

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function generateSaveAlone( $preset = [] )
    {
        $dispositif = $this->newEntity();
        //Log::write('debug', 'dispositif :'.$dispositif);
       // Log::write('debug', 'dispositif - preset :'.$preset);

		$dispositif = $this->patchEntity($dispositif, $preset);

		if ($this->save($dispositif)) {
			return true;
		}

		return false;

	}

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function resetByDemande($id = 0)
    {
		$dispositifs = $this->find('all', [
			'contain' => ['Dimensionnements']
		])->where(['Dimensionnements.demande_id'=>$id]);

		if( ! $dispositifs ){
			return false;
		}

		foreach($dispositifs as $tmp){
			$this->delete($tmp);
		}

	}
}
?>

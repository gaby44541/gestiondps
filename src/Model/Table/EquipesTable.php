<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
use Cake\I18n\Time;
use Cake\Utility\Hash;
use Cake\Log\Log;

/**
 * Equipes Model
 *
 * @property \App\Model\Table\DispositifsTable|\Cake\ORM\Association\BelongsTo $Dispositifs
 * @property \App\Model\Table\PersonnelsTable|\Cake\ORM\Association\BelongsToMany $Personnels
 *
 * @method \App\Model\Entity\Equipe get($primaryKey, $options = [])
 * @method \App\Model\Entity\Equipe newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Equipe[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Equipe|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Equipe|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Equipe patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Equipe[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Equipe findOrCreate($search, callable $callback = null, $options = [])
 */
class EquipesTable extends Table
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

        $this->setTable('equipes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

		$this->addBehavior('Duplicatable');
		$this->addBehavior('Listing');
				
        $this->belongsTo('Dispositifs', [
            'foreignKey' => 'dispositif_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsToMany('Personnels', [
            'foreignKey' => 'equipe_id',
            'targetForeignKey' => 'personnel_id',
            'joinTable' => 'personnels_equipes'
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
            ->scalar('indicatif')
            ->maxLength('indicatif', 255)
            ->requirePresence('indicatif', 'create')
            ->notEmpty('indicatif');

        $validator
            ->integer('effectif')
            ->allowEmpty('effectif')
			->range('effectif',[1,5],__('Une équipe est composée au minimum de 2 et au maximum de 4 équipiers'));

        $validator
            ->scalar('vehicule_type')
            ->maxLength('vehicule_type', 255)
            ->allowEmpty('vehicule_type');

        $validator
            ->integer('vehicules_km')
            ->allowEmpty('vehicules_km')
			->nonNegativeInteger('vehicules_km');

        $validator
            ->integer('vehicule_trajets')
            ->allowEmpty('vehicule_trajets')
			->nonNegativeInteger('vehicule_trajets');

        $validator
            ->integer('lot_a')
            ->allowEmpty('lot_a')
			->nonNegativeInteger('lot_a');

        $validator
            ->integer('lot_b')
            ->allowEmpty('lot_b')
			->nonNegativeInteger('lot_b');

        $validator
            ->integer('lot_c')
            ->allowEmpty('lot_c')
			->nonNegativeInteger('lot_v');

        $validator
            ->scalar('autre')
            ->maxLength('autre', 255)
            ->allowEmpty('autre');

        $validator
            ->scalar('consignes')
            ->maxLength('consignes', 4294967295)
            ->allowEmpty('consignes');
			
        $validator
            ->scalar('position')
            ->maxLength('position', 255)
            ->requirePresence('position')
            ->notEmpty('position');
			
        $validator
            ->dateTime('horaires_convocation')
            ->notEmpty('horaires_convocation');

        $validator
            ->dateTime('horaires_place')
            ->notEmpty('horaires_place');

        $validator
            ->dateTime('horaires_fin')
            ->notEmpty('horaires_fin');

        $validator
            ->dateTime('horaires_retour')
            ->notEmpty('horaires_retour');

        $validator
            ->decimal('duree')
            ->allowEmpty('duree');

        $validator
            ->scalar('remarques')
            ->maxLength('remarques', 4294967295)
            ->allowEmpty('remarques');

        $validator
            ->decimal('remise')
            ->allowEmpty('remise');
			
        $validator
            ->decimal('cout_personnel')
            ->allowEmpty('cout_personnel');

        $validator
            ->decimal('cout_kilometres')
            ->allowEmpty('cout_kilometres');

        $validator
            ->decimal('cout_repas')
            ->allowEmpty('cout_repas');

        $validator
            ->decimal('cout_remise')
            ->allowEmpty('cout_remise');
			
        $validator
            ->decimal('cout_economie')
            ->allowEmpty('cout_economie');
			
        $validator
            ->decimal('repartition_antenne')
            ->allowEmpty('repartition_antenne');

        $validator
            ->decimal('repartition_adpc')
            ->allowEmpty('repartition_adpc');

        $validator
            ->integer('repas_matin')
            ->allowEmpty('repas_matin')
			->nonNegativeInteger('repas_matin');

        $validator
            ->integer('repas_midi')
            ->allowEmpty('repas_midi')
			->nonNegativeInteger('repas_midi');

        $validator
            ->integer('repas_soir')
            ->allowEmpty('repas_soir')
			->nonNegativeInteger('repas_soir');

        $validator
            ->boolean('repas_charge')
			->allowEmpty('repas_charge');
            //->requirePresence('repas_charge', 'create')
            //->notEmpty('repas_charge');

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
        $rules->add($rules->existsIn(['dispositif_id'], 'Dispositifs'));

        return $rules;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function calculs(&$data = [])
    {

		$merge['horaire'] = 0;
		$merge['km'] = 0;
		$merge['repas'] = 0;
		$merge['repartition'] = 0;

		$parametre = TableRegistry::get('ConfigParametres');
		
		$parametres = $parametre->find('all')->last();
		
		$taux['horaire'] = $parametres->cout_personnel;
		$taux['km'] = $parametres->cout_kilometres;
		$taux['repas'] = $parametres->cout_repas;
		$taux['repartition'] = $parametres->pourcentage;
		
		$taux = array_merge( $merge , $taux );

		if( ! empty( $data ) && ! empty( $taux ) ){
		
			if(is_object($data['horaires_convocation'])){
				$start 	= $data['horaires_convocation']->toUnixString();
				$place 	= $data['horaires_place']->toUnixString();
				$depart = $data['horaires_fin']->toUnixString();
				$end 	= $data['horaires_retour']->toUnixString();
			} else {
				$start 	= strtotime($data['horaires_convocation']);
				$place 	= strtotime($data['horaires_place']);
				$depart	= strtotime($data['horaires_fin']);
				$end 	= strtotime($data['horaires_retour']);			
			}
	
			$data['duree']  = ( $end - $start ) / 3600; 
			$data['cout_personnel']  = ((int) $data['effectif'] ) * ( ( ( ( $end - $start )  ) / 3600 ) + 1 ) * $taux['horaire']; 
			$data['cout_kilometres']  = ((int) $data['vehicules_km'] ) * ((int) $data['vehicule_trajets'] ) * $taux['km'];
			$data['cout_repas']  = ((int) $data['repas_matin'] + (int) $data['repas_midi']  + (int) $data['repas_soir'] ) * $taux['repas'] * (int) $data['repas_charge'];
			$data['cout_remise']  = ( (int) $data['cout_personnel'] + (int) $data['cout_kilometres'] + (int) $data['cout_repas'] ) * ( 100 - (int) $data['remise'] ) / 100;
			$data['cout_economie']  = ( (int) $data['cout_personnel'] + (int) $data['cout_kilometres'] + (int) $data['cout_repas'] ) * ( (int) $data['remise'] ) / 100;
			$data['repartition_antenne']  = ( (int) $data['cout_remise'] ) * ( 100 - $taux['repartition'] ) / 100;
			$data['repartition_adpc']  = ( (int) $data['cout_remise'] ) * $taux['repartition'] / 100; 
			$data['lot_a'] = (int) $data['lot_a'];
			$data['lot_b'] = (int) $data['lot_b'];
			$data['lot_c'] = (int) $data['lot_c'];
			$data['effectif'] = (int) $data['effectif'];
			$data['position'] = isset($data['position']) ? $data['position'] : 'A l\'adresse indiquée';
				
		}
		
		return $data;
    }
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function preset( $id = 0 , $effectif = 0 , $indicatif = '', $auto = false , $consignes = '' , $dates = [] )
    {
		
		$id = (int) $id;
		$effectif = (int) $effectif;
		
		if(empty($id)){
			return false;
		}
		
		$dispositif = $this->Dispositifs->get($id, [
            'contain' => ['Dimensionnements']
        ]);

		if(empty($dispositif)){
			return false;
		}
		
		$lot_a = 0;
		$lot_b = 0;
		$lot_c = 0;
		
		if( empty($effectif)){
			if($dispositif->personnels_total == 2){
				$effectif = 2;
				$lot_b = 1;
			} else {
				$effectif = 4;
				$lot_a = 1;
			}
		}
		
		if($auto){
			switch($effectif){
				case 1:
					$indicatif = 'Chef '.$indicatif;
					break;
				case 2:
					$indicatif = 'Binôme '.$indicatif;
					break;
				default:
					$indicatif = 'Equipe '.$indicatif;
					break;				
			}
		}
		
		$data = [];

		$data['dispositif_id'] = $id;
		$data['indicatif'] = $indicatif;
		
		$data['horaires_convocation'] = $dispositif->dimensionnement->horaires_debut->modify('-2 hours')->format('Y-m-d H:i:s');
		$data['horaires_place'] = $dispositif->dimensionnement->horaires_debut->modify('-1 hours')->format('Y-m-d H:i:s');
		$data['horaires_fin'] = $dispositif->dimensionnement->horaires_fin->modify('+0 hours')->format('Y-m-d H:i:s');
		$data['horaires_retour'] = $dispositif->dimensionnement->horaires_fin->modify('+1 hours')->format('Y-m-d H:i:s');
			
		if(!empty($dates)){
			$data = array_merge($data,$dates);
		}
		
		$data['vehicule_type'] = ($effectif == 4) ? '1 VPSP' : '1 VL';
		$data['vehicules_km'] = 50;
		$data['vehicule_trajets'] = 2;
		$data['effectif'] = (int) $effectif;
		$data['lot_a'] = (int) $lot_a;
		$data['lot_b'] = (int) $lot_b;
		$data['lot_c'] = (int) $lot_c;
		$data['remise'] = 0;
		$data['repas_charge'] = 1;
		$data['consignes'] = $consignes;

		$start 	= strtotime($data['horaires_convocation']);
		$end 	= strtotime($data['horaires_retour']);
		
		$data['duree']  = ( $end - $start ) / 3600; 
			
		$data = $this->repas($data['horaires_convocation'],$data['horaires_retour'],$data['effectif'],$data);

		return $this->calculs($data);
		
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function repas($start=0,$end=0,$effectif=0,$data=[])
    {

		$start 	= strtotime($start);
		$end 	= strtotime($end);
			
		$debut = (int) date('H',$start);
		$debut = $debut - 1;
		
		$calcul = [];
		
		$data['repas_matin'] = 0;
		$data['repas_midi'] = 0;
		$data['repas_soir'] = 0;
		
		$ranges = range($start,$end,3600);
		
		foreach($ranges as &$range){
			$debut++;
			if($debut>23){
				$debut = 0;
			}
			$range = $debut;
			if($debut == 8){
				$data['repas_matin']++;
			}
			if($debut == 12){
				$data['repas_midi']++;
			}
			if($debut == 19){
				$data['repas_soir']++;
			}				
		}

		$data['repas_matin'] = $effectif * $data['repas_matin'];
		$data['repas_midi'] = $effectif * $data['repas_midi'];
		$data['repas_soir'] = $effectif * $data['repas_soir'];
		
		return $data;
		
	}

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function timing( $start = false , $end = false )
    {
		if(is_object($start)){
			$start = $start->modify('-1 hours')->toUnixString();
		} else {
			$start = strtotime($start)-3600;
		}
		
		if(is_object($end)){
			$end = $end->modify('+0 hours')->toUnixString();
		} else {
			$end = strtotime($end);
		}
		
		$duree = ($end - $start)/3600;
			
		$dates = [];
		
		if($duree > 14){
			
			$times = [7,8,9,10,11,12];
			
			$mini = [];
			
			foreach($times as $time){
				$mini[$time] = $duree%$time;
			}
			
			asort($mini);
			
			$min = min($mini);
			$max = max($mini);
			
			$active = empty($min) ? $min : $max;

			$null = array_keys($mini,$active);

			arsort($null);
			
			$null = array_shift($null);

			$firsts = ( $duree - $active ) / $null;
			
			$tmp_start = $start;
			$tmp_end = $start + ($null * 3600);
			
			for ($i = 0; $i < $firsts; $i++) {
				if(!empty($i)){
					$tmp_start += ($null * 3600);
					$tmp_end += ($null * 3600);
				}
				$dates[] = [
					'horaires_convocation' => date('Y-m-d H:i:s',$tmp_start-3600),
					'horaires_place' => date('Y-m-d H:i:s',$tmp_start),
					'horaires_fin' => date('Y-m-d H:i:s',$tmp_end),
					'horaires_retour' => date('Y-m-d H:i:s',$tmp_end+3600)
					];
			}
		
			if(!empty($active)){
				$tmp_start += ($null * 3600);
				$tmp_end += ($active * 3600);
				$dates[] = [
					'horaires_convocation' => date('Y-m-d H:i:s',$tmp_start-3600),
					'horaires_place' => date('Y-m-d H:i:s',$tmp_start),
					'horaires_fin' => date('Y-m-d H:i:s',$tmp_end),
					'horaires_retour' => date('Y-m-d H:i:s',$tmp_end+3600)
					];
			}
		} else {
			$dates[] = [
					'horaires_convocation' => date('Y-m-d H:i:s',$start-3600),
					'horaires_place' => date('Y-m-d H:i:s',$start),
					'horaires_fin' => date('Y-m-d H:i:s',$end),
					'horaires_retour' => date('Y-m-d H:i:s',$end+3600)
					];
		}

		return $dates;
		
	}

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function effectifs($dates=[],$acteurs=[],$publics=[],$dispositif=[],$indicatif=0)
    {
		$compteur = [];
		$output = [];
		
		$dates = (array) $dates;

		$effectifs = Hash::extract($dispositif,'equipes.{n}.effectif');
		$exists = Hash::extract($dispositif,'equipes.{n}.strtotime_place');
		$equipe = Hash::get($dispositif,'equipes');
		
		$combine = $result = Hash::combine(
			$equipe,
			'{n}.id',
			['%s:%s:%s', '{n}.strtotime_place', '{n}.effectif','{n}.indicatif']
		);
		
		$limit = 0;
		
		if(!empty($publics)){
			$limit += count($publics); 
		}
		if(!empty($acteurs)){
			$limit += count($acteurs); 
		}
		$counts = array_count_values( $effectifs );

		$equipes = 0;
		$compteur = 0;
		
		foreach($dates as $date){
			if(!empty($publics)){
				foreach($publics as $public){
					$compteur += $public;
				}
			}
			if(!empty($acteurs)){
				foreach($acteurs as $acteur){
					$compteur += $acteur;
				}
			}
		}
		
		if(array_sum($effectifs)<$compteur){
	
			if(!empty($publics)){
				foreach($publics as $public){
					$preset = [];
					$equipes++;
					$indicatifs = $indicatif.$equipes;
					foreach($dates as $date){

						$strtotime = strtotime($date['horaires_place']);
						if($counts[$strtotime] < $limit){
							$preset = $this->preset( $dispositif->id, $public , $indicatifs , true , 'Attaché au dispositif dédié au public' , $date );

							if(!in_array(strtotime($preset['horaires_place']).':'.$preset['effectif'].':'.$preset['indicatif'],$combine)){
								$this->generateSaveAlone($preset);
							}
						}
					}
				}
			}
			
			if(!empty($acteurs)){
				foreach($acteurs as $acteur){
					$preset = [];
					$equipes++;
					$indicatifs = $indicatif.$equipes;
					foreach($dates as $date){

						$strtotime = strtotime($date['horaires_place']);
						if($counts[$strtotime] < $limit){
							$preset = $this->preset( $dispositif->id, $acteur , $indicatifs , true , 'Attaché au dispositif dédié aux acteurs' , $date );

							if(!in_array(strtotime($preset['horaires_place']).':'.$preset['effectif'].':'.$preset['indicatif'],$combine)){
								$this->generateSaveAlone($preset);
							}
						}
		
					}
				}
			}
		}
		//$output['compteur'] = array_sum($compteur);

		//return $output;
		
	}
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function equipes( $equipiers = 0 )
    {
		$equipiers = (int) $equipiers;
		
		if(empty($equipiers)){
			return false;
		}

		$equipes = [];
		// Modulo 4
		$modulo4 = $equipiers%4;
		$count4 = ($equipiers - $modulo4)/4;
		
		for ($i = 1; $i <= $count4; $i++) {
			$equipes[] = 4;
		}

		// Modulo 2
		$modulo2 = $modulo4%2;
		$count2 = ($modulo4 - $modulo2)/2;

		for ($i = 1; $i <= $count2; $i++) {
			$equipes[] = 2;
		}

		// Modulo 2
		$count1 = $modulo2;
		
		if( ! empty($count1)){
			$equipes[] = 1;
		}
		
		return $equipes;
		
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
        $dispositifs = $this->Dispositifs->find('all', [
            'contain' => ['Dimensionnements','Equipes'=>[
				'sort' => ['Equipes.horaires_place' => 'ASC']]
			]
        ])->where(['Dimensionnements.demande_id'=>$id])->toArray();

		$indicatif = 0;
		
		foreach($dispositifs as $dispositif){

			$dates = $this->timing($dispositif->dimensionnement->horaires_debut,$dispositif->dimensionnement->horaires_fin);

			$exists = Hash::extract($dispositif,'equipes.{n}.effectif');
			$count = count($exists);
			$exists = array_sum($exists);

			$indicatif++;
			
			$publics = $this->equipes($dispositif->personnels_public);
			$acteurs = $this->equipes($dispositif->personnels_acteurs);

			if(empty($exists)){

				$equipes = 0;
				
				$equipes = $this->generateBoucle( $publics , $dispositif->id , $equipes , $indicatif , 'Attaché au dispositif dédié au public' , $dates );
				$equipes = $this->generateBoucle( $acteurs , $dispositif->id , $equipes , $indicatif , 'Attaché au dispositif dédié aux acteurs' , $dates );

			} else {
				
				$this->effectifs($dates,$publics,$acteurs,$dispositif,$indicatif);
				
				//$teams = $teams * (count($publics)+count($acteurs));
/*
				$exist = Hash::extract($dispositif,'equipes.{n}.strtotime_place');
			
				
			
				if($exists < $compteur){
					
					$ecart = $compteur - $exists;
*/					
					//$generiques = $this->equipes($ecart);
					
					//$equipes = $count;
					
					//$equipes = $this->generateBoucle( $generiques , $dispositif->id , $equipes , $indicatif , '' , $dates );

				//}
			}
		}
	
	}

	/**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function generateBoucle( $boucles = [] , $id = 0 , $equipes = 0 , $indicatif = '' , $comment = '' , $dates = [] )
    {
		
		$dates = (array) $dates;
		
		if($boucles){
			foreach($boucles as $boucle){
				$preset = [];
				$equipes++;
				$indicatifs = $indicatif.$equipes;

				foreach($dates as $date){
					$preset = $this->preset( $id, $boucle , $indicatifs , true , $comment , $date );
					$this->generateSaveAlone($preset);							
				}
			}
		}
		
		return $equipes;
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
        $equipe = $this->newEntity();
		
		$equipe = $this->patchEntity($equipe, $preset);

		$this->calculs( $equipe );

		Log::write('debug', $equipe->getErrors());

		if ($this->save($equipe)) {
			return true;
		} 
		
		return $equipe->getErrors();

	}
	
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function dossierComplet($id = 0)
    {
		$dispositifs = $this->Dispositifs->find('all', [
			'contain' => ['Dimensionnements']
		])->where(['Dimensionnements.demande_id'=>$id])->toArray();

		if( ! $dispositifs ){
			return false;
		}
		
		$out = true;
		
		foreach($dispositifs as $dispositif){
			
			$exists = Hash::extract($dispositif,'equipes.{n}.effectif');
			$count = count($exists);
			$exists = array_sum($exists);
			
			$out = empty($count) ? false : $out;
			$out = ($exists < $dispositif->personnels_total) ? false : $out;
			
		}
		
		return $out;
		
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
		$dispositifs = $this->Dispositifs->find('all', [
			'contain' => ['Dimensionnements']
		])->where(['Dimensionnements.demande_id'=>$id])->toArray();

		if( ! $dispositifs ){
			return false;
		}
		
		$exists = Hash::extract($dispositifs,'{n}.id');
		
		if($exists){
			$deletes = $this->find('all')->where(['dispositif_id IN'=>(array)$exists])->toArray();
			foreach($deletes as $delete){
				$tmp = $this->get($delete->id);
				$old = $this->delete($tmp);
			}
		}
		
	}

	/**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function getDistinctStrtotime()
    {
		
		$query = $this->find('all',[
			'contain' => ['Dispositifs.Dimensionnements.Demandes.ConfigEtats'],
		])
		->where(['ConfigEtats.ordre >='=>1,'ConfigEtats.ordre <='=>5]);

		$concat = $query->func()->concat(['UNIX_TIMESTAMP(Equipes.horaires_place)'=>'identifier','UNIX_TIMESTAMP(Equipes.horaires_fin)'=>'identifier']);
		$somme = $query->func()->sum('Equipes.effectif');
		
		$query->select(['strtotime_groupby' => $concat])
		->select(['effectif_groupby' => $somme])
		->select($this)
		->select($this->Dispositifs)
		->select($this->Dispositifs->Dimensionnements)
		->select($this->Dispositifs->Dimensionnements->Demandes)
		->select($this->Dispositifs->Dimensionnements->Demandes->ConfigEtats)
		->group('strtotime_groupby')
		->contain(['Dispositifs.Dimensionnements.Demandes.ConfigEtats'])
		->order(['horaires_place'=>'asc']);
		
		//$results = Hash::sort($query->toArray(),'{n}.strtotime_convocation','asc');
		
		//foreach(){
			
		return $query->toArray();
	}
}

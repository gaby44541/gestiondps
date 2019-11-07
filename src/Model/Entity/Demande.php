<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Cake\Log\Log;


/**
 * Demande Entity
 *
 * @property int $id
 * @property string $manifestation
 * @property int $organisateur_id
 * @property string $representant
 * @property string $representant_fonction
 * @property int $config_etat_id
 * @property int $antenne_id
 * @property string $gestionnaire_nom
 * @property string $gestionnaire_mail
 * @property string $gestionnaire_telephone
 * @property int $remise_pourcent
 * @property string $remise_justification
 *
 * @property \App\Model\Entity\Organisateur $organisateur
 * @property \App\Model\Entity\ConfigEtat $config_etat
 * @property \App\Model\Entity\Antenne $antenne
 * @property \App\Model\Entity\Dimensionnement[] $dimensionnements
 */
class Demande extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'manifestation' => true,
        'organisateur_id' => true,
        'representant' => true,
        'representant_fonction' => true,
        'config_etat_id' => true,
        'antenne_id' => true,
        'gestionnaire_nom' => true,
        'gestionnaire_mail' => true,
        'gestionnaire_telephone' => true,
        'remise_justification' => true,
		'chronologie' => true,
        'organisateur' => true,
        'config_etat' => true,
        'antenne' => true,
        'dimensionnements' => true
    ];

	protected $_virtual = [
		'alerts',
		'dates_limits',
		'somme_facturee'
	];

	protected function _getDatesLimits()
    {
		$limits = false;

		if(isset($this->_properties['dimensionnements'])){

			$limits = [];

			$limits['min'] = false;
			$limits['max'] = false;

			$limits['min'] = Hash::extract($this->_properties['dimensionnements'],'{n}.strtotime_debut');
			$limits['max'] = Hash::extract($this->_properties['dimensionnements'],'{n}.strtotime_fin');

			$equipes = Hash::extract($this->_properties['dimensionnements'],'{n}.dispositif.equipes');

			$limits['convocation'] = Hash::extract($equipes,'{n}.{n}.strtotime_convocation');
			$limits['retour'] = Hash::extract($equipes,'{n}.{n}.strtotime_retour');

			$merge = array_merge($limits['min'],$limits['max'],$limits['convocation'],$limits['retour']);

			sort($merge);

			$limits = [];

			$limits['min'] = reset($merge);
			$limits['max'] = end($merge);
			$limits['round_min'] = strtotime(date('Y-m-d 00:00:00',$limits['min']));
			$limits['round_max'] = strtotime(date('Y-m-d 24:00:00',$limits['max']));

		}

		return $limits;
	}

	protected function _getAlerts()
    {
		if(isset($this->_properties['dimensionnements'])){

			$alert = [12];

			//$dates = Hash::sort($this->_properties['dimensionnements'],'{n}.horaires_debut','asc');
			$dates = Hash::extract($this->_properties['dimensionnements'],'0.horaires_debut');

			if(!empty($dates)){
				$date = $dates[0];
				if($date->wasWithinLast('6 months')){
					$alert[] = 6;
				}
				if($date->isWithinNext('2 weeks')){
					$alert[] = 0;
				}
				if($date->isWithinNext('1 months')){
					$alert[] = 1;
				}
				if($date->isWithinNext('3 months')){
					$alert[] = 3;
				}
				if($date->isWithinNext('4 months')){
					$alert[] = 4;
				}
				if($date->isWithinNext('12 months')){
					$alert[] = 12;
				}
			}

			return min($alert);
		} else {
			return 0;
		}
    }
	protected function _getSommeFacturee()
    {/*
        Log::write('debug','getSommeFacturee');
		if(isset($this->_properties['dimensionnements'])){
            $coutPersonnel = Hash::extract($this->_properties['dimensionnements'],'{n}.dispositif.cout_personnel');
            $coutPersonnel = array_sum($coutPersonnel);
            Log::write('debug','$coutPersonnel = '.$coutPersonnel);

            $coutKilometres = Hash::extract($this->_properties['dimensionnements'],'{n}.dispositif.cout_kilometres');
            $coutKilometres = array_sum($coutKilometres);
            Log::write('debug','Demande - $coutKilometres = '.$coutKilometres);

            //Le coût de chaque équipe comprend le coût des véhicules et du matériel.
            $coutEquipes = Hash::extract($this->_properties['dimensionnements'],'{n}.dispositif.equipes.{n}.cout_remise');
            $coutEquipes = array_sum($coutEquipes);
			//$somme = Hash::extract($this->_properties['dimensionnements'],'{n}.dispositif.equipes.{n}.cout_remise');
			//$somme = array_sum($somme);

             //TODO : Ajouter les autres couts (véhicules + matériels + ...)
			$somme = $coutPersonnel + $coutKilometres;
            Log::write('debug','Demande - Somme total = '.$somme);

			return $somme;
		} else {
			return 0;
		}*/
		return 1664.51;
    }
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * Dimensionnement Entity
 *
 * @property int $id
 * @property int $demande_id
 * @property string $intitule
 * @property \Cake\I18n\FrozenTime $horaires_debut
 * @property \Cake\I18n\FrozenTime $horaires_fin
 * @property string $lieu_manifestation
 * @property string $risques_particuliers
 * @property string $contact_portable
 * @property string $contact_present
 * @property string $contact_fonction
 * @property string $contact_telephone
 * @property int $public_effectif
 * @property string $public_age
 * @property int $acteurs_effectif
 * @property string $acteurs_age
 * @property string $assis
 * @property bool $circuit
 * @property bool $ouvert
 * @property float $superficie
 * @property int $distance_maxi
 * @property string $acces
 * @property string $besoins_particuliers
 * @property string $pompier
 * @property int $pompier_delai
 * @property string $hopital
 * @property int $hopital_delai
 * @property bool $arrete
 * @property bool $commission
 * @property bool $plans
 * @property bool $annuaire
 * @property bool $documents_autres
 * @property string $medecin
 * @property string $medecin_telephone
 * @property string $infirmier
 * @property string $kiné
 * @property string $medecin_autres
 * @property bool $smur
 * @property bool $sp
 * @property bool $police
 * @property bool $gendarmerie
 * @property string $ambulancier
 * @property string $ambulancier_telephone
 * @property string $autres_public
 * @property string $autres
 *
 * @property \App\Model\Entity\Demande $demande
 * @property \App\Model\Entity\Dispositif $dispositif
 */
class Dimensionnement extends Entity
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
        'demande_id' => true,
        'intitule' => true,
        'horaires_debut' => true,
        'horaires_fin' => true,
        'lieu_manifestation' => true,
        'risques_particuliers' => true,
        'contact_portable' => true,
        'contact_present' => true,
        'contact_fonction' => true,
        'contact_telephone' => true,
        'public_effectif' => true,
        'public_age' => true,
        'acteurs_effectif' => true,
        'acteurs_age' => true,
        'assis' => true,
        'circuit' => true,
        'ouvert' => true,
        'superficie' => true,
        'distance_maxi' => true,
        'acces' => true,
        'besoins_particuliers' => true,
        'pompier' => true,
        'pompier_delai' => true,
        'hopital' => true,
        'hopital_delai' => true,
        'medecin' => true,
        'medecin_telephone' => true,
        'infirmier' => true,
        'kiné' => true,
        'medecin_autres' => true,
		'secours_presents' => true,
		'documents_officiels' => true,
        'ambulancier' => true,
        'ambulancier_telephone' => true,
        'autres_public' => true,
        'autres' => true,
        'demande' => true,
        'dispositif' => true
    ];

	protected $_virtual = [
		'du_au',
		'contact_full',
		'strtotime_debut',
		'strtotime_fin',
		'calendar',
		'arrays',
		'environnements',
		'nb_heures'
	];
    protected function _getCalendar()
    {
		$array = [];

		$array['id'] = $this->_properties['id'];
		$array['title'] = $this->_properties['intitule'];

		$array['url'] = Router::url(['controller'=>'demandes','action'=>'dispatch',$this->_properties['demande_id']]);

		if( isset($this->_properties['demande']['config_etat']['ordre'])){
			$ordre = (int) $this->_properties['demande']['config_etat']['ordre'];
		} else {
			$ordre = false;
		}

		switch($ordre):
			case 0:
			case 1:
			case 2:
			case 3:
			case 4:
				$array['color'] = 'red';
				break;
			case 5:
			case 6:
				$array['color'] = 'orange';
				break;
			case 7:
			case 8:
				$array['color'] = 'YellowGreen';
				break;
			case 9:
			case 10:
				$array['color'] = 'LightBlue';
				break;
			default:
				$array['color'] = 'Gainsboro';
				break;
		endswitch;

		$start = is_object( $this->_properties['horaires_debut'] ) ? $this->_properties['horaires_debut']->toUnixString() : strtotime($this->_properties['horaires_debut']);
		$end = is_object( $this->_properties['horaires_fin'] ) ? $this->_properties['horaires_fin']->toUnixString() : strtotime($this->_properties['horaires_fin']);

        $array['start'] = date('Y-m-d H:i:s',$start);
		$array['end'] = date('Y-m-d H:i:s',$end);

		return $array;

	}

    protected function _getStrtotimeDebut()
    {
        return is_object( $this->_properties['horaires_debut'] ) ? $this->_properties['horaires_debut']->toUnixString() : strtotime($this->_properties['horaires_debut']);
    }
   protected function _getStrtotimeFin()
    {
        return is_object( $this->_properties['horaires_fin'] ) ? $this->_properties['horaires_fin']->toUnixString() : strtotime($this->_properties['horaires_fin']);
    }
    protected function _getDuAu()
    {
		$start = is_object( $this->_properties['horaires_debut']) ? $this->_properties['horaires_debut']->i18nFormat(\IntlDateFormatter::FULL) :  $this->_properties['horaires_debut'];
		$end = is_object( $this->_properties['horaires_fin']) ? $this->_properties['horaires_fin']->i18nFormat(\IntlDateFormatter::FULL) :  $this->_properties['horaires_fin'];

        return 'du '.$start.' au '.$end;
    }
    protected function _getContactFull()
    {
        return $this->_properties['contact_present'].' - '.$this->_properties['contact_fonction'].' ('.$this->_properties['contact_portable'].' - '.$this->_properties['contact_telephone'].')';
    }

	protected function _getArrays(){

		return [
			'assis' => $this->arrayExplode('assis'),
			'acces' => $this->arrayExplode('acces'),
			'secours_presents' => $this->arrayExplode('secours_presents'),
			'documents_officiels' => $this->arrayExplode('documents_officiels')
		];
    }
	protected function _getEnvironnements(){
		$environnements = [];
		$environnements['facile'] = ['ville','rue','bâtiment','salle','facile','dégagé'];
		$environnements['moyen'] = ['gradin','tribune','chapiteau'];
		$environnements['difficile'] = ['difficile','pente','champ','ville'];
		$environnements['très difficile'] = ['public','talu','escalier','chemin','forêt','accidenté'];

		$output = [];

		foreach($environnements as $key => $environnement){
			foreach($environnement as $item){
				$output[$key][$item] = ucfirst($item);
			}
		}

		return $output;
	}

    protected function _getNbHeures(){
        $debut = $this->_properties['horaires_debut'];
        $fin = $this->_properties['horaires_fin'];
        $hours = $fin->diff($debut)->h + $fin->diff($debut)->days*24;
        return $hours;
    }


	protected function arrayExplode($array = '')
	{
		if(isset($this->_properties[$array])){

			if(!is_array($this->_properties[$array])){
				return explode(',',$this->_properties[$array]);
			}

			return $this->_properties[$array];
		}

		return [];

	}

}


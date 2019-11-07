<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Equipe Entity
 *
 * @property int $id
 * @property int $dispositif_id
 * @property string $indicatif
 * @property int $effectif
 * @property string $vehicule_type
 * @property int $vehicules_km
 * @property int $vehicule_trajets
 * @property int $lot_a
 * @property int $lot_b
 * @property int $lot_c
 * @property string $autre
 * @property string $consignes
 * @property \Cake\I18n\FrozenTime $horaires_convocation
 * @property \Cake\I18n\FrozenTime $horaires_place
 * @property \Cake\I18n\FrozenTime $horaires_fin
 * @property \Cake\I18n\FrozenTime $horaires_retour
 * @property int $duree
 * @property string $remarques
 * @property float $cout_personnel
 * @property float $cout_kilometres
 * @property float $cout_repas
 * @property float $cout_remise
 * @property float $repartition_antenne
 * @property float $repartition_adpc
 * @property int $repas_matin
 * @property int $repas_midi
 * @property int $repas_soir
 * @property bool $repas_charge
 *
 * @property \App\Model\Entity\Dispositif $dispositif
 * @property \App\Model\Entity\Personnel[] $personnels
 */
class Equipe extends Entity
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
        'dispositif_id' => true,
        'indicatif' => true,
        'effectif' => true,
        'autre' => true,
        'consignes' => true,
		'position' => true,
        'horaires_convocation' => true,
        'horaires_place' => true,
        'horaires_fin' => true,
        'horaires_retour' => true,
        'duree' => true,
        'remarques' => true,
        'dispositif' => true,
        'personnels' => true
    ];

	protected $_virtual = [
		'du_au',
		'du_au_light',
		'depart_retour',
		'durees',
		'duree_aller',
		'duree_poste',
		'duree_retour',
		'strtotime_convocation',
		'strtotime_place',
		'strtotime_fin',
		'strtotime_retour',
		'strtotime_distinct'
	];

    protected function _getDuAuLight()
    {
		$start = is_object( $this->_properties['horaires_place']) ? $this->_properties['horaires_place']->i18nFormat('dd/MM/YYYY HH:mm') :  $this->_properties['horaires_place'];
		$end = is_object( $this->_properties['horaires_fin']) ? $this->_properties['horaires_fin']->i18nFormat('dd/MM/YYYY HH:mm') :  $this->_properties['horaires_fin'];

        return 'du '.$start.' au '.$end;
    }
    protected function _getDuAu()
    {
		$start = is_object( $this->_properties['horaires_place']) ? $this->_properties['horaires_place']->i18nFormat(\IntlDateFormatter::FULL) :  $this->_properties['horaires_place'];
		$end = is_object( $this->_properties['horaires_fin']) ? $this->_properties['horaires_fin']->i18nFormat(\IntlDateFormatter::FULL) :  $this->_properties['horaires_fin'];

        return 'du '.$start.' au '.$end;
    }
    protected function _getDepartRetour()
    {
		$start = is_object( $this->_properties['horaires_convocation']) ? $this->_properties['horaires_convocation']->i18nFormat(\IntlDateFormatter::FULL) :  $this->_properties['horaires_convocation'];
		$end = is_object( $this->_properties['horaires_retour']) ? $this->_properties['horaires_retour']->i18nFormat(\IntlDateFormatter::FULL) :  $this->_properties['horaires_retour'];

        return 'départ du point de rendez-vous à '.$start.' et retour estimé à '.$end;
    }

    protected function _getDurees()
    {
		$start = $this->_properties['horaires_convocation']->toUnixString();
		$end = $this->_properties['horaires_retour']->toUnixString();

        return (($end - $start) / 3600);
    }
    protected function _getStrtotimeDistinct()
    {
        return $this->_properties['horaires_place']->toUnixString().$this->_properties['horaires_fin']->toUnixString();
    }
    protected function _getStrtotimeConvocation()
    {
        return $this->_properties['horaires_convocation']->toUnixString();
    }
    protected function _getStrtotimePlace()
    {
        return $this->_properties['horaires_place']->toUnixString();
    }
    protected function _getStrtotimeFin()
    {
        return $this->_properties['horaires_fin']->toUnixString();
    }
    protected function _getStrtotimeRetour()
    {
        return $this->_properties['horaires_retour']->toUnixString();
    }
    protected function _getDureeAller()
    {
		$start = $this->_properties['horaires_convocation']->toUnixString();
		$end = $this->_properties['horaires_place']->toUnixString();

        return $end - $start;
    }
    protected function _getDureePoste()
    {
		$start = $this->_properties['horaires_place']->toUnixString();
		$end = $this->_properties['horaires_fin']->toUnixString();

        return $end - $start;
    }
    protected function _getDureeRetour()
    {
		$start = $this->_properties['horaires_fin']->toUnixString();
		$end = $this->_properties['horaires_retour']->toUnixString();

        return $end - $start;
    }
}

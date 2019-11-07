<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Text;

/**
 * Organisateur Entity
 *
 * @property int $id
 * @property string $uuid
 * @property int $raison_sociale
 * @property string $nom
 * @property string $fonction
 * @property string $adresse
 * @property string $adresse_suite
 * @property string $code_postal
 * @property string $ville
 * @property string $telephone
 * @property string $portable
 * @property string $mail
 * @property string $representant_prenom
 * @property string $representant_nom
 * @property string $tresorier_nom
 * @property string $tresorier_prenom
 * @property string $tresorier_mail
 * @property string $tresorier_telephone
 * @property bool $publish
 *
 * @property \App\Model\Entity\Demande[] $demandes
 */
class Organisateur extends Entity
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
        'uuid' => true,
        'raison_sociale' => true,
        'nom' => true,
        'fonction' => true,
        'adresse' => true,
        'adresse_suite' => true,
        'code_postal' => true,
        'ville' => true,
        'telephone' => true,
        'portable' => true,
        'mail' => true,
        'representant_prenom' => true,
        'representant_nom' => true,

        'publish' => true,
        'demandes' => true
    ];

	protected $_virtual = [
		'representant_fonction',
		'representant',
		'tresorier',
		'cp_ville',
		'bloc_adresse'
	];
	// Coordonnées
	protected function _getMail()
    {
        return $this->strmail($this->_properties['mail']);
    }
	protected function _getVille()
    {
        return $this->strcompose($this->_properties['ville']);
    }
	protected function _getTelephone()
    {
        return str_replace(' ','',$this->_properties['telephone']);
    }
	protected function _getPortable()
    {
        return str_replace(' ','',$this->_properties['portable']);
    }
    protected function _getBlocAdresse()
    {
		$array[] = $this->_properties['nom'];
		$array[] = $this->_properties['adresse'];
		$array[] = $this->_properties['adresse_suite'];
		$array[] = $this->strcompose($this->_properties['code_postal'].' '.$this->_properties['ville']);

        return implode(PHP_EOL,$array).PHP_EOL;
    }
    protected function _getCpVille()
    {
        return $this->strcompose($this->_properties['code_postal'].' '.$this->_properties['ville']);
    }
	// Représentant
	protected function _getRepresentantNom()
    {
        return ucwords(strtolower($this->_properties['representant_nom']));
    }
	protected function _getRepresentantPrenom()
    {
        return $this->strcompose($this->_properties['representant_prenom']);
    }
    protected function _getRepresentant()
    {
        return $this->_properties['representant_nom'].' '.$this->_properties['representant_prenom'];
    }
    protected function _getRepresentantFonction()
    {
        return $this->strcompose($this->_properties['representant_nom'].' '.$this->_properties['representant_prenom']).' - '.$this->_properties['fonction'];
    }
	// Functions
	protected function strmail($mail = '')
	{
		$mail = strtr( $mail,
						'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ:',
						'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy-'
					);
		return strtolower($mail);
	}
	protected function strcompose($compose = '')
	{
		return str_replace(' - ','-',ucwords(strtolower(str_replace('-',' - ',$compose))));
	}
}

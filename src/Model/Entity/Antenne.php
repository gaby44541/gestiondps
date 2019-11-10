<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Antenne Entity
 *
 * @property int $id
 * @property string $antenne
 * @property string $adresse
 * @property string $adresse_suite
 * @property string $code_postal
 * @property string $ville
 * @property string $telephone
 * @property string $portable
 * @property string $mail
 * @property string $fax
 * @property string $rib_etablissemnt
 * @property string $rib_guichet
 * @property string $rib_compte
 * @property string $rib_rice
 * @property string $rib_domicile
 * @property string $rib_bic
 * @property string $rib_iban
 * @property string $cheque
 * @property string $etat
 *
 * @property \App\Model\Entity\Personnel[] $personnels
 */
class Antenne extends Entity
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
        'antenne' => true,
        'adresse' => true,
        'adresse_suite' => true,
        'code_postal' => true,
        'ville' => true,
        'telephone' => true,
        'portable' => true,
        'mail' => true,
        'fax' => true,
        'rib_etablissemnt' => true,
        'rib_guichet' => true,
        'rib_compte' => true,
        'rib_rice' => true,
        'rib_domicile' => true,
        'rib_bic' => true,
        'rib_iban' => true,
        'cheque' => true,
        'etat' => true,
        'personnels' => true,
		'technique_nom' => true,
		'technique_mail' => true,
		'tresorier_nom' => true,
		'tresorier_mail' => true,
		'chef_secteur_nom' => true,
		'chef_secteur_mail' => true,
    ];

	protected $_virtual = [
		'cp_ville',
		'coordonnees',
		'bloc_adresse'
	];

	protected function _getBlocAdresse()
    {
		$array[] = $this->_properties['antenne'];
		$array[] = $this->_properties['adresse'];
		$array[] = $this->_properties['adresse_suite'];
		$array[] = $this->strcompose($this->_properties['code_postal'].' '.$this->_properties['ville']);

        return implode(PHP_EOL,$array).PHP_EOL;
    }

	protected function _getVille()
    {
        return str_replace(' - ','-',ucwords(strtolower(str_replace('-',' - ',$this->_properties['ville']))));
    }

	protected function _getTelephone()
    {
        return str_replace(' ','',$this->_properties['telephone']);
    }
	protected function _getPortable()
    {
        return str_replace(' ','',$this->_properties['portable']);
    }
	protected function _getFax()
    {
        return str_replace(' ','',$this->_properties['fax']);
    }
    protected function _getCpVille()
    {
        return str_replace(' - ','-',ucwords(strtolower($this->_properties['code_postal'].' '.str_replace('-',' - ',$this->_properties['ville']))));
    }
    protected function _getCoordonnees()
    {
		$array[] = $this->_properties['antenne'];
		$array[] = $this->_properties['adresse'];
		$array[] = $this->_properties['adresse_suite'];
		$array[] = str_replace(' - ','-',ucwords(strtolower($this->_properties['code_postal'].' '.str_replace('-',' - ',$this->_properties['ville']))));

        return implode(' ',$array);
    }
    protected function _getTresorierMail()
    {
		return $this->strmail($this->_properties['tresorier_mail']);
    }
    protected function _getTechniqueMail()
    {
		return $this->strmail($this->_properties['technique_mail']);
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

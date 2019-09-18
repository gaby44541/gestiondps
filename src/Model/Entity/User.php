<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher; // Ajouter cette ligne
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $nom
 * @property string $telephone
 * @property bool $externe
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Organisateur[] $organisateurs
 */
class User extends Entity
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
        'username' => true,
        'password' => true,
        'nom' => true,
        'telephone' => true,
        'externe' => true,
        'created' => true,
        'modified' => true,
        'organisateurs' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    protected function _setPassword($value)
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();

            return $hasher->hash($value);
        }
    }
	
	protected function _getNom()
    {
        return str_replace(' - ','-',ucwords(strtolower(str_replace('-',' - ',$this->_properties['nom']))));
    }
	
	protected function _getTelephone()
    {
        return str_replace(' ','',$this->_properties['telephone']);
    }
}

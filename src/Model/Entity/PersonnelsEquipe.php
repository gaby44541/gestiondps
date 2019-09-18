<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PersonnelsEquipe Entity
 *
 * @property int $id
 * @property int $equipe_id
 * @property int $personnel_id
 * @property bool $chef
 * @property bool $selection
 * @property bool $disponibilite
 *
 * @property \App\Model\Entity\Equipe $equipe
 * @property \App\Model\Entity\Personnel $personnel
 */
class PersonnelsEquipe extends Entity
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
        'equipe_id' => true,
        'personnel_id' => true,
        'chef' => true,
        'selection' => true,
        'disponbilite' => true,
        'equipe' => true,
        'personnel' => true
    ];
}

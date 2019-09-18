<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PersonnelsAntenne Entity
 *
 * @property int $id
 * @property int $antenne_id
 * @property int $personnel_id
 *
 * @property \App\Model\Entity\Antenne $antenne
 * @property \App\Model\Entity\Personnel $personnel
 */
class PersonnelsAntenne extends Entity
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
        'antenne_id' => true,
        'personnel_id' => true,
        'antenne' => true,
        'personnel' => true
    ];
}

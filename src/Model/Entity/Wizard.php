<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Wizard Entity
 *
 * @property int $id
 * @property string $uuid
 * @property int $step
 * @property string $datas
 * @property \Cake\I18n\FrozenTime $created
 */
class Wizard extends Entity
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
        'step' => true,
        'datas' => true,
        'created' => true
    ];
}

<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConfigDelai Entity
 *
 * @property int $id
 * @property float $indice
 * @property string $designation
 *
 * @property \App\Model\Entity\Dispositif[] $dispositifs
 */
class ConfigDelai extends Entity
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
        'indice' => true,
        'designation' => true,
        'dispositifs' => true
    ];
}

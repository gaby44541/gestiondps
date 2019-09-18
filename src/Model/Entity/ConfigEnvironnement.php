<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConfigEnvironnement Entity
 *
 * @property int $id
 * @property float $indice
 * @property string $environnement
 *
 * @property \App\Model\Entity\Dispositif[] $dispositifs
 */
class ConfigEnvironnement extends Entity
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
        'environnement' => true,
        'dispositifs' => true
    ];
}

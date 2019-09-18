<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConfigParametre Entity
 *
 * @property int $id
 * @property int $pourcentage
 * @property float $cout_personnel
 * @property float $cout_kilometres
 * @property float $cout_repas
 */
class ConfigParametre extends Entity
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
        'pourcentage' => true,
        'cout_personnel' => true,
        'cout_kilometres' => true,
        'cout_repas' => true
    ];
}

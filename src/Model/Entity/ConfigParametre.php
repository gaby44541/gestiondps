<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ConfigParametre Entity
 *
 * @property int $id
 * @property int $pourcentage
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
        'cout_kilometres' => true,
        'cout_repas' => true,
        'cout_vpsp' => true,
        'cout_vtu' => true,
        'cout_vtp' => true,
        'cout_quad' => true,
        'cout_lot_a' => true,
        'cout_lot_b' => true,
        'cout_lot_c' => true,
        'cout_pse2' => true,
        'cout_pse1' => true,
        'cout_lat' => true,
        'cout_medecin' => true,
        'cout_infirmier' => true,
        'cout_ce_cp' => true,
        'cout_stagiaire' => true,
        'cout_hebergement' => true,
        'cout_portatif' => true,
        'frais_gestion' => true
    ];
}

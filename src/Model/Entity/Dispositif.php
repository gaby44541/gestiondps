<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Dispositif Entity
 *
 * @property int $id
 * @property int $dimensionnement_id
 * @property string $title
 * @property string $gestionnaire_identite
 * @property string $gestionnaire_mail
 * @property string $gestionnaire_telephone
 * @property float $config_typepublic_id
 * @property int $config_environnement_id
 * @property int $config_delai_id
 * @property float $ris
 * @property string $recommandation_type
 * @property string $recommandation_poste
 * @property int $personnels_public
 * @property string $organisation_public
 * @property int $personnels_acteurs
 * @property string $organisation_acteurs
 * @property int $personnels_total
 * @property string $organisation_poste
 * @property string $organisation_transport
 * @property string $consignes_generales
 * @property int $assiste
 * @property int $leger
 * @property int $medicalise
 * @property int $evacue
 * @property string $rapport
 * @property string $accord_siege
 * @property int $remise
 *
 * @property \App\Model\Entity\Dimensionnement $dimensionnement
 * @property \App\Model\Entity\ConfigTypepublic $config_typepublic
 * @property \App\Model\Entity\ConfigEnvironnement $config_environnement
 * @property \App\Model\Entity\ConfigDelai $config_delai
 * @property \App\Model\Entity\Equipe[] $equipes
 */
class Dispositif extends Entity
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
        'dimensionnement_id' => true,
        'title' => true,
        'gestionnaire_identite' => true,
        'gestionnaire_mail' => true,
        'gestionnaire_telephone' => true,
        'config_typepublic_id' => true,
        'config_environnement_id' => true,
        'config_delai_id' => true,
        'ris' => true,
        'recommandation_type' => true,
        'recommandation_poste' => true,
        'personnels_public' => true,
        'organisation_public' => true,
        'personnels_acteurs' => true,
        'organisation_acteurs' => true,
        'personnels_total' => true,
        'organisation_poste' => true,
        'organisation_transport' => true,
        'consignes_generales' => true,
        'assiste' => true,
        'leger' => true,
        'medicalise' => true,
        'evacue' => true,
        'rapport' => true,
        'accord_siege' => true,
        'dimensionnement' => true,
        'config_typepublic' => true,
        'config_environnement' => true,
        'config_delai' => true,
        'equipes' => true
    ];
}

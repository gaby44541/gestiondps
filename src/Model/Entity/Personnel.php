<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Personnel Entity
 *
 * @property int $id
 * @property string $prenom
 * @property string $nom
 * @property string $nom_naissance
 * @property string $statut
 * @property string $rue
 * @property string $code_postal
 * @property string $ville
 * @property string $identifiant
 * @property string $portable
 * @property string $telephone
 * @property string $mail
 * @property string $antenne
 * @property string $entreprise
 * @property \Cake\I18n\FrozenDate $naissance_date
 * @property string $naissance_lieu
 * @property string $prevenir
 * @property string $prevenir_telephone
 *
 * @property \App\Model\Entity\Antenne[] $antennes
 * @property \App\Model\Entity\Equipe[] $equipes
 */
class Personnel extends Entity
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
        'prenom' => true,
        'nom' => true,
        'nom_naissance' => true,
        'statut' => true,
        'rue' => true,
        'code_postal' => true,
        'ville' => true,
        'identifiant' => true,
        'portable' => true,
        'telephone' => true,
        'mail' => true,
        'antenne' => true,
        'entreprise' => true,
        'naissance_date' => true,
        'naissance_lieu' => true,
        'prevenir' => true,
        'prevenir_telephone' => true,
        'antennes' => true,
        'equipes' => true
    ];
}

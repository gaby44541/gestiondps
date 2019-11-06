<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;


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
        'equipes' => true,
        'nb_chef_equipe' => true,
        'nb_pse2' => true,
        'nb_pse1' => true,
        'nb_lat' => true,
        'nb_medecin' => true,
        'nb_infirmier' => true,
        'nb_cadre_operationnel' => true,
        'nb_stagiaire' => true,
        'numero_coa' => true
    ];

    protected $_virtual = [
    	'cout_personnel'
    ];

    public function _getCoutPersonnel(){
		$parametre = TableRegistry::get('ConfigParametres');
		$parametres = $parametre->find('all')->last();
		/*Coût du personnel sur une base de 8h*/
		$coutPse1 = $parametres->cout_pse1;
		$coutPse2 = $parametres->cout_pse2;
		$coutCeCp = $parametres->cout_ce_cp;
		$coutLat = $parametres->cout_lat;
		$coutMedecin = $parametres->cout_medecin;
		$coutInfirmier = $parametres->cout_infirmier;
		$coutCadreOperationnel = 0;
		$coutStagiaire = $parametres->cout_stagiaire;

        /*Nombre de personnel par compétence*/
        $nbPse1 = $this->nb_pse1;
        $nbPse2 = $this->nb_pse2;
        $nbChefEquipe = $this->nb_chef_equipe;
        $nbLat = $this->nb_lat;
        $nbMedecin = $this->nb_medecin;
        $nbInfirmier = $this->nb_infirmier;
        $nbCadreOperationnel = $this->nb_cadre_operationnel;
        $nbStagiaire = $this->nb_stagiaire;

        /**/
   		$dimensionnementsTable = TableRegistry::get('Dimensionnements');
        $dimensionnement = $dimensionnementsTable->findById($this->dimensionnement_id)->first();

        /*Cout du personnel sur une base de 8h*/
        $coutPersonnel = ($coutPse1 * $nbPse1) + ($coutPse2 * $nbPse2) + ($coutCeCp * $nbChefEquipe) + ($coutLat * $nbLat) + ($coutMedecin * $nbMedecin) + ($coutInfirmier * $nbInfirmier) + ($coutCadreOperationnel * $nbCadreOperationnel) + ($coutStagiaire * $nbStagiaire);
        Log::write('debug','Dispositif.php - coutPersonnel = '.$coutPersonnel);
        $nbHeures = $dimensionnement->nb_heures;
        Log::write('debug','Dispositif.php - dimensionnement - nb heures = '.$nbHeures);

        $coutPersonnel = $coutPersonnel * $nbHeures / 8;
        Log::write('debug','Dispositif.php - coutPersonnel(2) = '.$coutPersonnel);
        return $coutPersonnel;
    }
}

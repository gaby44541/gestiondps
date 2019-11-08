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
        'personnels_acteurs' => true,
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
        'nb_vpsp' => true,
        'nb_vtu' => true,
        'nb_vtp' => true,
        'nb_quad' => true,
        'lot_a' => true,
        'lot_b' => true,
        'lot_c' => true,
        'nb_repas' => true,
        'nb_hebergement' => true,
        'nb_portatifs' => true,
        'cout_divers' => true,
        'explication_cout_divers' => true,
        'remise' => true,
        'cout_total' => true,
        'cout_total_remise' => true,
        'numero_coa' => true,
        'nb_kilometres' => true
    ];

    public $_virtual = [
        'nb_personnels_total',
        'nb_is_total',
        'cout_vehicules',
    	'cout_materiel',
    	'cout_personnel',
    	'cout_kilometres',
    	'cout_hebergement',
    	'cout_total_divers',
    	'param_frais_gestion'
    ];

    /* Nombre de personnel total */
    public function _getNbPersonnelsTotal(){
        return $this->nb_chef_equipe + $this->nb_pse2 + $this->nb_pse1 + $this->nb_lat + $this->nb_medecin + $this->nb_infirmier + $this->nb_cadre_operationnel + $this->nb_stagiaire;
    }

    /* Nombre de secouristes total.
       Permet de générer les équipes modulo 4. (sans les stagiaires, médecins, ...)*/
    public function _getNbIsTotal(){
        return $this->nb_chef_equipe + $this->nb_pse2 + $this->nb_pse1;
    }

    /*
        Calcul du coût des véhicules
     */
    public function _getCoutVehicules(){
        $parametre = TableRegistry::get('ConfigParametres');
		$parametres = $parametre->find('all')->last();
		/*Coût d'un véhicule sur un poste*/
  		$coutVPSP = $parametres->cout_vpsp;
   		$coutVTU = $parametres->cout_vtu;
   		$coutVTP = $parametres->cout_vtp;
   		$coutQuad = $parametres->cout_quad;
   		/*Nombre de véhicules par catégories*/
        $nbVPSP = $this->nb_vpsp;
        $nbVTU = $this->nb_vtu;
        $nbVTP = $this->nb_vtp;
        $nbQuad = $this->nb_quad;

        /*Cout total des véhicules pour ce poste*/
        $coutVehicules = ($coutVPSP * $nbVPSP) + ($coutVTU * $nbVTU) + ($coutVTP * $nbVTP) + ($coutQuad * $nbQuad);

        return $coutVehicules;
    }

    /*
        Calcul du coût du matériel
    */
    public function _getCoutMateriel(){
        $parametre = TableRegistry::get('ConfigParametres');
        $parametres = $parametre->find('all')->last();
        /*Coût du matériel sur un poste*/
        $coutLotA = $parametres->cout_lot_a;
        $coutLotB = $parametres->cout_lot_b;
        $coutLotC = $parametres->cout_lot_c;
        /*Nombre de matériel par catégories*/
        $nbLotA = $this->lot_a;
        $nbLotB = $this->lot_b;
        $nbLotC = $this->lot_c;

        /*Cout total du matériel pour ce poste*/
        $coutMateriel = ($coutLotA * $nbLotA) + ($coutLotB * $nbLotB) + ($coutLotC * $nbLotC);
        return $coutMateriel;
    }

    /*
        Calcul du coût personnel
     */
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

        /*Récupération du nombre total d'heures*/
        $dimensionnementsTable = TableRegistry::get('Dimensionnements');
        $dimensionnement = $dimensionnementsTable->findById($this->dimensionnement_id)->first();
        $nbHeures = $dimensionnement->nb_heures;

        /*Cout du personnel sur une base de 8h*/
        $coutPersonnel = ($coutPse1 * $nbPse1) + ($coutPse2 * $nbPse2) + ($coutCeCp * $nbChefEquipe) + ($coutLat * $nbLat) + ($coutMedecin * $nbMedecin) + ($coutInfirmier * $nbInfirmier) + ($coutCadreOperationnel * $nbCadreOperationnel) + ($coutStagiaire * $nbStagiaire);

        $coutPersonnel = $coutPersonnel * $nbHeures / 8;
        return $coutPersonnel;
    }

    /*
        Calcul du coût kilométriques
     */
    public function _getCoutKilometres(){
        $parametre = TableRegistry::get('ConfigParametres');
        $parametres = $parametre->find('all')->last();
        /*Coût d'un kilometre*/
        $coutKilometre = $parametres->cout_kilometres;
        $coutTotalKilometres = $coutKilometre * $this->nb_kilometres;
        return $coutTotalKilometres;
    }

    /*
        Cout hebergement (tentes)
    */
    public function _getCoutHebergement(){
        $parametre = TableRegistry::get('ConfigParametres');
        $parametres = $parametre->find('all')->last();
        /*Coût unitaire d'une tente*/
        $coutTente = $parametres->cout_hebergement;

        return $coutTente * $this->nb_hebergement;
    }

    /*
        Cout repas
    */
    public function _getCoutRepas(){
        $parametre = TableRegistry::get('ConfigParametres');
        $parametres = $parametre->find('all')->last();
        /*Coût unitaire d'un repas*/
        $coutTente = $parametres->cout_repas;

        return $coutTente * $this->nb_repas;
    }


    /*
        Cout portatifs et licences
    */
    public function _getCoutPortatifs(){
        $parametre = TableRegistry::get('ConfigParametres');
        $parametres = $parametre->find('all')->last();
        /*Coût unitaire d'un portatif + licence*/
        $coutPortatifs = $parametres->cout_portatif;

        return $coutPortatifs * $this->nb_portatifs;
    }

    public function _getParamFraisGestion(){
        $parametre = TableRegistry::get('ConfigParametres');
        $parametres = $parametre->find('all')->last();
        /*Frais de gestion en %*/
        $fraisGestion = $parametres->frais_gestion;

        return $fraisGestion;
    }
}

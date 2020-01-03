<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;


/**
 * Comptabilite Controller
 *
 * @property \App\Model\Table\ComptabiliteTable $Comptabilite
 *
 * @method \App\Model\Entity\Comptabilite[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RechercheDpsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $demande = TableRegistry::get('Demandes');
        $demandes = $demande->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
                                                   				'sort' => ['Dimensionnements.horaires_debut' => 'ASC']],'Antennes']]);
        $demandesFacturees = $demande->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
                                                                'sort' => ['Dimensionnements.horaires_debut' => 'ASC']],'Antennes']])->where ('ordre = 13');
        $demandesAttenteFacture = $demande->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
                                                                'sort' => ['Dimensionnements.horaires_debut' => 'ASC']],'Antennes']])->where ('ordre = 10');
        $this->set(compact('demandes','demandesFacturees','demandesAttenteFacture'));

    }

    public function resultats()
    {
        $demande = TableRegistry::get('Demandes');
        $resultats = $demande->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
                                                   				'sort' => ['Dimensionnements.horaires_debut' => 'ASC']],'Antennes']])->where("UPPER(manifestation) like UPPER('%".$this->request->getData('Nom_de_la_manifestation')."%')");
        $this->set(compact('resultats'));
    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;

/**
 * PersonnelsEquipes Controller
 *
 * @property \App\Model\Table\PersonnelsEquipesTable $PersonnelsEquipes
 *
 * @method \App\Model\Entity\PersonnelsEquipe[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PersonnelsEquipesController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New personnelsEquipe', 'config' => [ 'controller' => 'PersonnelsEquipes', 'action' => 'add' ]],
		[ 'label' => 'List personnelsEquipe', 'config' => [ 'controller' => 'PersonnelsEquipes', 'action' => 'index' ]],
		[ 'label' => 'List Equipes', 'config' => ['controller' => 'Equipes', 'action' => 'index' ]],
		[ 'label' => 'Add Equipes', 'config' => ['controller' => 'Equipes', 'action' => 'add' ]],
		[ 'label' => 'List Personnels', 'config' => ['controller' => 'Personnels', 'action' => 'index' ]],
		[ 'label' => 'Add Personnels', 'config' => ['controller' => 'Personnels', 'action' => 'add' ]],
    ];

	
	var $arraysum = ['paths'=>[
							'total_cout'=>'+dimensionnements.{n}.dispositif.equipes.{n}.cout_personnel/dimensionnements.{n}.dispositif.equipes.{n}.cout_kilometres/dimensionnements.{n}.dispositif.equipes.{n}.cout_repas',
							'total_personnel'=>'+dimensionnements.{n}.dispositif.personnels_public/dimensionnements.{n}.dispositif.personnels_acteurs',
							'total_duree'=>'dimensionnements.{n}.dispositif.equipes.{n}.duree',
							'total_kilometres'=>'dimensionnements.{n}.dispositif.equipes.{n}.cout_kilometres',
							'total_repas'=>'dimensionnements.{n}.dispositif.equipes.{n}.cout_repas',
							'total_remise'=>'dimensionnements.{n}.dispositif.equipes.{n}.cout_remise',
							'total_economie'=>'dimensionnements.{n}.dispositif.equipes.{n}.cout_economie',
							'total_antennes'=>'dimensionnements.{n}.dispositif.equipes.{n}.repartition_antenne',
							'total_adpc'=>'dimensionnements.{n}.dispositif.equipes.{n}.repartition_adpc',
							'total_repas_matin'=>'*dimensionnements.{n}.dispositif.equipes.{n}.repas_matin/dimensionnements.{n}.dispositif.equipes.{n}.repas_charge',
							'total_repas_midi'=>'*dimensionnements.{n}.dispositif.equipes.{n}.repas_midi/dimensionnements.{n}.dispositif.equipes.{n}.repas_charge',
							'total_repas_soir'=>'*dimensionnements.{n}.dispositif.equipes.{n}.repas_soir/dimensionnements.{n}.dispositif.equipes.{n}.repas_charge',
							'total_vehicules'=>'dimensionnements.{n}.dispositif.equipes.{n}.vehicule_type',
							'total_lota'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_a',
							'total_lotb'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_b',
							'total_lotc'=>'dimensionnements.{n}.dispositif.equipes.{n}.lot_c',
							]
						];
						
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Equipes', 'Personnels']
        ];
        $personnelsEquipes = $this->paginate($this->PersonnelsEquipes);
		
		$navigation = $this->navigation;

        $this->set(compact('personnelsEquipes','navigation'));
    }

	public function ajax($function = false){
		
		$this->autoRender = false;
		
	    // Force le controller à rendre une réponse JSON.
        $this->RequestHandler->renderAs($this, 'json');
		
        // Définit le type de réponse de la requete AJAX
        $this->response->type('application/json');

        // Chargement du layout AJAX
        $this->viewBuilder()->layout('ajax');
		
		// Chargement des données
		$json_data[] = ['id'=>2500,'title'=>'test','start'=>'2018-07-16 12:30:00','end'=>'2018-07-18 05:45:00','url'=>'http://localhost/crud/antennes/view/1'];
		$json_data[] = ['id'=>2501,'title'=>'test','start'=>'2018-07-18 05:45:00','end'=>'2018-07-27'];//,'rendering'=>'background'

		$response = $this->response->withType('json')->withStringBody(json_encode($json_data));
		
		// Retour des données encodées en JSON
		return $response;
	}

    /**
     * View method
     *
     * @param string|null $id Personnels Equipe id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $personnelsEquipe = $this->PersonnelsEquipes->get($id, [
            'contain' => ['Equipes', 'Personnels']
        ]);

        $this->set('personnelsEquipe', $personnelsEquipe);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $personnelsEquipe = $this->PersonnelsEquipes->newEntity();
        if ($this->request->is('post')) {
            $personnelsEquipe = $this->PersonnelsEquipes->patchEntity($personnelsEquipe, $this->request->getData());
            if ($this->PersonnelsEquipes->save($personnelsEquipe)) {
                $this->Flash->success(__('The personnels equipe has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The personnels equipe could not be saved. Please, try again.'));
        }
        $equipes = $this->PersonnelsEquipes->Equipes->listing(['group'=>'dispositif_id'],'id','indicatif');
        $personnels = $this->PersonnelsEquipes->Personnels->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('personnelsEquipe', 'equipes', 'personnels','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Personnels Equipe id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $personnelsEquipe = $this->PersonnelsEquipes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $personnelsEquipe = $this->PersonnelsEquipes->patchEntity($personnelsEquipe, $this->request->getData());
            if ($this->PersonnelsEquipes->save($personnelsEquipe)) {
                $this->Flash->success(__('The personnels equipe has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The personnels equipe could not be saved. Please, try again.'));
        }
        $equipes = $this->PersonnelsEquipes->Equipes->listing('list', ['limit' => 200]);
        $personnels = $this->PersonnelsEquipes->Personnels->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('personnelsEquipe', 'equipes', 'personnels','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Personnels Equipe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $personnelsEquipe = $this->PersonnelsEquipes->get($id);
        if ($this->PersonnelsEquipes->delete($personnelsEquipe)) {
            $this->Flash->success(__('The personnels equipe has been deleted.'));
        } else {
            $this->Flash->error(__('The personnels equipe could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

	public function inscriptions( $user_id = false ) {

		$this->loadModel('Equipes');

		//$inscriptions = Hash::sort($demandes->toArray(),'{n}.strtotime_convocation','asc');
		
		$inscriptions = $this->Equipes->getDistinctStrtotime();
		
		$this->set('inscriptions', $inscriptions);
		
		var_dump($this->request->getData());
    }
	
}

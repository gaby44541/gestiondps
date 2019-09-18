<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;

/**
 * Dimensionnements Controller
 *
 * @property \App\Model\Table\DimensionnementsTable $Dimensionnements
 *
 * @method \App\Model\Entity\Dimensionnement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DimensionnementsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New dimensionnement', 'config' => [ 'controller' => 'Dimensionnements', 'action' => 'add' ]],
		[ 'label' => 'List dimensionnement', 'config' => [ 'controller' => 'Dimensionnements', 'action' => 'index' ]],
		[ 'label' => 'List Demandes', 'config' => ['controller' => 'Demandes', 'action' => 'index' ]],
		[ 'label' => 'Add Demandes', 'config' => ['controller' => 'Demandes', 'action' => 'add' ]],
		[ 'label' => 'List Dispositifs', 'config' => ['controller' => 'Dispositifs', 'action' => 'index' ]],
		[ 'label' => 'Add Dispositifs', 'config' => ['controller' => 'Dispositifs', 'action' => 'add' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Demandes']
        ];
        $dimensionnements = $this->paginate($this->Dimensionnements);
		
		$navigation = $this->navigation;

        $this->set(compact('dimensionnements','navigation'));
    }

	    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function wizard() 
    {
		$demande_id = (int) $this->Wizard->getDatas('Demandes.id'); 

		$dimensionnements = $this->Dimensionnements->find('all')
			->contain(['Demandes','Dispositifs'])
			->where(['demande_id'=>$demande_id])
			->orderAsc('horaires_debut')
			->orderAsc('horaires_fin');
		
		$dimensionnements_ids = Hash::extract($dimensionnements->toArray(),'{n}.id');
		
		$this->Wizard->injectDatas('Dimensionnements.id',$dimensionnements_ids); 
		
		$demande = $this->Dimensionnements->Demandes->get($demande_id);
		
		$navigation = $this->navigation;

        $this->set(compact('dimensionnements','navigation','demande','demande_id'));
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
     * @param string|null $id Dimensionnement id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dimensionnement = $this->Dimensionnements->get($id, [
            'contain' => ['Demandes', 'Dispositifs']
        ]);

        $this->set('dimensionnement', $dimensionnement);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($demande_id=0)
    {

        $dimensionnement = $this->Dimensionnements->newEntity();
		
        if ($this->request->is('post')) {
            
			$dimensionnement = $this->Dimensionnements->patchEntity($dimensionnement, $this->request->getData());
			
            if ($this->Dimensionnements->save($dimensionnement)) {
                $this->Flash->success(__('The dimensionnement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
			
            $this->Flash->error(__('The dimensionnement could not be saved. Please, try again.'));
        
		}

		if(!empty($demande_id)){
			$demandes = $this->Dimensionnements->Demandes->alone($demande_id);
		} else {
			$demandes = $this->Dimensionnements->Demandes->listing();
		}
		
		$navigation = $this->navigation;
		$environnements = $dimensionnement->get('environnements');
		
        $this->set(compact('dimensionnement','demandes','navigation','demande_id','environnements'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dimensionnement id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dimensionnement = $this->Dimensionnements->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $dimensionnement = $this->Dimensionnements->patchEntity($dimensionnement, $this->request->getData());
            if ($this->Dimensionnements->save($dimensionnement)) {
                $this->Flash->success(__('The dimensionnement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The dimensionnement could not be saved. Please, try again.'));
        }
		
        //$demandes = $this->Dimensionnements->Demandes->find('list', ['limit' => 200]);
		$demandes = $this->Dimensionnements->Demandes->alone($dimensionnement->demande_id);

		$dimensionnement->assis = explode(',',$dimensionnement->assis);
		$dimensionnement->secours_presents = explode(',',$dimensionnement->secours_presents);
		$dimensionnement->documents_officiels = explode(',',$dimensionnement->documents_officiels);
		$dimensionnement->acces = explode(',',$dimensionnement->acces);
		
		$navigation = $this->navigation;
		$environnements = $dimensionnement->get('environnements');
		
        $this->set(compact('dimensionnement', 'demandes','navigation','environnements'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Dimensionnement id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dimensionnement = $this->Dimensionnements->get($id);
        if ($this->Dimensionnements->delete($dimensionnement)) {
            $this->Flash->success(__('The dimensionnement has been deleted.'));
        } else {
            $this->Flash->error(__('The dimensionnement could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Equipe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function duplicate($id = null)
    {	
		$this->autoRender = false;
        //$this->request->allowMethod(['post', 'delete']);

		if ($this->Dimensionnements->duplicate($id)) {
			$this->Flash->success(__('The equipe has been duplicate.'));
        } else {
            $this->Flash->error(__('The equipe could not be duplicate. Please, try again.'));
        }

       return $this->redirect(['action' => 'index']);
    }
	

    /**
     * Delete method
     *
     * @param string|null $id Equipe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function scinder($id = null)
    {	
		$this->autoRender = false;
        //$this->request->allowMethod(['post', 'delete']);

        $dimensionnement = $this->Dimensionnements->get($id, [
            'contain' => ['Demandes', 'Dispositifs']
        ]);
		
		$old = $dimensionnement;
		
		$demande_id = (int) $dimensionnement->get('demande_id');
		
		if( $demande_id ){
			
			$data = $this->Dimensionnements->Demandes->duplicate($demande_id);
			
			if( $data ){
				
				$demande_id = $data->get('id');
				
				if( $demande_id ){

					$dimensionnement->set('demande_id',$demande_id);
					
					$save = $this->Dimensionnements->patchEntity($old,$dimensionnement->toArray());
					
					if($this->Dimensionnements->save($save)) {
						
						$this->Flash->success(__('The dimensionnement has been saved.'));
						return $this->redirect(['controller'=>'demandes','action' => 'view',$demande_id]);
					}
				}
		
			}
			
			$this->Flash->error(__('Echec de duplication de la demande attachée à cette déclaration.'));
			
		}
		
		$this->Flash->error(__('Impossible de scinder cette déclaration.'));
		
		return $this->redirect(['controller'=>'demandes','action' => 'index']);

    }
}

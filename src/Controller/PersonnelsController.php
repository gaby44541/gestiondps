<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Personnels Controller
 *
 * @property \App\Model\Table\PersonnelsTable $Personnels
 *
 * @method \App\Model\Entity\Personnel[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PersonnelsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New personnel', 'config' => [ 'controller' => 'Personnels', 'action' => 'add' ]],
		[ 'label' => 'List personnel', 'config' => [ 'controller' => 'Personnels', 'action' => 'index' ]],
		[ 'label' => 'List Antennes', 'config' => ['controller' => 'Antennes', 'action' => 'index' ]],
		[ 'label' => 'Add Antennes', 'config' => ['controller' => 'Antennes', 'action' => 'add' ]],
		[ 'label' => 'List Equipes', 'config' => ['controller' => 'Equipes', 'action' => 'index' ]],
		[ 'label' => 'Add Equipes', 'config' => ['controller' => 'Equipes', 'action' => 'add' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $personnels = $this->paginate($this->Personnels);
		
		$navigation = $this->navigation;

        $this->set(compact('personnels','navigation'));
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
     * @param string|null $id Personnel id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $personnel = $this->Personnels->get($id, [
            'contain' => ['Antennes', 'Equipes']
        ]);

        $this->set('personnel', $personnel);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $personnel = $this->Personnels->newEntity();
        if ($this->request->is('post')) {
            $personnel = $this->Personnels->patchEntity($personnel, $this->request->getData());
            if ($this->Personnels->save($personnel)) {
                $this->Flash->success(__('The personnel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The personnel could not be saved. Please, try again.'));
        }
        $antennes = $this->Personnels->Antennes->find('list', ['limit' => 200]);
        $equipes = $this->Personnels->Equipes->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('personnel', 'antennes', 'equipes','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Personnel id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $personnel = $this->Personnels->get($id, [
            'contain' => ['Antennes', 'Equipes']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $personnel = $this->Personnels->patchEntity($personnel, $this->request->getData());
            if ($this->Personnels->save($personnel)) {
                $this->Flash->success(__('The personnel has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The personnel could not be saved. Please, try again.'));
        }
        $antennes = $this->Personnels->Antennes->find('list', ['limit' => 200]);
        $equipes = $this->Personnels->Equipes->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('personnel', 'antennes', 'equipes','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Personnel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $personnel = $this->Personnels->get($id);
        if ($this->Personnels->delete($personnel)) {
            $this->Flash->success(__('The personnel has been deleted.'));
        } else {
            $this->Flash->error(__('The personnel could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

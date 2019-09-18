<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConfigEnvironnements Controller
 *
 * @property \App\Model\Table\ConfigEnvironnementsTable $ConfigEnvironnements
 *
 * @method \App\Model\Entity\ConfigEnvironnement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigEnvironnementsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New configEnvironnement', 'config' => [ 'controller' => 'ConfigEnvironnements', 'action' => 'add' ]],
		[ 'label' => 'List configEnvironnement', 'config' => [ 'controller' => 'ConfigEnvironnements', 'action' => 'index' ]],
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
        $configEnvironnements = $this->paginate($this->ConfigEnvironnements);
		
		$navigation = $this->navigation;

        $this->set(compact('configEnvironnements','navigation'));
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
     * @param string|null $id Config Environnement id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configEnvironnement = $this->ConfigEnvironnements->get($id, [
            'contain' => ['Dispositifs']
        ]);

        $this->set('configEnvironnement', $configEnvironnement);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configEnvironnement = $this->ConfigEnvironnements->newEntity();
        if ($this->request->is('post')) {
            $configEnvironnement = $this->ConfigEnvironnements->patchEntity($configEnvironnement, $this->request->getData());
            if ($this->ConfigEnvironnements->save($configEnvironnement)) {
                $this->Flash->success(__('The config environnement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config environnement could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configEnvironnement','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config Environnement id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configEnvironnement = $this->ConfigEnvironnements->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configEnvironnement = $this->ConfigEnvironnements->patchEntity($configEnvironnement, $this->request->getData());
            if ($this->ConfigEnvironnements->save($configEnvironnement)) {
                $this->Flash->success(__('The config environnement has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config environnement could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configEnvironnement','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config Environnement id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configEnvironnement = $this->ConfigEnvironnements->get($id);
        if ($this->ConfigEnvironnements->delete($configEnvironnement)) {
            $this->Flash->success(__('The config environnement has been deleted.'));
        } else {
            $this->Flash->error(__('The config environnement could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConfigTypepublics Controller
 *
 * @property \App\Model\Table\ConfigTypepublicsTable $ConfigTypepublics
 *
 * @method \App\Model\Entity\ConfigTypepublic[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigTypepublicsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New configTypepublic', 'config' => [ 'controller' => 'ConfigTypepublics', 'action' => 'add' ]],
		[ 'label' => 'List configTypepublic', 'config' => [ 'controller' => 'ConfigTypepublics', 'action' => 'index' ]],
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
        $configTypepublics = $this->paginate($this->ConfigTypepublics);
		
		$navigation = $this->navigation;

        $this->set(compact('configTypepublics','navigation'));
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
     * @param string|null $id Config Typepublic id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configTypepublic = $this->ConfigTypepublics->get($id, [
            'contain' => ['Dispositifs']
        ]);

        $this->set('configTypepublic', $configTypepublic);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configTypepublic = $this->ConfigTypepublics->newEntity();
        if ($this->request->is('post')) {
            $configTypepublic = $this->ConfigTypepublics->patchEntity($configTypepublic, $this->request->getData());
            if ($this->ConfigTypepublics->save($configTypepublic)) {
                $this->Flash->success(__('The config typepublic has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config typepublic could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configTypepublic','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config Typepublic id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configTypepublic = $this->ConfigTypepublics->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configTypepublic = $this->ConfigTypepublics->patchEntity($configTypepublic, $this->request->getData());
            if ($this->ConfigTypepublics->save($configTypepublic)) {
                $this->Flash->success(__('The config typepublic has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config typepublic could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configTypepublic','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config Typepublic id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configTypepublic = $this->ConfigTypepublics->get($id);
        if ($this->ConfigTypepublics->delete($configTypepublic)) {
            $this->Flash->success(__('The config typepublic has been deleted.'));
        } else {
            $this->Flash->error(__('The config typepublic could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConfigDelais Controller
 *
 * @property \App\Model\Table\ConfigDelaisTable $ConfigDelais
 *
 * @method \App\Model\Entity\ConfigDelai[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigDelaisController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New configDelai', 'config' => [ 'controller' => 'ConfigDelais', 'action' => 'add' ]],
		[ 'label' => 'List configDelai', 'config' => [ 'controller' => 'ConfigDelais', 'action' => 'index' ]],
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
        $configDelais = $this->paginate($this->ConfigDelais);
		
		$navigation = $this->navigation;

        $this->set(compact('configDelais','navigation'));
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
     * @param string|null $id Config Delai id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configDelai = $this->ConfigDelais->get($id, [
            'contain' => ['Dispositifs']
        ]);

        $this->set('configDelai', $configDelai);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configDelai = $this->ConfigDelais->newEntity();
        if ($this->request->is('post')) {
            $configDelai = $this->ConfigDelais->patchEntity($configDelai, $this->request->getData());
            if ($this->ConfigDelais->save($configDelai)) {
                $this->Flash->success(__('The config delai has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config delai could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configDelai','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config Delai id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configDelai = $this->ConfigDelais->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configDelai = $this->ConfigDelais->patchEntity($configDelai, $this->request->getData());
            if ($this->ConfigDelais->save($configDelai)) {
                $this->Flash->success(__('The config delai has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config delai could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configDelai','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config Delai id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configDelai = $this->ConfigDelais->get($id);
        if ($this->ConfigDelais->delete($configDelai)) {
            $this->Flash->success(__('The config delai has been deleted.'));
        } else {
            $this->Flash->error(__('The config delai could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

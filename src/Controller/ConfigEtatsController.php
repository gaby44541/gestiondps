<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConfigEtats Controller
 *
 * @property \App\Model\Table\ConfigEtatsTable $ConfigEtats
 *
 * @method \App\Model\Entity\ConfigEtat[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigEtatsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New configEtat', 'config' => [ 'controller' => 'ConfigEtats', 'action' => 'add' ]],
		[ 'label' => 'List configEtat', 'config' => [ 'controller' => 'ConfigEtats', 'action' => 'index' ]],
		[ 'label' => 'List Demandes', 'config' => ['controller' => 'Demandes', 'action' => 'index' ]],
		[ 'label' => 'Add Demandes', 'config' => ['controller' => 'Demandes', 'action' => 'add' ]],
    ];
	
	// Todo une fonction move up, move down ?
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
		// Suppression du paginate vu le nombre d'item
		$configEtats = $this->ConfigEtats->find('all',['contain'=>['Demandes'],'order'=>['ordre'=>'asc']]);

		$navigation = $this->navigation;

        $this->set(compact('configEtats','navigation'));
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
     * @param string|null $id Config Etat id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configEtat = $this->ConfigEtats->get($id, [
            'contain' => ['Demandes']
        ]);

        $this->set('configEtat', $configEtat);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configEtat = $this->ConfigEtats->newEntity();
        if ($this->request->is('post')) {
            $configEtat = $this->ConfigEtats->patchEntity($configEtat, $this->request->getData());
            if ($this->ConfigEtats->save($configEtat)) {
                $this->ConfigEtats->reorder();
				$this->Flash->success(__('The config etat has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config etat could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configEtat','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config Etat id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configEtat = $this->ConfigEtats->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configEtat = $this->ConfigEtats->patchEntity($configEtat, $this->request->getData());
            if ($this->ConfigEtats->save($configEtat)) {
                $this->Flash->success(__('The config etat has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config etat could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configEtat','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config Etat id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configEtat = $this->ConfigEtats->get($id);
        if ($this->ConfigEtats->delete($configEtat)) {
			$this->ConfigEtats->reorder();
            $this->Flash->success(__('The config etat has been deleted.'));
        } else {
            $this->Flash->error(__('The config etat could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

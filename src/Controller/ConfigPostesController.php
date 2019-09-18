<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConfigPostes Controller
 *
 * @property \App\Model\Table\ConfigPostesTable $ConfigPostes
 *
 * @method \App\Model\Entity\ConfigPoste[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigPostesController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New configPoste', 'config' => [ 'controller' => 'ConfigPostes', 'action' => 'add' ]],
		[ 'label' => 'List configPoste', 'config' => [ 'controller' => 'ConfigPostes', 'action' => 'index' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $configPostes = $this->paginate($this->ConfigPostes);
		
		$navigation = $this->navigation;

        $this->set(compact('configPostes','navigation'));
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
     * @param string|null $id Config Poste id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configPoste = $this->ConfigPostes->get($id, [
            'contain' => []
        ]);

        $this->set('configPoste', $configPoste);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configPoste = $this->ConfigPostes->newEntity();
        if ($this->request->is('post')) {
            $configPoste = $this->ConfigPostes->patchEntity($configPoste, $this->request->getData());
            if ($this->ConfigPostes->save($configPoste)) {
                $this->Flash->success(__('The config poste has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config poste could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configPoste','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config Poste id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configPoste = $this->ConfigPostes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configPoste = $this->ConfigPostes->patchEntity($configPoste, $this->request->getData());
            if ($this->ConfigPostes->save($configPoste)) {
                $this->Flash->success(__('The config poste has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config poste could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configPoste','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config Poste id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configPoste = $this->ConfigPostes->get($id);
        if ($this->ConfigPostes->delete($configPoste)) {
            $this->Flash->success(__('The config poste has been deleted.'));
        } else {
            $this->Flash->error(__('The config poste could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

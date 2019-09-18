<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConfigLogs Controller
 *
 * @property \App\Model\Table\ConfigLogsTable $ConfigLogs
 *
 * @method \App\Model\Entity\ConfigLog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigLogsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New configLog', 'config' => [ 'controller' => 'ConfigLogs', 'action' => 'add' ]],
		[ 'label' => 'List configLog', 'config' => [ 'controller' => 'ConfigLogs', 'action' => 'index' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
		$configLogs = $this->ConfigLogs->find('all',['limit'=>400,'order'=>['date'=>'desc']])->toArray();
		
        //$configLogs = $this->paginate($configLogs);
		
		$navigation = $this->navigation;

        $this->set(compact('configLogs','navigation'));
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
     * @param string|null $id Config Log id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configLog = $this->ConfigLogs->get($id, [
            'contain' => []
        ]);

        $this->set('configLog', $configLog);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configLog = $this->ConfigLogs->newEntity();
        if ($this->request->is('post')) {
            $configLog = $this->ConfigLogs->patchEntity($configLog, $this->request->getData());
            if ($this->ConfigLogs->save($configLog)) {
                $this->Flash->success(__('The config log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config log could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configLog','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config Log id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configLog = $this->ConfigLogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configLog = $this->ConfigLogs->patchEntity($configLog, $this->request->getData());
            if ($this->ConfigLogs->save($configLog)) {
                $this->Flash->success(__('The config log has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config log could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configLog','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config Log id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
		$configLog = $this->ConfigLogs->get($id);
		
        if ($this->ConfigLogs->delete($configLog)) {
            $this->Flash->success(__('The config log has been deleted.'));
        } else {
            $this->Flash->error(__('The config log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

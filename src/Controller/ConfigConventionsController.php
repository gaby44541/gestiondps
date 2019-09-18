<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ConfigConventions Controller
 *
 * @property \App\Model\Table\ConfigConventionsTable $ConfigConventions
 *
 * @method \App\Model\Entity\ConfigConvention[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigConventionsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New configConvention', 'config' => [ 'controller' => 'ConfigConventions', 'action' => 'add' ]],
		[ 'label' => 'List configConvention', 'config' => [ 'controller' => 'ConfigConventions', 'action' => 'index' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $configConventions = $this->paginate($this->ConfigConventions);

		$configConventions = $this->ConfigConventions->find('all')->order('ordre');
				
		$navigation = $this->navigation;
		
		$this->ConfigConventions->reOrder();
		
        $this->set(compact('configConventions','navigation'));
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
     * @param string|null $id Config Convention id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configConvention = $this->ConfigConventions->get($id, [
            'contain' => []
        ]);

        $this->set('configConvention', $configConvention);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configConvention = $this->ConfigConventions->newEntity();
        if ($this->request->is('post')) {
            $configConvention = $this->ConfigConventions->patchEntity($configConvention, $this->request->getData());
            if ($this->ConfigConventions->save($configConvention)) {
                $this->Flash->success(__('The config convention has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config convention could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configConvention','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config Convention id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configConvention = $this->ConfigConventions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configConvention = $this->ConfigConventions->patchEntity($configConvention, $this->request->getData());
            if ($this->ConfigConventions->save($configConvention)) {
                $this->Flash->success(__('The config convention has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config convention could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configConvention','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config Convention id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configConvention = $this->ConfigConventions->get($id);
        if ($this->ConfigConventions->delete($configConvention)) {
            $this->Flash->success(__('The config convention has been deleted.'));
        } else {
            $this->Flash->error(__('The config convention could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

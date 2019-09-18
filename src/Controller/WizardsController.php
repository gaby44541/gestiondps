<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Wizards Controller
 *
 * @property \App\Model\Table\WizardsTable $Wizards
 *
 * @method \App\Model\Entity\Wizard[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WizardsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New wizard', 'config' => [ 'controller' => 'Wizards', 'action' => 'add' ]],
		[ 'label' => 'List wizard', 'config' => [ 'controller' => 'Wizards', 'action' => 'index' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $wizards = $this->paginate($this->Wizards);
		
		$navigation = $this->navigation;

        $this->set(compact('wizards','navigation'));
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
     * @param string|null $id Wizard id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $wizard = $this->Wizards->get($id, [
            'contain' => []
        ]);

        $this->set('wizard', $wizard);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $wizard = $this->Wizards->newEntity();
        if ($this->request->is('post')) {
            $wizard = $this->Wizards->patchEntity($wizard, $this->request->getData());
            if ($this->Wizards->save($wizard)) {
                $this->Flash->success(__('The wizard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The wizard could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('wizard','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Wizard id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $wizard = $this->Wizards->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $wizard = $this->Wizards->patchEntity($wizard, $this->request->getData());
            if ($this->Wizards->save($wizard)) {
                $this->Flash->success(__('The wizard has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The wizard could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('wizard','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Wizard id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $wizard = $this->Wizards->get($id);
        if ($this->Wizards->delete($wizard)) {
            $this->Flash->success(__('The wizard has been deleted.'));
        } else {
            $this->Flash->error(__('The wizard could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

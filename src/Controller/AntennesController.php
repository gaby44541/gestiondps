<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;

/**
 * Antennes Controller
 *
 * @property \App\Model\Table\AntennesTable $Antennes
 *
 * @method \App\Model\Entity\Antenne[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AntennesController extends AppController
{
	var $navigation = [
		[ 'label' => 'New antenne', 'config' => [ 'controller' => 'Antennes', 'action' => 'add' ]],
		[ 'label' => 'List antenne', 'config' => [ 'controller' => 'Antennes', 'action' => 'index' ]],
		[ 'label' => 'List Personnels', 'config' => ['controller' => 'Personnels', 'action' => 'index' ]],
		[ 'label' => 'Add Personnels', 'config' => ['controller' => 'Personnels', 'action' => 'add' ]],
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $antennes = $this->paginate($this->Antennes);

		$navigation = $this->navigation;

        $this->set(compact('antennes','navigation'));
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
     * @param string|null $id Antenne id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $antenne = $this->Antennes->get($id, [
            'contain' => ['Personnels']
        ]);

        $this->set('antenne', $antenne);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $antenne = $this->Antennes->newEntity();
        if ($this->request->is('post')) {
            $antenne = $this->Antennes->patchEntity($antenne, $this->request->getData());
            if ($this->Antennes->save($antenne)) {
                $this->Flash->success(__('The antenne has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The antenne could not be saved. Please, try again.'));
        }
        $personnels = $this->Antennes->Personnels->find('list', ['limit' => 200]);

		$navigation = $this->navigation;

        $this->set(compact('antenne', 'personnels','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Antenne id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $antenne = $this->Antennes->get($id, [
            'contain' => ['Personnels']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $antenne = $this->Antennes->patchEntity($antenne, $this->request->getData());
            if ($this->Antennes->save($antenne)) {
                $this->Flash->success(__('The antenne has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The antenne could not be saved. Please, try again.'));
        }
        $personnels = $this->Antennes->Personnels->find('list', ['limit' => 200]);

		$navigation = $this->navigation;

        $this->set(compact('antenne', 'personnels','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Antenne id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $antenne = $this->Antennes->get($id);
        if ($this->Antennes->delete($antenne)) {
            $this->Flash->success(__('The antenne has been deleted.'));
        } else {
            $this->Flash->error(__('The antenne could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

	    /**
     * Delete method
     *
     * @param string|null $id Antenne id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function calendar()
    {

		$navigation = $this->navigation;

        $this->set(compact('navigation'));
    }
}

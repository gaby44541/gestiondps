<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Mailings Controller
 *
 * @property \App\Model\Table\MailingsTable $Mailings
 *
 * @method \App\Model\Entity\Mailing[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MailingsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New mailing', 'config' => [ 'controller' => 'Mailings', 'action' => 'add' ]],
		[ 'label' => 'List mailing', 'config' => [ 'controller' => 'Mailings', 'action' => 'index' ]],
		[ 'label' => 'List Mails', 'config' => ['controller' => 'Mails', 'action' => 'index' ]],
		[ 'label' => 'Add Mails', 'config' => ['controller' => 'Mails', 'action' => 'add' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
		$mailings = $this->Mailings->find('all',['contain'=>['Mails']]);
		
		$navigation = $this->navigation;

        $this->set(compact('mailings','navigation'));
    }

	public function ajax($function = false)
	{
		
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
     * @param string|null $id Mailing id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mailing = $this->Mailings->get($id, [
            'contain' => ['Mails']
        ]);

        $this->set('mailing', $mailing);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mailing = $this->Mailings->newEntity();
        if ($this->request->is('post')) {
            $mailing = $this->Mailings->patchEntity($mailing, $this->request->getData());
            if ($this->Mailings->save($mailing)) {
                $this->Flash->success(__('The mailing has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mailing could not be saved. Please, try again.'));
        }
        $mails = $this->Mailings->Mails->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('mailing', 'mails','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Mailing id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mailing = $this->Mailings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mailing = $this->Mailings->patchEntity($mailing, $this->request->getData());
            if ($this->Mailings->save($mailing)) {
                $this->Flash->success(__('The mailing has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mailing could not be saved. Please, try again.'));
        }
        $mails = $this->Mailings->Mails->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('mailing', 'mails','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Mailing id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mailing = $this->Mailings->get($id);
        if ($this->Mailings->delete($mailing)) {
            $this->Flash->success(__('The mailing has been deleted.'));
        } else {
            $this->Flash->error(__('The mailing could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

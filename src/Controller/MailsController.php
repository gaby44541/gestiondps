<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Mails Controller
 *
 * @property \App\Model\Table\MailsTable $Mails
 *
 * @method \App\Model\Entity\Mail[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MailsController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New mail', 'config' => [ 'controller' => 'Mails', 'action' => 'add' ]],
		[ 'label' => 'List mail', 'config' => [ 'controller' => 'Mails', 'action' => 'index' ]],
		[ 'label' => 'List Mailings', 'config' => ['controller' => 'Mailings', 'action' => 'index' ]],
		[ 'label' => 'Add Mailings', 'config' => ['controller' => 'Mailings', 'action' => 'add' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $mails = $this->paginate($this->Mails);
		
		$navigation = $this->navigation;

        $this->set(compact('mails','navigation'));
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
     * @param string|null $id Mail id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mail = $this->Mails->get($id, [
            'contain' => ['Mailings']
        ]);

        $this->set('mail', $mail);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mail = $this->Mails->newEntity();
        if ($this->request->is('post')) {
            $mail = $this->Mails->patchEntity($mail, $this->request->getData());
            if ($this->Mails->save($mail)) {
                $this->Flash->success(__('The mail has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mail could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('mail','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Mail id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mail = $this->Mails->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mail = $this->Mails->patchEntity($mail, $this->request->getData());
            if ($this->Mails->save($mail)) {
                $this->Flash->success(__('The mail has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mail could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('mail','navigation'));
    }

	    /**
     * Delete method
     *
     * @param string|null $id Equipe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function duplicate($id = null)
    {	
		$this->autoRender = false;
        //$this->request->allowMethod(['post', 'delete']);

		if ($this->Mails->duplicate($id)) {
			$this->Flash->success(__('The mail has been duplicate.'));
        } else {
            $this->Flash->error(__('The mail could not be duplicate. Please, try again.'));
        }

       return $this->redirect(['action' => 'index']);
    }
	
    /**
     * Delete method
     *
     * @param string|null $id Mail id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mail = $this->Mails->get($id);
        if ($this->Mails->delete($mail)) {
            $this->Flash->success(__('The mail has been deleted.'));
        } else {
            $this->Flash->error(__('The mail could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;
use Cake\Http\Session;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New user', 'config' => [ 'controller' => 'Users', 'action' => 'add' ]],
		[ 'label' => 'List user', 'config' => [ 'controller' => 'Users', 'action' => 'index' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);
		
		$navigation = $this->navigation;

        $this->set(compact('users','navigation'));
		
/* 		$this->loadModel('Mails');
		
		$model = $this->Mails->find('all',['where'=>['type'=>'aftersave']])->first();

		$model->attachments = explode(',',$model->attachments);
		
		


		$email = new Email('default');
		$email->setFrom(['president@protectioncivile-vosges.org' => 'Protection Civile des Vosges'])
			->setTo(['jcroussel88@orange.fr','president@protectioncivile-vosges.org'])
			->attachments($file)
			->setSubject($model->subject)
			->send($model->message); */
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
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Organisateurs']
        ]);

        $this->set('user', $user);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$id = (int) $this->Auth->user('id');
		
		if(!empty($id)){
			$this->Flash->error(__('Pour créer un nouveau compte utilisateur, vous devez vous déconnecter de celui-ci.'));
			//return $this->redirect(['action' => 'edit']);			
		}
		
        $user = $this->Users->newEntity();

		if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $organisateurs = $this->Users->Organisateurs->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('user', 'organisateurs','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id=0)
    {
		if(empty($id)){
			$id = (int) $this->Auth->user('id');
		}
		
        $user = $this->Users->get($id, [
            'contain' => ['Organisateurs']
        ]);

		$nouveau = $this->request->getData('nouveau');
		$confirmer = $this->request->getData('confirmer');
		
        if ($this->request->is(['patch', 'post', 'put'])) {
			
            $user = $this->Users->patchEntity($user, $this->request->getData(),['validate' => 'passwords']);

			if ($this->Users->save($user)) {
				
				if(!empty($nouveau)){
					$user->set('password',$nouveau);
				}
				
				$this->Users->save($user);
				
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $organisateurs = $this->Users->Organisateurs->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('user', 'organisateurs','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
/*         $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        } */
		$this->Flash->success(__('Pour supprimer votre compte, veuillez envoyer un mail à gabriel.boursier@loire-atlantique.protection-civile.org'));
        return $this->redirect(['action' => 'index']);
    }
	
    public function login()
    {
		$this->viewBuilder()->setLayout('login');
		
        if ($this->request->is('post')) {
            
			$user = $this->Auth->identify();

            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

	public function initialize()
	{
		parent::initialize();
		$this->Auth->allow(['logout']);
	}

	public function logout()
	{
		$session = new Session();
		$session->delete('organisateur_id');

		$this->Flash->success('Vous avez été déconnecté.');

		return $this->redirect($this->Auth->logout());
	}
}

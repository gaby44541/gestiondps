<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Organisateurs Controller
 *
 * @property \App\Model\Table\OrganisateursTable $Organisateurs
 *
 * @method \App\Model\Entity\Organisateur[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class OrganisateursController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New organisateur', 'config' => [ 'controller' => 'Organisateurs', 'action' => 'add' ]],
		[ 'label' => 'List organisateur', 'config' => [ 'controller' => 'Organisateurs', 'action' => 'index' ]],
		[ 'label' => 'List Demandes', 'config' => ['controller' => 'Demandes', 'action' => 'index' ]],
		[ 'label' => 'Add Demandes', 'config' => ['controller' => 'Demandes', 'action' => 'add' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
		//$this->Wizard->activeWizard();
		//$this->Wizard->quitWizard();
		
		$query = $this->Organisateurs->find('published');
		$organisateurs = $this->Organisateurs->find('all',['select'=>['MAX(id) AS id','*'],'group'=>['uuid']])
										->order(['nom'=>'ASC'])
										->toArray();
		
        //$organisateurs = $this->paginate($query);

		$navigation = $this->navigation;

        $this->set(compact('organisateurs','navigation'));

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function cleaning()
    {

		$this->autoRender = false;
		
		$organisateurs = $this->Organisateurs->find('all');

		foreach($organisateurs as $organisateur){
			$save = $this->Organisateurs->get($organisateur->id);

			foreach($organisateur->toArray() as $key => $val){
				$save->set($key,$val);
			}

			$this->Organisateurs->save($save);
		}
		
        $this->Flash->success(__('Nettoyage effectué.'));

		return $this->Wizard->redirect(['action' => 'index']);

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function wizard()
    {

		$organisateur_id = (int) $this->Wizard->getDatas('Organisateurs.id');
		$demande_id = (int) $this->Wizard->getDatas('Demandes.id');
		
		if(!empty($organisateur_id)){
			
			$this->viewBuilder()->setTemplate('wizard_edit');
			
			$this->Flash->success(__('Vous vous apprêtez à modifier les coordonnées d\'un organisateur, cette modification ne sera que pour ce dossier.'));

			$organisateur = $this->Organisateurs->get($organisateur_id);

			if ($this->request->is(['patch', 'post', 'put'])) {

				$organisateur = $this->Organisateurs->patchEntity($organisateur, $this->request->getData());

				if( $organisateur->isDirty() ){
					
					//$organisateur->set('publish',0);
					$this->Organisateurs->save($organisateur);
					
					//unset( $this->request->data['id'] );
					
					//$new = $this->Organisateurs->newEntity();
					//$organisateur = $this->Organisateurs->patchEntity($new, $this->request->getData());
					//$organisateur->set('publish',1);

					if ($this->Organisateurs->save($organisateur)) {
						$this->Flash->success(__('The organisateur has been saved.'));

						//return $this->redirect(['action' => 'index']);
						return $this->Wizard->redirect(['action' => 'index']);
					}
					
					$this->Flash->error(__('The organisateur could not be saved. Please, try again.'));
					
				} else {
					$this->Flash->error(__('No changes detected. Please, try again or quit by click on list button.'));
					//return $this->redirect(['action' => 'index']);
					return $this->Wizard->redirect(['action' => 'index']);
				}
				
			}	
			
			$navigation = $this->navigation;
			
			$this->set(compact('organisateur','navigation'));			
		
		} else {
			
			//$this->Flash->error(__('Vérifiez les coordonnées de l\'organisateur demandeur en cliquant sur le crayon avant de le sélectionner !'));
			
			$query = $this->Organisateurs->find('published');
			
			$organisateurs = $this->paginate($query);

			$query = $this->Organisateurs->find('published');
			$organisateurs = $this->Organisateurs->find('all',['select'=>['MAX(id) AS id','*'],'group'=>['uuid']])
											->order(['nom'=>'ASC'])
											->toArray();
											
			$navigation = $this->navigation;

			$this->set(compact('organisateurs','navigation'));
		
		}

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
     * @param string|null $id Organisateur id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $organisateur = $this->Organisateurs->get($id, [
            'contain' => ['Demandes']
        ]);

        $this->set('organisateur', $organisateur);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $organisateur = $this->Organisateurs->newEntity(['uuid' => uniqid('ORG'.rand(1000,9999),true),'publish'=>1]);
		
        if ($this->request->is('post')) {
			
            $organisateur = $this->Organisateurs->patchEntity($organisateur, $this->request->getData());
			
            if ($this->Organisateurs->save($organisateur)) {
                $this->Flash->success(__('The organisateur has been saved.'));

                return $this->Wizard->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The organisateur could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('organisateur','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Organisateur id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $organisateur = $this->Organisateurs->get($id, [
            'contain' => []
        ]);
		
        if ($this->request->is(['patch', 'post', 'put'])) {

			$organisateur = $this->Organisateurs->patchEntity($organisateur, $this->request->getData());
			
			if( $organisateur->isDirty() ){
				
				$organisateur->set('publish',0);
				$this->Organisateurs->save($organisateur);
				
				unset( $this->request->data['id'] );
				
				$new = $this->Organisateurs->newEntity();
				$organisateur = $this->Organisateurs->patchEntity($new, $this->request->getData());
				$organisateur->set('publish',1);

				if ($this->Organisateurs->save($organisateur)) {
					$this->Flash->success(__('The organisateur has been saved.'));

					//return $this->redirect(['action' => 'index']);
					return $this->Wizard->redirect(['action' => 'index']);
				}
				
				$this->Flash->error(__('The organisateur could not be saved. Please, try again.'));
				
			} else {
				$this->Flash->error(__('No changes detected. Please, try again or quit by click on list button.'));
				//return $this->redirect(['action' => 'index']);
				return $this->Wizard->redirect(['action' => 'index']);
			}
            
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('organisateur','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Organisateur id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
		
        $organisateur = $this->Organisateurs->get($id);
        
		if ($this->Organisateurs->delete($organisateur)) {
            $this->Flash->success(__('The organisateur has been deleted.'));
        } else {
            $this->Flash->error(__('The organisateur could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Equipe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function relance()
    {	
		$this->autoRender = false;
		
		$mails = $this->Organisateurs->find('list',['valueField'=>'mail'])->distinct(['uuid'])->toArray();
		
		$this->loadModel('Mails');
		$this->loadModel('Mailings');
		
		$message = $this->Mails->find()->where(['controller'=>'organisateurs','action'=>'relance','publish'=>1])->first();
		
		if($message){

			$save = [];
			
			foreach($mails as $mail){
				if(!empty($mail)){
					$save[] = [
						'uuid' => uniqid('mail'.rand(1000,9999),true),
						'message' => $message->message,
						'mail_id' => $message->id,
						'destinataire' => $mail
					];
				}
			}
			
			$save = $this->Mailings->newEntities($save);

			if($this->Mailings->saveMany($save)){
				$this->Flash->success(__('Relances de début d\'année créées.'));
				return $this->redirect(['controller'=>'mailings','action' => 'index']);
			}
			
			$this->Flash->error(__('Relances de début d\'année non créées.'));
			
		} else {
			
			$this->Flash->error(__('Aucun message de relance n\'a pu être créé dans les mails.'));
			return $this->redirect($this->referer());
			
		}

    }
}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;


/**
 * Comptabilite Controller
 *
 * @property \App\Model\Table\ComptabiliteTable $Comptabilite
 *
 * @method \App\Model\Entity\Comptabilite[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ComptabiliteController extends AppController
{
	var $navigation = [[ 'label' => 'Recherche DPS', 'config' => [ 'controller' => 'RechercheDps', 'action' => 'index' ]]];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $demande = TableRegistry::get('Demandes');
        $demandes = $demande->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
                                                   				'sort' => ['Dimensionnements.horaires_debut' => 'ASC']],'Antennes']]);
        $demandesFacturees = $demande->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
                                                                'sort' => ['Dimensionnements.horaires_debut' => 'ASC']],'Antennes']])->where ('ordre = 13');
        $demandesAttenteFacture = $demande->find('all',['contain'=>['ConfigEtats','Organisateurs','Dimensionnements'=>[
                                                                'sort' => ['Dimensionnements.horaires_debut' => 'ASC']],'Antennes']])->where ('ordre = 10');
        $this->set(compact('demandes','demandesFacturees','demandesAttenteFacture','navigation'));

    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function cleaning()
    {

		$this->autoRender = false;

		$Comptabilite = $this->Comptabilite->find('all');

		foreach($Comptabilite as $Comptabilite){
			$save = $this->Comptabilite->get($Comptabilite->id);

			foreach($Comptabilite->toArray() as $key => $val){
				$save->set($key,$val);
			}

			$this->Comptabilite->save($save);
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
		$Comptabilite_id = (int) $this->Wizard->getDatas('Comptabilite.id');
		$demande_id = (int) $this->Wizard->getDatas('Demandes.id');

		if(!empty($Comptabilite_id)){

			$this->viewBuilder()->setTemplate('wizard_edit');

			$this->Flash->success(__('Vous vous apprêtez à modifier les coordonnées d\'un Comptabilite, cette modification ne sera que pour ce dossier.'));

			$Comptabilite = $this->Comptabilite->get($Comptabilite_id);

			if ($this->request->is(['patch', 'post', 'put'])) {

				$Comptabilite = $this->Comptabilite->patchEntity($Comptabilite, $this->request->getData());

				if( $Comptabilite->isDirty() ){

					//$Comptabilite->set('publish',0);
					$this->Comptabilite->save($Comptabilite);

					//unset( $this->request->data['id'] );

					//$new = $this->Comptabilite->newEntity();
					//$Comptabilite = $this->Comptabilite->patchEntity($new, $this->request->getData());
					//$Comptabilite->set('publish',1);

					if ($this->Comptabilite->save($Comptabilite)) {
						$this->Flash->success(__('L\'Comptabilite a été sauvegardé.'));

						//return $this->redirect(['action' => 'index']);
						return $this->Wizard->redirect(['action' => 'index']);
					}

					$this->Flash->error(__('The Comptabilite could not be saved. Please, try again.'));

				} else {
					$this->Flash->error(__('No changes detected. Please, try again or quit by click on list button.'));
					//return $this->redirect(['action' => 'index']);
					return $this->Wizard->redirect(['action' => 'index']);
				}

			}

			$navigation = $this->navigation;

			$this->set(compact('Comptabilite','navigation'));

		} else {

			//$this->Flash->error(__('Vérifiez les coordonnées de l\'Comptabilite demandeur en cliquant sur le crayon avant de le sélectionner !'));

			$query = $this->Comptabilite->find('published');

			$Comptabilite = $this->paginate($query);

			$query = $this->Comptabilite->find('published');
			$Comptabilite = $this->Comptabilite->find('all',['select'=>['MAX(id) AS id','*'],'group'=>['uuid']])
											->order(['nom'=>'ASC'])
											->toArray();

			$navigation = $this->navigation;

			$this->set(compact('Comptabilite','navigation'));

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
     * @param string|null $id Comptabilite id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $Comptabilite = $this->Comptabilite->get($id, [
            'contain' => ['Demandes']
        ]);

        $this->set('Comptabilite', $Comptabilite);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $Comptabilite = $this->Comptabilite->newEntity(['uuid' => uniqid('ORG'.rand(1000,9999),true),'publish'=>1]);

        if ($this->request->is('post')) {

            $Comptabilite = $this->Comptabilite->patchEntity($Comptabilite, $this->request->getData());

            if ($this->Comptabilite->save($Comptabilite)) {
                $this->Flash->success(__('L\'Comptabilite a été sauvegardé.'));

                return $this->Wizard->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The Comptabilite could not be saved. Please, try again.'));
        }

		$navigation = $this->navigation;

        $this->set(compact('Comptabilite','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Comptabilite id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $Comptabilite = $this->Comptabilite->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

			$Comptabilite = $this->Comptabilite->patchEntity($Comptabilite, $this->request->getData());

			if( $Comptabilite->isDirty() ){

				$Comptabilite->set('publish',0);
				$this->Comptabilite->save($Comptabilite);

				unset( $this->request->data['id'] );

				$new = $this->Comptabilite->newEntity();
				$Comptabilite = $this->Comptabilite->patchEntity($new, $this->request->getData());
				$Comptabilite->set('publish',1);

				if ($this->Comptabilite->save($Comptabilite)) {
					$this->Flash->success(__('L\'Comptabilite a été sauvegardé.'));

					//return $this->redirect(['action' => 'index']);
					return $this->Wizard->redirect(['action' => 'index']);
				}

				$this->Flash->error(__('The Comptabilite could not be saved. Please, try again.'));

			} else {
				$this->Flash->error(__('No changes detected. Please, try again or quit by click on list button.'));
				//return $this->redirect(['action' => 'index']);
				return $this->Wizard->redirect(['action' => 'index']);
			}

        }

		$navigation = $this->navigation;

        $this->set(compact('Comptabilite','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Comptabilite id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $Comptabilite = $this->Comptabilite->get($id);

		if ($this->Comptabilite->delete($Comptabilite)) {
            $this->Flash->success(__('The Comptabilite has been deleted.'));
        } else {
            $this->Flash->error(__('The Comptabilite could not be deleted. Please, try again.'));
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

		$mails = $this->Comptabilite->find('list',['valueField'=>'mail'])->distinct(['uuid'])->toArray();

		$this->loadModel('Mails');
		$this->loadModel('Mailings');

		$message = $this->Mails->find()->where(['controller'=>'Comptabilite','action'=>'relance','publish'=>1])->first();

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

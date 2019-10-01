<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
use Cake\Log\Log;

/**
 * Dispositifs Controller
 *
 * @property \App\Model\Table\DispositifsTable $Dispositifs
 *
 * @method \App\Model\Entity\Dispositif[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DispositifsController extends AppController
{
	var $navigation = [
		[ 'label' => 'New dispositif', 'config' => [ 'controller' => 'Dispositifs', 'action' => 'add' ]],
		[ 'label' => 'List dispositif', 'config' => [ 'controller' => 'Dispositifs', 'action' => 'index' ]],
		[ 'label' => 'List Dimensionnements', 'config' => ['controller' => 'Dimensionnements', 'action' => 'index' ]],
		[ 'label' => 'Add Dimensionnements', 'config' => ['controller' => 'Dimensionnements', 'action' => 'add' ]],
		[ 'label' => 'List ConfigTypepublics', 'config' => ['controller' => 'ConfigTypepublics', 'action' => 'index' ]],
		[ 'label' => 'Add ConfigTypepublics', 'config' => ['controller' => 'ConfigTypepublics', 'action' => 'add' ]],
		[ 'label' => 'List ConfigEnvironnements', 'config' => ['controller' => 'ConfigEnvironnements', 'action' => 'index' ]],
		[ 'label' => 'Add ConfigEnvironnements', 'config' => ['controller' => 'ConfigEnvironnements', 'action' => 'add' ]],
		[ 'label' => 'List ConfigDelais', 'config' => ['controller' => 'ConfigDelais', 'action' => 'index' ]],
		[ 'label' => 'Add ConfigDelais', 'config' => ['controller' => 'ConfigDelais', 'action' => 'add' ]],
		[ 'label' => 'List Equipes', 'config' => ['controller' => 'Equipes', 'action' => 'index' ]],
		[ 'label' => 'Add Equipes', 'config' => ['controller' => 'Equipes', 'action' => 'add' ]],
    ];

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Dimensionnements', 'ConfigTypepublics', 'ConfigEnvironnements', 'ConfigDelais']
        ];
        $dispositifs = $this->paginate($this->Dispositifs);

		$navigation = $this->navigation;

        $this->set(compact('dispositifs','navigation'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function wizard()
    {
		$demande_id = (int) $this->Wizard->getDatas('Demandes.id');

		$dispositifs = $this->Dispositifs->find('all',[
			'contain' => ['Dimensionnements.Demandes','Equipes','ConfigTypepublics', 'ConfigEnvironnements', 'ConfigDelais']
        ])->where(['demande_id'=>$demande_id]);

		$ids = Hash::extract($dispositifs->toArray(),'{n}.id');

		$this->Wizard->injectDatas('Dispositifs.id',$ids);

		$dispositif_ids = $dispositifs->combine('id','dimensionnement_id')->toArray();

		$dimensionnements = $this->Dispositifs->Dimensionnements->find('all')
													->where(['demande_id'=>$demande_id]);
		if(!empty($dispositif_ids)){
			$dimensionnements = $dimensionnements->where(['id NOT IN'=>$dispositif_ids]);
		}

		//$this->Flash->success(__('The dispositif has been saved.'));

		$dimensionnements = $dimensionnements->orderAsc('horaires_debut')
											->map(function ($row) { // map() est une méthode de collection, elle exécute la requête
												$row->arraycombine = $row->intitule . ' du ' . h($row->horaires_debut) .' au '.h($row->horaires_fin);
												return $row;
											})
											->combine('id','arraycombine')
											->toArray();

		$navigation = $this->navigation;

        $this->set(compact('dispositifs','navigation','dimensionnements','demande_id'));
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
     * @param string|null $id Dispositif id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $dispositif = $this->Dispositifs->get($id, [
            'contain' => [
						'Dimensionnements',
						'ConfigTypepublics',
						'ConfigEnvironnements',
						'ConfigDelais',
						'Equipes'
						]
			]
		);

        $this->set('dispositif', $dispositif);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($dimensionnements_id = 0)
    {
        $dispositif = $this->Dispositifs->newEntity();

        if ($this->request->is('post')) {

            $dispositif = $this->Dispositifs->patchEntity($dispositif, $this->request->getData());

            if($result = $this->Dispositifs->save($dispositif)) {
                $this->Flash->success(__('Le dispositif a bien été sauvegardé.'));

                return $this->redirect(['action' => 'edit',$result->id]);
            }

            $this->Flash->error(__('Le dispositif n\'a pas été sauvegardé. Merci de rééssayer.'));
        }

        $dimensionnements = $this->Dispositifs->Dimensionnements->find('list', ['limit' => 200]);

		if( !empty($dimensionnements_id)){
			$dimensionnements = $this->Dispositifs->Dimensionnements->alone($dimensionnements_id);
		}

        $configTypepublics = $this->Dispositifs->ConfigTypepublics->listing();
        $configEnvironnements = $this->Dispositifs->ConfigEnvironnements->listing();
        $configDelais = $this->Dispositifs->ConfigDelais->listing();

		$navigation = $this->navigation;

        $this->set(compact('dispositif', 'dimensionnements', 'configTypepublics', 'configEnvironnements', 'configDelais','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Dispositif id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $dispositif = $this->Dispositifs->get($id, [
            'contain' => []
        ]);

        /* Actions = Recalculer ou Sortir */
		$actions = (int) $this->request->getData('actions');

        if ($this->request->is(['patch', 'post', 'put'])) {

			$dispositif = $this->Dispositifs->patchEntity($dispositif, $this->request->getData());

            $msgErreurVerif = $this->verifierAvantSauvegarde($dispositif);

            if($msgErreurVerif == null){
                if ($result = $this->Dispositifs->save($dispositif)) {
                    $this->Flash->success(__('Le dispositif a bien été sauvegardé.'));

                    if($actions){
                        return $this->redirect(['action' => 'index']);
                    }

                    return $this->redirect(['action' => 'edit',$result->id]);

                } else {
                    $this->Flash->error(__('Le dispositif n\'a pas été sauvegardé. Merci de rééssayer.'));
                }
			}else{
			    $this->Flash->error(__($msgErreurVerif));
			}
        }

        $dimensionnements = $this->Dispositifs->Dimensionnements->find('list', ['limit' => 200]);

        $configTypepublics = $this->Dispositifs->ConfigTypepublics->listing();
        $configEnvironnements = $this->Dispositifs->ConfigEnvironnements->listing();
        $configDelais = $this->Dispositifs->ConfigDelais->listing();

		$navigation = $this->navigation;

        $this->set(compact('dispositif', 'dimensionnements', 'configTypepublics', 'configEnvironnements', 'configDelais','navigation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function generate( $id = 0, $reset = 0)
    {
		$this->request->allowMethod(['post', 'generate']);

		$this->autoRender = false;

		if($reset == 1){
			$this->Dispositifs->resetByDemande($id);
		}

        $this->Dispositifs->generateSave($id);

		return $this->redirect(['controller'=>'Demandes','action'=>'view',$id]);

    }

    /**
     * Delete method
     *
     * @param string|null $id Dispositif id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $dispositif = $this->Dispositifs->get($id);
        if ($this->Dispositifs->delete($dispositif)) {
            $this->Flash->success(__('Le dispositif a bien été supprimé.'));
        } else {
            $this->Flash->error(__('Le dispositif n\'a pas pu être supprimé. Merci de rééssayer.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Vérification du dispositif avant sauvegarde.
     * Renvoie un message d'erreur si la vérification a échoué. Renvoie null si succès.
     */
    public function verifierAvantSauvegarde($dispositif = null){
        if($dispositif != null){
            // Vérification de la somme des membres de l'équipe avec le personnel total, excepté les stagiaires.
            $sommePersonnel = $dispositif->nb_chef_equipe + $dispositif->nb_pse2 + $dispositif->nb_pse1 + $dispositif->nb_lat + $dispositif->nb_medecin + $dispositif->nb_infirmier + $dispositif->nb_cadre_operationnel;
            $totalPersonnel = $dispositif->personnels_total;
            return (($sommePersonnel == $totalPersonnel) ? NULL : 'Merci de vérifier le nombre de chef d\'équipe, pse2... (actuellement '.$sommePersonnel.') pour qu\'il concorde avec le nombre de personnel total ('.$totalPersonnel.')');
        }
        return null;
    }

}

<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time; // ADD TEST
use Cake\Utility\Hash;

/**
 * Equipes Controller
 *
 * @property \App\Model\Table\EquipesTable $Equipes
 *
 * @method \App\Model\Entity\Equipe[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EquipesController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New equipe', 'config' => [ 'controller' => 'Equipes', 'action' => 'add' ]],
		[ 'label' => 'List equipe', 'config' => [ 'controller' => 'Equipes', 'action' => 'index' ]],
		[ 'label' => 'List Dispositifs', 'config' => ['controller' => 'Dispositifs', 'action' => 'index' ]],
		[ 'label' => 'Add Dispositifs', 'config' => ['controller' => 'Dispositifs', 'action' => 'add' ]],
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
        $this->paginate = [
            'contain' => ['Dispositifs']
        ];
        $equipes = $this->paginate($this->Equipes);
		
		$navigation = $this->navigation;

        $this->set(compact('equipes','navigation'));
    }

	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function pdf()
    {	
		$demande_id = (int) $this->Wizard->getDatas('Demandes.id'); 

		$equipes = $this->Equipes->find('all',[
			'contain' => ['Dispositifs.Dimensionnements.Demandes']
        ])->where(['demande_id'=>$demande_id]);
		
		$dispositifs = $this->Equipes->Dispositifs->find('all',['contain'=>['Dimensionnements']])
													->where(['demande_id'=>$demande_id])
													->orderAsc('horaires_debut')    
													->map(function ($row) { // map() est une méthode de collection, elle exécute la requête
														$row->arraycombine = $row->title .' '. $row->dimensionnement->du_au;
														return $row;
													})
													->combine('id','arraycombine')
													->toArray();
		$navigation = $this->navigation;

        $this->set(compact('equipes','navigation','dispositifs'));

		$this->viewBuilder()
                ->className('Dompdf.Pdf')
                ->layout('default2')
                ->options(['config' => [
                        'filename' => 'equipes_' . $demande_id,
                        'render' => 'browser',
                        'size' => 'A3',
						'orientation'=>'landscape',
                        'dpi' => 150
        ]]);
    }
	
	/**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function wizard()
    {	
		$demande_id = (int) $this->Wizard->getDatas('Demandes.id'); 

		// $equipes = $this->Equipes->find('all',[
			// 'contain' => ['Dispositifs.Dimensionnements.Demandes']
        // ])->where(['Dimensionnements.demande_id'=>$demande_id]);

		$dispositifs = $this->Equipes->Dispositifs->find('all',['contain'=>['Dimensionnements']])
													->where(['Dimensionnements.demande_id'=>$demande_id])
													->orderAsc('horaires_debut')    
													->map(function ($row) { // map() est une méthode de collection, elle exécute la requête
														$row->arraycombine = $row->title .' '. $row->dimensionnement->du_au;
														return $row;
													})
													->combine('id','arraycombine')
													->toArray();
		
		$equipes = $this->Equipes->find('all')
								->contain(['Dispositifs.Dimensionnements'])
								->where(['Dimensionnements.demande_id'=>$demande_id])
								->orderAsc('Dimensionnements.horaires_debut')
								->orderAsc('Dimensionnements.id')
								->orderAsc('Equipes.horaires_convocation')
								->toArray();
		
		$limits['start'] = Hash::extract($equipes,'{n}.dispositif.dimensionnement.strtotime_debut');
		$limits['end'] = Hash::extract($equipes,'{n}.dispositif.dimensionnement.strtotime_fin');
		$limits['convocation'] = Hash::extract($equipes,'{n}.strtotime_convocation');
		$limits['retour'] = Hash::extract($equipes,'{n}.strtotime_retour');
		
		$limits = array_merge($limits['start'],$limits['end'],$limits['convocation'],$limits['retour']);
		//$limits = array_unique($limits);
		
		sort($limits);
		
		$first = reset($limits);
		$last = end($limits);

		//$first = $first - 7200;
		//$last = $last + 7200;
		
		$first = strtotime(date('Y-m-d 00:00:00',$first));
		$last = strtotime(date('Y-m-d 24:00:00',$last));

		
		$total = $last - $first;
		
		$strtotime['heure'] = $this->Pourcentages->getPourcent(3600,$total);
		$strtotime['demi'] = $this->Pourcentages->getPourcent(1800,$total);
		$strtotime['quart'] = $this->Pourcentages->getPourcent(900,$total);
		$strtotime['day'] = $this->Pourcentages->getPourcent(86400,$total);
		$strtotime['12h'] = $this->Pourcentages->getPourcent(43200,$total);
		$strtotime['6h'] = $this->Pourcentages->getPourcent(21600,$total);
		$strtotime['start'] = date('d-m-Y 00:00',$first);
		$strtotime['end'] = date('d-m-Y 24:00',$last);		

		foreach($equipes as $equipe){
			$virtual = (array) $equipe->getVirtual();
			$virtual = array_merge($virtual,['pourcent_convocation','pourcent_place','pourcent_fin','pourcent_retour','pourcent_reste']);
			
			$equipe->setVirtual($virtual);
			
			$strtotime_convocation = $this->Pourcentages->getPourcent($equipe->get('strtotime_convocation'),$total,$first);
			$strtotime_place = $this->Pourcentages->getPourcent($equipe->get('strtotime_place'),$total,$equipe->get('strtotime_convocation'));
			$strtotime_fin = $this->Pourcentages->getPourcent($equipe->get('strtotime_fin'),$total,$equipe->get('strtotime_place'));
			$strtotime_retour = $this->Pourcentages->getPourcent($equipe->get('strtotime_retour'),$total,$equipe->get('strtotime_fin'));
			$strtotime_reste = $this->Pourcentages->getPourcent($last,$total,$equipe->get('strtotime_retour'));
			
			$equipe->set('pourcent_convocation',$strtotime_convocation);
			$equipe->set('pourcent_place',$strtotime_place);
			$equipe->set('pourcent_fin',$strtotime_fin);
			$equipe->set('pourcent_retour',$strtotime_retour);
			$equipe->set('pourcent_reste',$strtotime_reste);
			
		}

		$this->Equipes->timing('2019-01-06 12:30','2019-01-06 16:30');
		$navigation = $this->navigation;

        $this->set(compact('equipes','navigation','dispositifs','demande_id','strtotime'));
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
     * @param string|null $id Equipe id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $equipe = $this->Equipes->get($id, [
            'contain' => ['Dispositifs', 'Personnels']
        ]);

        $this->set('equipe', $equipe);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($dispositif_id=0)
    {
		
        $dispositif = $this->Equipes->Dispositifs->get($dispositif_id, [
            'contain' => ['Dimensionnements']
        ]);
				
		$preset = $this->Equipes->preset( $dispositif_id );
		
        $equipe = $this->Equipes->newEntity($preset);
		
		if(! $this->request->is('post')){
			$equipe->clean();
		}
		
        if ($this->request->is('post')) {
			
			/* 			
			$this->loadModel('ConfigParametres');
			
			$parametres = $this->ConfigParametres->find('all')->last();
						
			$taux['horaire'] = $parametres->cout_personnel;
			$taux['km'] = $parametres->cout_kilometres;
			$taux['repas'] = $parametres->cout_repas;
			$taux['repartition'] = $parametres->pourcentage; 
			*/
		
            $equipe = $this->Equipes->patchEntity($equipe, $this->request->getData());
			
			$this->Equipes->calculs( $equipe );
			
            if ($this->Equipes->save($equipe)) {
                $this->Flash->success(__('The equipe has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The equipe could not be saved. Please, try again.'));
        }
		
		if(!empty($dispositif_id )){
			$dispositifs = $this->Equipes->Dispositifs->alone( $dispositif_id );
		} else {
			$dispositifs = $this->Equipes->Dispositifs->listing();
		}
        
        $personnels = $this->Equipes->Personnels->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('equipe', 'dispositifs', 'personnels','navigation'));
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
			$this->Equipes->resetByDemande($id);
		}
		
        $this->Equipes->generateSave($id);
		
		return $this->redirect(['controller'=>'Demandes','action'=>'view',$id]);

    }

    /**
     * Edit method
     *
     * @param string|null $id Equipe id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $equipe = $this->Equipes->get($id, [
            'contain' => ['Personnels']
        ]);
		
        if ($this->request->is(['patch', 'post', 'put'])) {
			
			$this->loadModel('ConfigParametres');
			
			$parametres = $this->ConfigParametres->find('all')->last();
						
			$taux['horaire'] = $parametres->cout_personnel;
			$taux['km'] = $parametres->cout_kilometres;
			$taux['repas'] = $parametres->cout_repas;
			$taux['repartition'] = $parametres->pourcentage;

            $equipe = $this->Equipes->patchEntity($equipe, $this->request->getData());
			
			$this->Equipes->calculs( $equipe, $taux );
		
            if ($this->Equipes->save($equipe)) {
                $this->Flash->success(__('The equipe has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The equipe could not be saved. Please, try again.'));
        }
        $dispositifs = $this->Equipes->Dispositifs->find('list', ['limit' => 200]);
        $personnels = $this->Equipes->Personnels->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('equipe', 'dispositifs', 'personnels','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Equipe id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
		
        $equipe = $this->Equipes->get($id);
        
		if ($this->Equipes->delete($equipe)) {
            $this->Flash->success(__('The equipe has been deleted.'));
        } else {
            $this->Flash->error(__('The equipe could not be deleted. Please, try again.'));
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
    public function duplicate($id = null)
    {	
		$this->autoRender = false;
        
		$this->request->allowMethod(['post', 'duplicate']);

		if ($this->Equipes->duplicate($id)) {
			$this->Flash->success(__('The equipe has been duplicate.'));
        } else {
            $this->Flash->error(__('The equipe could not be duplicate. Please, try again.'));
        }

       return $this->redirect(['action' => 'index']);
    }
}

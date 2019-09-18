<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PersonnelsAntennes Controller
 *
 * @property \App\Model\Table\PersonnelsAntennesTable $PersonnelsAntennes
 *
 * @method \App\Model\Entity\PersonnelsAntenne[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PersonnelsAntennesController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New personnelsAntenne', 'config' => [ 'controller' => 'PersonnelsAntennes', 'action' => 'add' ]],
		[ 'label' => 'List personnelsAntenne', 'config' => [ 'controller' => 'PersonnelsAntennes', 'action' => 'index' ]],
		[ 'label' => 'List Antennes', 'config' => ['controller' => 'Antennes', 'action' => 'index' ]],
		[ 'label' => 'Add Antennes', 'config' => ['controller' => 'Antennes', 'action' => 'add' ]],
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
            'contain' => ['Antennes', 'Personnels']
        ];
        $personnelsAntennes = $this->paginate($this->PersonnelsAntennes);
		
		$navigation = $this->navigation;

        $this->set(compact('personnelsAntennes','navigation'));
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
     * @param string|null $id Personnels Antenne id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $personnelsAntenne = $this->PersonnelsAntennes->get($id, [
            'contain' => ['Antennes', 'Personnels']
        ]);

        $this->set('personnelsAntenne', $personnelsAntenne);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $personnelsAntenne = $this->PersonnelsAntennes->newEntity();
        if ($this->request->is('post')) {
            $personnelsAntenne = $this->PersonnelsAntennes->patchEntity($personnelsAntenne, $this->request->getData());
            if ($this->PersonnelsAntennes->save($personnelsAntenne)) {
                $this->Flash->success(__('The personnels antenne has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The personnels antenne could not be saved. Please, try again.'));
        }
        $antennes = $this->PersonnelsAntennes->Antennes->find('list', ['limit' => 200]);
        $personnels = $this->PersonnelsAntennes->Personnels->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('personnelsAntenne', 'antennes', 'personnels','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Personnels Antenne id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $personnelsAntenne = $this->PersonnelsAntennes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $personnelsAntenne = $this->PersonnelsAntennes->patchEntity($personnelsAntenne, $this->request->getData());
            if ($this->PersonnelsAntennes->save($personnelsAntenne)) {
                $this->Flash->success(__('The personnels antenne has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The personnels antenne could not be saved. Please, try again.'));
        }
        $antennes = $this->PersonnelsAntennes->Antennes->find('list', ['limit' => 200]);
        $personnels = $this->PersonnelsAntennes->Personnels->find('list', ['limit' => 200]);
		
		$navigation = $this->navigation;
		
        $this->set(compact('personnelsAntenne', 'antennes', 'personnels','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Personnels Antenne id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $personnelsAntenne = $this->PersonnelsAntennes->get($id);
        if ($this->PersonnelsAntennes->delete($personnelsAntenne)) {
            $this->Flash->success(__('The personnels antenne has been deleted.'));
        } else {
            $this->Flash->error(__('The personnels antenne could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

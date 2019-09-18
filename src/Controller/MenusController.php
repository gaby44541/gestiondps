<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Schema\Collection;

/**
 * Menus Controller
 *
 * @property \App\Model\Table\MenusTable $Menus
 *
 * @method \App\Model\Entity\Menu[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MenusController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New menu', 'config' => [ 'controller' => 'Menus', 'action' => 'add' ]],
		[ 'label' => 'List menu', 'config' => [ 'controller' => 'Menus', 'action' => 'index' ]],
		[ 'label' => 'List ParentMenus', 'config' => ['controller' => 'ParentMenus', 'action' => 'index' ]],
		[ 'label' => 'Add ParentMenus', 'config' => ['controller' => 'ParentMenus', 'action' => 'add' ]],
		[ 'label' => 'List ChildMenus', 'config' => ['controller' => 'ChildMenus', 'action' => 'index' ]],
		[ 'label' => 'Add ChildMenus', 'config' => ['controller' => 'ChildMenus', 'action' => 'add' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        // $this->paginate = [
            // 'contain' => ['ParentMenus'],
			// 'order' => array('lft')
        // ];
        //$menus = $this->paginate($this->Menus);
		$menus = $this->Menus->find('all', array(
			'order' => array('lft')
		));
		
		$navigation = $this->navigation;

        $this->set(compact('menus','navigation'));
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
     * @param string|null $id Menu id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $menu = $this->Menus->get($id, [
            'contain' => ['ParentMenus', 'ChildMenus']
        ]);

        $this->set('menu', $menu);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $menu = $this->Menus->newEntity();
        if ($this->request->is('post')) {
            $menu = $this->Menus->patchEntity($menu, $this->request->getData());
            if ($this->Menus->save($menu)) {
                $this->Flash->success(__('The menu has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The menu could not be saved. Please, try again.'));
        }
        $parentMenus = $this->Menus->ParentMenus->find('treeList',['spacer'=>'--']);
		
		$navigation = $this->navigation;
		
        $this->set(compact('menu', 'parentMenus','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Menu id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $menu = $this->Menus->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $menu = $this->Menus->patchEntity($menu, $this->request->getData());
            if ($this->Menus->save($menu)) {
                $this->Flash->success(__('The menu has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The menu could not be saved. Please, try again.'));
        }
        $parentMenus = $this->Menus->ParentMenus->find('treeList',['spacer'=>'--']);
		
		$navigation = $this->navigation;
		
        $this->set(compact('menu', 'parentMenus','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Menu id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $menu = $this->Menus->get($id);
        if ($this->Menus->delete($menu)) {
            $this->Flash->success(__('The menu has been deleted.'));
        } else {
            $this->Flash->error(__('The menu could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

	public function moveup($id = null)
    {
        $this->request->allowMethod(['post', 'put', 'get']);
        $menu = $this->Menus->get($id);
        if ($this->Menus->moveUp($menu)) {
            $this->Flash->success(__('The menu has been moved Up.'));
        } else {
            $this->Flash->error(__('The menu could not be moved up. Please, try again.'));
        }
        return $this->redirect($this->referer(['action' => 'index']));
    }

    public function movedown($id = null)
    {
        $this->request->allowMethod(['post', 'put', 'get']);
        $menu = $this->Menus->get($id);
        if ($this->Menus->moveDown($menu)) {
            $this->Flash->success(__('The menu has been moved down.'));
        } else {
            $this->Flash->error(__('The menu could not be moved down. Please, try again.'));
        }
        return $this->redirect($this->referer(['action' => 'index']));
    }
}

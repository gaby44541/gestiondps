<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * ConfigParametres Controller
 *
 * @property \App\Model\Table\ConfigParametresTable $ConfigParametres
 *
 * @method \App\Model\Entity\ConfigParametre[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigParametresController extends AppController
{
	var $navigation = [ 
		[ 'label' => 'New configParametre', 'config' => [ 'controller' => 'ConfigParametres', 'action' => 'add' ]],
		[ 'label' => 'List configParametre', 'config' => [ 'controller' => 'ConfigParametres', 'action' => 'index' ]],
    ];
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $configParametres = $this->paginate($this->ConfigParametres);
		
		$navigation = $this->navigation;

        $this->set(compact('configParametres','navigation'));
    }

	public function backupbis(){
		
		$this->autoRender = false;
		
	    // Force le controller à rendre une réponse JSON.
        $this->RequestHandler->renderAs($this, 'json');
		
        // Définit le type de réponse de la requete AJAX
        $this->response->type('application/json');

        // Chargement du layout AJAX
        $this->viewBuilder()->layout('ajax');
		
		$uuid = 'gestiondps_'.date('Ymdhis').'_'.uniqid();
		
		$folder = new Folder();
		$folder->create(TMP.'backup');
		
		$path = TMP.'backup'.DS.$uuid;
		
		exec('mysqldump --user=c2dps --password=qppubV_NH6Z --host=localhost c2gestiondps | gzip > '.$path.'.sql.gz');
		
		$email = new Email('default');

		$email->setTransport('mobilia')
			->setFrom('gabriel.boursier@loire-atlantique.protection-civile.org')
			->setTemplate('default','default')
			->setEmailFormat('both')
			->setTo('gabriel.boursier@loire-atlantique.protection-civile.org')
			->setAttachments($path.'.sql.gz')
			->setSubject('Sauvegarde BDD Gestion opérationnel')
			->setReadReceipt('gabriel.boursier@loire-atlantique.protection-civile.org')
			->setReturnPath('gabriel.boursier@loire-atlantique.protection-civile.org')
			->setReplyTo('gabriel.boursier@loire-atlantique.protection-civile.org')
			->send('Sauvegarde de la BDD opérationnelle au '.date('Y-m-d H:i:s'));
			
		$file = new File($path.'.sql.gz');
		$file->delete();
		$file->close();
/*		
		// Chargement des données
		//$json_data[] = ['id'=>2500,'title'=>'test','start'=>'2018-07-16 12:30:00','end'=>'2018-07-18 05:45:00','url'=>'http://localhost/crud/antennes/view/1'];
		//$json_data[] = ['id'=>2501,'title'=>'test','start'=>'2018-07-18 05:45:00','end'=>'2018-07-27'];//,'rendering'=>'background'
		
        $db = ConnectionManager::get('default');

        // Create a schema collection.
        $collection = $db->schemaCollection();
		
		// Get all tables
		$tables = $collection->listTables();
		
		foreach( $tables as $table ){
			
			// Get a single table (instance of Schema\TableSchema)
			$tableSchema = $collection->describe($table);
			
			//Get columns list from table
			$columns = $tableSchema->columns();	

			$return[] = $tableSchema->dropSql($db)[0];
			$return[] = $tableSchema->createSql($db)[0];
			$return[] = 'INSERT INTO '.$table.' (`'.implode('`,`',$tableSchema->columns()).'`) VALUES';
			
			$model = $this->loadModel($table);

			var_dump(implode(PHP_EOL.PHP_EOL,$return));			
		}
*/
		//$tableSchema->column( $column );
		$response = $this->response->withType('json')->withStringBody(json_encode($json_data));
		
		// Retour des données encodées en JSON
		//var_dump( $response );
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
     * @param string|null $id Config Parametre id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $configParametre = $this->ConfigParametres->get($id, [
            'contain' => []
        ]);

        $this->set('configParametre', $configParametre);
		$this->set('navigation', $this->navigation );
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $configParametre = $this->ConfigParametres->newEntity();
        if ($this->request->is('post')) {
            $configParametre = $this->ConfigParametres->patchEntity($configParametre, $this->request->getData());
            if ($this->ConfigParametres->save($configParametre)) {
                $this->Flash->success(__('The config parametre has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config parametre could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configParametre','navigation'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config Parametre id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $configParametre = $this->ConfigParametres->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $configParametre = $this->ConfigParametres->patchEntity($configParametre, $this->request->getData());
            if ($this->ConfigParametres->save($configParametre)) {
                $this->Flash->success(__('The config parametre has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config parametre could not be saved. Please, try again.'));
        }
		
		$navigation = $this->navigation;
		
        $this->set(compact('configParametre','navigation'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config Parametre id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $configParametre = $this->ConfigParametres->get($id);
        if ($this->ConfigParametres->delete($configParametre)) {
            $this->Flash->success(__('The config parametre has been deleted.'));
        } else {
            $this->Flash->error(__('The config parametre could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

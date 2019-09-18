<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Hash;

class TcpdfGanttHelper extends Helper 
{
	
	public $helpers = [
		'Form' => [
			'className' => 'Bootstrap.Form',
			'widgets' => [
				'datetimepicking' => ['DateTimePicking']
			]
		],
		'Html' => [
			'className' => 'Bootstrap.Html'
		],
		'Modal' => [
			'className' => 'Bootstrap.Modal'
		],
		'Navbar' => [
			'className' => 'Bootstrap.Navbar'
		],
		'Paginator' => [
			'className' => 'Bootstrap.Paginator'
		],
		'Panel' => [
			'className' => 'Bootstrap.Panel'
		]
	];

	protected $_defaultConfig = [
		'colors' => [
			'mission'=>'progress-bar-primary',
			'trajet'=>'progress-bar-info',
			'duree'=>'progress-bar-success'
		],
		'limits' => [
			'mini' => '2018-07-15 12:00:00',
			'maxi' => '2018-07-18 12:00:00',
		],
		'fields' => [
			'convocation'=>'horaires_convocation',
			'ouverture'=>'horaires_place',
			'fermeture'=>'horaires_fin',
			'retour'=>'horaires_retour',
		],
		'datas'=>[
			'model'=>'Equipes',
			'conditions'=>['order'=>['dispositif_id'=>'asc','horaires_convocation'=>'asc']]
		],
		'regle'=>5,
		'size'=>410,
		'marge'=>5
	];

	/**
     * Construct the widgets and binds the default context providers
     *
     * @param \Cake\View\View $View The View this helper is being attached to.
     * @param array $config Configuration settings for the helper.
     */
    // public function __construct(View $View, array $config = [])
    // {
        // parent::__construct($View, $config);

        // $this->_limits = $this->getConfig('limits');
		// $this->_fields = $this->getConfig('fields');
    // }
	
	public function getLimits(	$where = [],
								$conditions = [],
								$model = 'Dimensionnements',
								$debut = 'horaires_debut',
								$end = 'horaires_fin') {
		// Force type
		$model = (string) $model;
		$conditions = (array) $conditions;
		$where = (array) $where;
		
		// Get datas from the table
		$datas = TableRegistry::get($model);
		
		$min =  $datas->find('all',$conditions)
						->select($debut)
						->where($where)
						->min($debut);
						
		$max =  $datas->find('all',$conditions)
						->select($end)
						->where($where)
						->max($end);
		
		$limit_min = $min->{$debut}->toUnixString();
		$limit_max = $max->{$end}->toUnixString();

		$min = 0;
		$max = 12;
			
		$min_h = (int)date('H',$limit_min);
			
		if($min_h >= 12 ){
			$min = 12;
		}
			
		$max_h = (int)date('H',$limit_max);
		
		if($max_h >= 12 ){
			$max = 24;
		}
			
		$limits['mini'] = date('Y-m-d '.$min.':00:00',$limit_min);
		$limits['maxi'] = date('Y-m-d '.$max.':00:00',$limit_max);

		$this->setConfig('limits',$limits);
		
    }
	
	public function getDatas() 
	{
		$fields = $this->getConfig('fields');
		$config = $this->getConfig('datas');

		extract($config);
		extract($fields);
		
		// Force type
		$model = (string) $model;
		$conditions = (array) $conditions;
		
		// Get datas from the table
		$datas = TableRegistry::get($model);
		
		if(isset($conditions['where'])){
			$where = $conditions['where'];
			unset( $conditions['where'] );
			// Query database
			$datas =  $datas->find('all',$conditions)
							->where($where)
							->toArray();
		} else {
			// Query database
			$datas =  $datas->find('all',$conditions)
							->toArray();
		}

		return $datas;
    }
	
	public function formatDatas( $datas ) 
	{
		$fields = $this->getConfig('fields');
		$config = $this->getConfig('datas');
		$limits = $this->getConfig('limits');

		extract($config);
		extract($fields);
		
		$return = [];
		if(empty($limits))
		{			
			$limit_min = 0;
			$limit_max = 0;
			
			$limits = [];
			$timer = [];
			
			foreach($datas as $data){
				$date_min = $data->{$convocation}->toUnixString();
				$date_max = $data->{$retour}->toUnixString();
				
				$timer[] = $date_min;
				$timer[] = $date_max;
			}
				
			asort($timer);

			$limit_min = array_shift($timer);
			$limit_max = array_pop($timer);

			$min = 0;
			$max = 12;
			
			$min_h = (int)date('H',$limit_min);
			
			if($min_h >= 12 ){
				$min = 12;
			}
			
			$max_h = (int)date('H',$limit_max);
			
			if($max_h >= 12 ){
				$max = 24;
			}
			
			$limits['mini'] = date('Y-m-d '.$min.':00:00',$limit_min);
			$limits['maxi'] = date('Y-m-d '.$max.':00:00',$limit_max);

			$this->setConfig('limits',$limits);
			
			
		}

		foreach($datas as $key => $data){
			
			$output = $this->getPourcentages(
											$limits['mini'],
											$limits['maxi'],
											$data->{$convocation}->toUnixString(),
											$data->{$ouverture}->toUnixString(),
											$data->{$fermeture}->toUnixString(),
											$data->{$retour}->toUnixString()
										);
			$return[$key]['positions'] = $output;
			$return[$key]['data'] = $data;
			$return[$key]['secteur'] = ['couleur' => $couleur,
										'dispositif' => $dispositif_id];
		}
		return $return;
		
    }

	public function setPdf($pdf)	
	{
		$this->setConfig('pdf',$pdf);
    }
	
	public function getPas($value = 3600)	
	{
		$size = (int) $this->getConfig('size');
		$limits = $this->getConfig('limits');
		
		$zero = strtotime($limits['mini']);
		$total = strtotime($limits['maxi']);
		
		//return (( $size * $value ) / ($total - $zero));
		return ( $size / (($total - $zero) / $value ) );
		
    }

	public function getStart()	
	{
		$limits = $this->getConfig('limits');
		
		$zero = strtotime($limits['mini']);

		return (int)date('H',$zero);
		
    }
	
	protected function getPourcentage($total,$value)	
	{
		$size = (int) $this->getConfig('size');
		$marge = (int) $this->getConfig('marge');
		
		return (( $size * $value ) / $total);
    }
	
	public function displayLimits()	
	{
		return $this->getConfig('limits');
    }
	
	protected function getPourcentages($zero,$total,$convocation,$ouverture,$fermeture,$retour)
	{
		$marge = (int) $this->getConfig('marge');
		
		$convocation = (int) $convocation;
		$ouverture = (int) $ouverture;
		$fermeture = (int) $fermeture;
		$retour = (int) $retour;

		$zero = strtotime($zero);
		$total = strtotime($total);

		$progress_total = $total - $zero;
		
		// var_dump($progress_total/3600);
		// var_dump(date('d-m-Y H:i:s', $total));
		// var_dump(date('d-m-Y H:i:s', $zero));
		// var_dump(date('d-m-Y H:i:s', $convocation));
		
		$progress_aller = $convocation - $zero;
		$progress_place = $ouverture - $convocation;
		$progress_poste = $fermeture - $ouverture;
		$progress_retour = $retour - $fermeture;

		$progress_aller = $this->getPourcentage($progress_total,$progress_aller);
		$progress_place = $this->getPourcentage($progress_total,$progress_place);
		$progress_poste = $this->getPourcentage($progress_total,$progress_poste);
		$progress_retour = $this->getPourcentage($progress_total,$progress_retour);	
		
		$progress['aller'] = $progress_place;
		$progress['poste'] = $progress_poste;
		$progress['retour'] = $progress_retour;
		
		$absice['aller'] = $progress_aller + $marge;
		$absice['poste'] = $progress_aller + $progress_place + $marge;
		$absice['retour'] = $progress_aller + $progress_place + $progress_poste + $marge;
		
		$length['aller'] = $progress_place;
		$length['poste'] = $progress_poste;
		$length['retour'] = $progress_retour;
		
		//var_dump($absice);
		//var_dump($length);
		
		return compact('absice','length');
    }

    public function build() 
	{
		$regle = (int) $this->getConfig('regle');
	
		$datas = $this->getDatas();
		$datas = $this->formatDatas($datas);
		
		$this->headerDay();
		$this->headerTime();
		
		$step = 1;
		
		foreach($datas as $data){
			$this->line($data);
			if($step==$regle){
				$this->headerTime();
				$step = 1;
			} else {
				$step++;
			}
		}
    }
	
    public function line($data) 
	{
		$colors = $this->getConfig('colors');
		
		$progress = $data['progress'];
		$object = $data['data'];
		
		extract($colors);
		extract($progress);
		
		echo
		'<div class="progress" style="height:30px;">
			<div class="progress-bar progress-bar-success" style="width: '.$progress_aller.'%; background-color:transparent; border:none;"></div>
			<div class="progress-bar '.$trajet.'" style="width: '.$progress_place.'%"></div>
			<div class="progress-bar '.$mission.'" style="text-align:left; width: '.$progress_poste.'%; margin:0px; padding:0px;"  aria-valuenow="'.$progress_poste.'" aria-valuemin="0" aria-valuemax="100" >
				<div class="btn-group" role="group" aria-label="Edition">'.
				$this->Html->link( $this->Html->icon('pencil'),['controller'=>'Equipes','action'=>'edit',$object->id],['class'=>'btn btn-warning btn-sm','title'=>__('Editer'),'style'=>['margin:2px; padding:3px; margin-right:0px;'],'data-toggle'=>'tooltip','escape'=>false]).
				$this->Html->link( $this->Html->icon('duplicate'), ['controller'=>'Equipes','action' => 'duplicate', $object->id], ['class' => 'btn btn-sm btn-warning','style'=>['margin:2px; padding:3px; margin-left:0px;'], 'data-toggle' => 'tooltip', 'title' => 'Duplicate', 'escape' => false, 'confirm' => __('Are you sure you want to duplicate # {0}?', $object->id)]).
				'</div>'.
				$object->indicatif.' - '.
				$this->Html->icon('user').'1/4&nbsp;'.
				$this->Html->icon('flag').'1/4					
			</div>
			<div class="progress-bar '.$trajet.'" style="width: '.$progress_retour.'%"></div>
		</div>';
    }
	
    public function headerDay2() 
	{
		$limits = $this->getConfig('limits');
		
		$zero = strtotime($limits['mini']);
		$total = strtotime($limits['maxi']);
		
		$total = $total - $zero;
		$count_jour = $total / 86400;
		$count_heure = $total / 3600;
		
		$pourcentage_jour = $this->getPourcentage($total,86400);
		$pourcentage_heure = $this->getPourcentage($total,3600);
		
		echo '<div class="progress">';
		for($i=0;$i<$count_jour;$i++){
		echo '<div class="progress-bar progress-bar-primary" style="width: '.$pourcentage_jour.'%; background-color:transparent; border-right:1px solid #B0E0E6; color:grey;"> '.date('d / m / Y',($zero + ($i * 86400))).' </div>';	
		}	
		echo '</div>';
    }

    public function headerDay() 
	{
		$largeur = $this->getConfig('largeur');
		$unite = $this->getConfig('unite');
		$limits = $this->getConfig('limits');
		
		$zero = strtotime($limits['mini']);
		$total = strtotime($limits['maxi']);

		echo '<div style="height:0.5cm; width: '.$largeur.$unite.';">';
		echo '<div style="width:50%; display:inline-block; text-align:left;">'.date('d-m-Y H:i',$zero).'</div>';
		echo '<div style="width:50%; display:inline-block; text-align:right;">'.date('d-m-Y H:i',$total).'</div>';
		echo '</div>';
		
    }
	
    public function headerTime() 
	{
		$limits = $this->getConfig('limits');
		
		$zero = strtotime($limits['mini']);
		$total = strtotime($limits['maxi']);

		$zero = date('Y-m-d H:00:00',$zero);
		$total = date('Y-m-d H:00:00',$total);

		$zero = strtotime($zero);
		$total = strtotime($total);
		
		$limite_basse = date('Y-m-d 00:00:00',$zero);
		$limite_haute = date('Y-m-d 00:00:00',$total);
		
		$limite_basse = strtotime($limite_basse);
		$limite_haute = strtotime($limite_haute);
		
		$difference_basse = ($zero - $limite_basse)/3600;
		$difference_haute = ($limite_haute - $total)/3600;
		
		$total = $total - $zero;

		$count_jour = $total / 86400;
		$count_heure = $total / 3600;
		
		$pourcentage_jour = $this->getPourcentage($total,86400);
		$pourcentage_heure = $this->getPourcentage($total,3600);
		
		return [
			'count'=>['jours' => $count_jour,'heures' => $count_heures],
			'length'=>['jours' => $pourcentage_jour,'heures' => $pourcentage_heure],
		];
		// echo '<div class="progress" style="height:40px;">';
		// $display = $difference_basse -1;
		// $align = 'left';
		// for($i=0;$i<$count_heure;$i++){
		// $display++;
		// switch($display){
			// case 24:
			// case 0:
				// $tmp = 0;
				// $display = 0;
				// break;
			// case 5:
				// $tmp = 0;
				// break;
			// case 6:
				// $tmp = 6;
				// break;
			// case 11:
				// $tmp = 1;
				// break;
			// case 12:
				// $tmp = 2;
				// break;
			// case 17:
				// $tmp = 1;
				// break;
			// case 18:
				// $tmp = 8;
				// break;
			// case 23:
				// $tmp = 0;
				// break;
			// default:
				// $tmp = '&nbsp;';
		// }
		// echo '<div class="progress-bar progress-bar-primary" style="height:20px; width: '.$pourcentage_heure.'%; background-color:transparent; font-size:0.7em; color:grey;"><div style="border-right:1px solid #B0E0E6; text-align:'.$align.';">'.$tmp .'</div></div>';	
		// $align = ($display%2==1)?'left':'right';
		// }
		// for($i=0;$i<$count_heure;$i++){
		// echo '<div class="progress-bar progress-bar-primary" style="height:20px; width: '.($pourcentage_heure/2).'%; background-color:transparent; font-size:0.5em; color:grey;"><div style="border-right:1px dashed #B0E0E6; text-align:'.$align.';">&nbsp;</div></div>';	
		// echo '<div class="progress-bar progress-bar-primary" style="height:20px; width: '.($pourcentage_heure/2).'%; background-color:transparent; font-size:0.5em; color:grey;"><div style="border-right:1px dashed #B0E0E6; text-align:'.$align.';">&nbsp;</div></div>';	
		// }	
		// echo '</div>';
    }
}
?>

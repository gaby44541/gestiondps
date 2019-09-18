<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Hash;

class PdfGanttHelper extends Helper 
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
			'maxi' => '2018-07-18 13:00:00',
		],
		'fields' => [
			'convocation'=>'horaires_convocation',
			'ouverture'=>'horaires_place',
			'fermeture'=>'horaires_fin',
			'retour'=>'horaires_retour',
			'dispositif'=>'dispositif_id',
		],
		'datas'=>[
			'model'=>'Equipes',
			'conditions'=>['order'=>['dispositif_id'=>'asc','horaires_convocation'=>'asc']]
		],
		'regle'=>5,
		'hauteur'=>'60px',
		'largeur'=>2362,
		'unite'=>'px'
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
	
	protected function getLimits($model = 'Equipes',$conditions=[]) 
	{
		// Force type
		$model = (string) $model;
		$conditions = (array) $conditions;
		
		// Get datas from the table
		$datas = TableRegistry::get($model);
		
		// Query database
		$datas =  $datas->find('all', $conditions )
						->toArray();

		return $datas;	
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
		
		// Query database
		$datas =  $datas->find('all',$conditions)
						->toArray();
		
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

		
		$dispositif_id = 0;

		foreach($datas as $key => $data){
			
			$output = $this->getPourcentages(
											$limits['mini'],
											$limits['maxi'],
											$data->{$convocation}->toUnixString(),
											$data->{$ouverture}->toUnixString(),
											$data->{$fermeture}->toUnixString(),
											$data->{$retour}->toUnixString()
										);
			
			if( $dispositif_id != $data->{$dispositif} ){
				$dispositif_id = 0;
			}
			
			if(empty($dispositif_id)){
				$dispositif_id = $data->{$dispositif};
				$couleur = dechex(rand(0x000000, 0xFFFFFF));
				$num = rand(0,30);
				$hash = md5('color' . $num);
				$couleur = hexdec(substr($hash, 0, 2)).','.hexdec(substr($hash, 2, 2)).','.hexdec(substr($hash, 4, 2));
				
				//$couleur = $this->hex2rgb($couleur);
			}
			
			$return[$key]['progress'] = $output;
			$return[$key]['data'] = $data;
			$return[$key]['secteur'] = ['couleur' => $couleur,
										'dispositif' => $dispositif_id];
		}
		return $return;
		
    }

	protected function getPourcentage($total,$value)	
	{
		$largeur = (int) $this->getConfig('largeur');
		$pourcent = round((( 100 * $value ) / $total),2);
		
		return round(($largeur * ( $pourcent / 100)),2);
    }
	
	protected function getPourcentages($zero,$total,$convocation,$ouverture,$fermeture,$retour)
	{
		$convocation = (int) $convocation;
		$ouverture = (int) $ouverture;
		$fermeture = (int) $fermeture;
		$retour = (int) $retour;

		$zero = strtotime($zero);
		$total = strtotime($total);

		$progress_total = $total - $zero;
		$progress_aller = $convocation - $zero;
		$progress_place = $ouverture - $convocation;
		$progress_poste = $fermeture - $ouverture;
		$progress_retour = $retour - $fermeture;

		$progress_aller = $this->getPourcentage($progress_total,$progress_aller);
		$progress_place = $this->getPourcentage($progress_total,$progress_place);
		$progress_poste = $this->getPourcentage($progress_total,$progress_poste);
		$progress_retour = $this->getPourcentage($progress_total,$progress_retour);	

		return compact('progress_aller','progress_place','progress_poste','progress_retour');	
    }

    public function build() 
	{
		$regle = (int) $this->getConfig('regle');
	
		$datas = $this->getDatas();
		$datas = $this->formatDatas($datas);

		$this->legende($datas);
		
		$this->headerDay();
		$return = $this->headerTime();
		
		echo $return;
		
		$step = 1;
		
		foreach($datas as $data){
			$this->line($data);

			if($step==$regle){
				echo $return;
				$step = 1;
			} else {
				$step++;
			}
		}
    }
	
    public function line($data) 
	{
		$unite = $this->getConfig('unite');
		$largeur = $this->getConfig('largeur');
		$hauteur = $this->getConfig('hauteur');
		
		$colors = $this->getConfig('colors');
		
		$progress = $data['progress'];
		$object = $data['data'];
		$secteur = $data['secteur'];
		
		extract($colors);
		extract($progress);

		// $str_split = str_split($secteur['couleur'],2);
		
		// foreach($str_split as &$split){
			// $split = hexdec($split);
		// }
		
		// $str_split = implode(',',$str_split);
		$str_split = $secteur['couleur'];

		echo
		'<div style="height:65px; width: '.$largeur.$unite.'; background-color:rgba('.$str_split.', .2);">
			<div class="progress-bar progress-bar-success" style="height:'.$hauteur.'; width: '.$progress_aller.$unite.'; background-color:transparent; border:none;"></div>
			<div class="progress-bar '.$trajet.'" style="height:'.$hauteur.'; width: '.$progress_place.$unite.';"></div>
			<div class="progress-bar '.$mission.'" style="height:'.$hauteur.'; font-size:20px; line-height:24px; text-align:left; width: '.$progress_poste.$unite.';"  aria-valuenow="'.$progress_poste.'" aria-valuemin="0" aria-valuemax="100" >'.
				$object->indicatif.' - '.
				$this->Html->icon('user').'4&nbsp;'.
				$this->Html->icon('flag').'4					
			</div>
			<div class="progress-bar '.$trajet.'" style="height:'.$hauteur.'; width: '.$progress_retour.$unite.';"></div>
		</div>';
    }

    public function legende($datas) 
	{
		$legende = Hash::combine($datas,'{n}.secteur.couleur','{n}.secteur.dispositif');
				
		$largeur = $this->getConfig('largeur');
		$unite = $this->getConfig('unite');
		$limits = $this->getConfig('limits');
		
		$count = count($legende);
		$case = (($largeur -($count * 79)) / $count);
		
		echo '<div style="height:1cm; width: '.$largeur.$unite.';">';
		foreach($legende as $key => $val ){
		echo '<div style="width:0.85cm; height:0.85cm; display:inline-block; text-align:left; background-color:rgba('.$key.', .2);"></div>';
		echo '<div style="width:'.$case.$unite.'; height:0.85cm; padding-left:20px; display:inline-block; text-align:left; background-color:white;">'.$val.'</div>';
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
		$unite = $this->getConfig('unite');
		$largeur = $this->getConfig('largeur');
		
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

		$return = '<div style="height:0.5cm; width: '.$largeur.$unite.'; display:block;">';
		$display = $difference_basse -1;
		$align = 'left';
		$second = [];
		for($i=0;$i<$count_heure;$i++){
		$display++;
		switch($display){
			case 24:
			case 0:
				$tmp = 0;
				$display = 0;
				break;
			case 5:
				$tmp = 0;
				break;
			case 6:
				$tmp = 6;
				break;
			case 11:
				$tmp = 1;
				break;
			case 12:
				$tmp = 2;
				break;
			case 17:
				$tmp = 1;
				break;
			case 18:
				$tmp = 8;
				break;
			case 23:
				$tmp = 0;
				break;
			default:
				$tmp = '&nbsp;';
		}
		$return .= '<div class="progress-bar progress-bar-primary" style="height:0.4cm; width: '.$pourcentage_heure.$unite.'; background-color:transparent; font-size:1.2em; margin:0px; padding:0px; border-right:0px solid #B0E0E6; color:grey;"><div style="border-right:1px solid #B0E0E6; text-align:'.$align.';">'.$tmp .'</div></div>';	
		$align = ($display%2==1)?'left':'right';
		}
		$return .= '</div>';
		return $return;
    }
	
	protected function hex2rgb( $colour ) {
			if ( $colour[0] == '#' ) {
					$colour = substr( $colour, 1 );
			}
			if ( strlen( $colour ) == 6 ) {
					list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
			} elseif ( strlen( $colour ) == 3 ) {
					list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
			} else {
					return false;
			}
			$r = hexdec( $r );
			$g = hexdec( $g );
			$b = hexdec( $b );
			return array( 'red' => $r, 'green' => $g, 'blue' => $b );
	}

}
?>

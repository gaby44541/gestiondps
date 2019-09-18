<?php

$association= isset($association) ? $association : 0;
$action_id	= isset($action_id) ? $action_id : 0;
$action		= isset($action) ? $action : 'add';
$controller	= isset($controller) ? $controller : '';
$space		= isset($space) ? $space : '';
$pull		= isset($pull) ? $pull : '';
$merge		= isset($merge) ? (array) $merge : [];
$options	= isset($options) ? (array) $options : ['add','edit','index','delete'];
$text		= isset($text) ? $text : true;
$icon		= isset($icon) ? $icon : true;
$style		= isset($style) ? ' style="'.$style.'"' : '';
$class		= isset($class) ? $class : '';

$trame = [];

$trame['add']=[		'link'=>true,
					'label'=>['icon'=>'plus','text'=>__('Ajouter')],
					'url'=>['controller'=>$controller,'action'=>'add'],
					'attr'=>['class'=>$class,'title'=>__('Nouveau'),'data-toggle'=>'tooltip','escape'=>false]
			];
$trame['edit']=[	'link'=>true,
					'label'=>['icon'=>'pencil','text'=>__('Editer')],
					'url'=>['controller'=>$controller,'action'=>'edit',$action_id],
					'attr'=>['class'=>$class,'title'=>__('Editer'),'data-toggle'=>'tooltip','escape'=>false]
				];
$trame['index']=[	'link'=>true,
					'label'=>['icon'=>'list','text'=>__('Liste')],
					'url'=>['controller'=>$controller,'action'=>'index'],
					'attr'=>['class'=>$class,'title'=>__('Lister'),'data-toggle'=>'tooltip','escape'=>false]
				];
$trame['delete']=[ 	'link'=>false,
					'label'=>['icon'=>'trash','text'=>__('Supprimer')],
					'url'=>['controller'=>$controller,'action'=>'delete',$action_id],
					'attr'=>['class'=>'btn btn-danger btn-sm','title'=>__('Supprimer'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Are you sure you want to delete # {0}?', $action_id)]
				];
$trame['addid']=[ 	'link'=>true,
					'label'=>['icon'=>'plus','text'=>__('Associer')],
					'url'=>['controller'=>$controller,'action'=>'add',$association],
					'attr'=>['class'=>$class,'title'=>__('Associer'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Are you sure you want to create new association # {0}?', $association)]
				];
$trame['view']=[ 	'link'=>true,
					'label'=>['icon'=>'eye-open','text'=>__('Voir')],
					'url'=>['controller'=>$controller,'action'=>'view',$action_id],
					'attr'=>['class'=>$class,'title'=>__('Voir'),'data-toggle'=>'tooltip','escape'=>false]
				];
$trame['wizard']=[ 	'link'=>true,
					'label'=>['icon'=>'flash','text'=>__('Sélectionner cette entité pour créer un dossier')],
					'url'=>['controller'=>$controller,'action'=>'wizard','next',$action_id],
					'attr'=>['class'=>'btn btn-inverse btn-primary btn-sm','title'=>__('Mode wizard : Sélectionner cette entité pour créer un dossier'),'data-toggle'=>'tooltip','escape'=>false]
				];

/* if( ! is_array( $merge ) ){
	$merge = (array) $merge;
} 
if( ! isset( $options ) ) {
	$options = ['add','edit','index','delete'];
}
if( ! is_array( $options ) ){
	$options = (array) $options;
}
if( ! isset( $text ) ){
	$text = true;
}
if( ! isset( $icon ) ){
	$icon = true;
}*/
$tmp = array();
foreach( $options as $option ){
	if( isset( $trame[ $option ] ) ){
		$tmp[ $option ] = $trame[ $option ];
		if( isset( $merge[ $option ] ) ){
			$tmp[ $option ] = array_merge( $tmp[ $option ] , $merge[ $option ] );
		}
		if( ! $text ){
			unset( $tmp[ $option ]['label']['text'] );
		} else {
			$tmp[ $option ]['label']['text'] = __($tmp[ $option ]['label']['text']);
		}
		if( ! $icon ){
			unset( $tmp[ $option ]['label']['icon'] );
		} else {
			$tmp[ $option ]['label']['icon'] = $this->Html->icon($tmp[ $option ]['label']['icon']);
			
		}
		$tmp[ $option ]['label'] = __( implode(' ',$tmp[ $option ]['label']) );
	}
}
?>

<!-- Single button -->
<div class="btn-group <?= $pull ?>"<?= $style ?>>
	<button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<?= $label ?>&nbsp;<span class="caret"></span>
	</button>
	<ul class="dropdown-menu">
		<?php foreach($actions as $action_id => $action_label){ ?>
		<li><?= $this->Html->link( $action_label, ['controller'=>$controller,'action'=>$action,$action_id],['escape'=>false]); ?></li>
		<?php } ?>
	</ul>
</div>
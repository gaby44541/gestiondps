<?php

$association= isset( $association ) ? $association : 0;
$action_id 	= isset( $action_id ) ? $action_id : 0;
$controller = isset( $controller ) ? $controller : '';
$action 	= isset( $action ) ? $action : 'index';
$space 		= isset( $space ) ? $space : '';
$link 		= isset($link) ? $link : true;
$pull		= isset($pull) ? $pull : '';
$merge		= isset($merge) ? (array) $merge : [];
$options	= isset($options) ? (array) $options : ['add','edit','index','delete'];
$text		= isset($text) ? $text : true;
$icon		= isset($icon) ? $icon : true;
$style		= isset($style) ? ' style="'.$style.'"' : '';
$class		= isset($class) ? $class : 'btn btn-success btn-sm';

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
$trame['delete']=[	'link'=>false,
					'label'=>['icon'=>'trash','text'=>__('Supprimer')],
					'url'=>['controller'=>$controller,'action'=>'delete',$action_id],
					'attr'=>['class'=>'btn btn-danger btn-sm','title'=>__('Supprimer'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Etes-vous sûr de vouloir supprimer # {0}?', $action_id)]
					];
$trame['addid']=[	'link'=>true,
					'label'=>['icon'=>'plus','text'=>__('Associer')],
					'url'=>['controller'=>$controller,'action'=>'add',$association],
					'attr'=>['class'=>$class,'title'=>__('Associer'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Etes-vous sûr de vouloir créer une nouvelle association # {0}?', $association)]];
$trame['view']=[	'link'=>true,
					'label'=>['icon'=>'eye-open','text'=>__('Voir')],
					'url'=>['controller'=>$controller,'action'=>'view',$action_id],
					'attr'=>['class'=>$class,'title'=>__('Voir'),'data-toggle'=>'tooltip','escape'=>false]];
$trame['wizard']=[	'link'=>true,
					'label'=>['icon'=>'flash','text'=>__('Sélectionner cette entité pour créer un dossier')],
					'url'=>['controller'=>$controller,'action'=>'wizard','next',$action_id],
					'attr'=>['class'=>'btn btn-inverse btn-primary btn-sm','title'=>__('Mode wizard : Sélectionner cette entité pour créer un dossier'),'data-toggle'=>'tooltip','escape'=>false]];
$trame['create']=[	'link'=>true,
					'label'=>['icon'=>'plus','text'=>__('Faire une nouvelle demande')],
					'url'=>['controller'=>$controller,'action'=>'add'],
					'attr'=>['class'=>'btn btn-warning btn-sm','title'=>__('Faire une nouvelle demande'),'data-toggle'=>'tooltip','escape'=>false]];
$trame['association']=['link'=>true,
					'label'=>['icon'=>'plus','text'=>__('Associer')],
					'url'=>['controller'=>$controller,'action'=>'add',$association],
					'attr'=>['class'=>$class,'title'=>__('Associer'),'data-toggle'=>'tooltip','escape'=>false]];
$trame['cancel']=[	'link'=>true,
					'label'=>['icon'=>'remove','text'=>__('Retourner au dossier')],
					'url'=>['controller'=>$controller,'action'=>'view',$action_id],
					'attr'=>['class'=>$class,'title'=>__('Annuler la saisie et retourner au dossier'),'data-toggle'=>'tooltip','escape'=>false]];
$trame['createwizard']=[
					'link'=>true,
					'label'=>['icon'=>'flash','text'=>__('Créer un nouveau dossier')],
					'url'=>['controller'=>$controller,'action'=>'wizard'],
					'attr'=>['class'=>'btn btn-inverse btn-primary btn-sm','title'=>__('Créer une nouvelle demande'),'data-toggle'=>'tooltip','escape'=>false]];
$trame['divers']=[
				'link'=>$link,
				'label'=>['icon'=>'flash','text'=>__('')],
				'url'=>[],
				'attr'=>[]
				];

$tmp = array();

if(!empty($options)){
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
}
?>
<div class="btn-group text-left <?= $pull ?>"<?= $style ?>>
<?php
	foreach( $tmp as $key => $option ){
		if( $option['link'] ){
			echo $this->Html->link( $option['label'], $option['url'], $option['attr']);
		} else {
			echo $this->Form->postLink( $option['label'], $option['url'], $option['attr']);
		}
		echo $space;
	}
?>
</div>

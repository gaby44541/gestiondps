<?php
if( ! isset( $options ) ) {
	$options = ['view','edit','delete'];
}
if( ! isset( $group ) ) {
	$group = 'group';
} else {
	$group = 'toolbar';
}
if( ! isset( $pull ) ) {
	$pull = 'right';
}
if( $pull != 'right'){
	$pull = 'left';
} else {
	$pull = 'right';
}
if( ! is_array( $options ) ){
	$options = (array) $options;
}
if( !isset($td)){
	$td = true;
} else {
	$td = (boolean) $td;
}
$default = ['type'=>'custom',
			'label'=>__('<i class="glyphicon glyphicon-flash"></i> Sélectionner'),
			'url'=>['controller'=>$controller,'action' => 'wizard','next',$action_id],
			'params'=>['class' => 'btn btn-sm btn-primary', 'data-toggle' => 'tooltip', 'title' => 'Sélectionner cet item et passer à la suite du dossier', 'escape' => false]
		];


?>
<?php if($td): ?>
<td class="actions" style="width:15%;">
<?php endif; ?>
<div class="btn-<?= $group ?> pull-<?= $pull ?>" role="toolbar">
<?php
	foreach( $options as $option ){
		if( is_array( $option ) ) {
			$merge = array_merge($default,$option);
			$option = $option['type'];
		} else {
			$type = $option;
			if( $type = 'wizard'){
				$merge = $default;
			}
		}
		switch( $option ){
			case 'addid':
				$association = isset( $association ) ? $association : 0;
				echo $this->Html->link(__('<i class="fa fa-plus"></i> Ajouter'), ['controller'=>$controller,'action' => 'add',$association], ['class' => 'btn btn-success btn-sm', 'title' => 'New', 'data-toggle' => 'tooltip', 'escape' => false]);
				break;
			case 'add':
				echo $this->Html->link(__('&nbsp;<i class="fa fa-plus"></i>&nbsp;'), ['controller'=>$controller,'action' => 'add'], ['class' => 'btn btn-success btn-sm', 'title' => 'New', 'data-toggle' => 'tooltip', 'escape' => false]);
				break;
			case 'edit':
				echo $this->Html->link(__('&nbsp;<i class="fa fa-pencil"></i>&nbsp;'), ['controller'=>$controller,'action' => 'edit', $action_id], ['class' => 'btn btn-sm btn-success', 'data-toggle' => 'tooltip', 'title' => 'Edit',  'escape' => false]);
				break;
			case 'index':
				echo $this->Html->link(__('&nbsp;<i class="fa fa-list-alt"></i>&nbsp;'), ['controller'=>$controller,'action' => 'index'], ['class' => 'btn btn-success btn-sm', 'title' => 'List', 'data-toggle' => 'tooltip', 'escape' => false]);
				break;
			case 'duplicate':
				echo $this->Html->link(__('&nbsp;<i class="fa fa-clone"></i>&nbsp;'), ['controller'=>$controller,'action' => 'duplicate', $action_id], ['class' => 'btn btn-sm btn-danger', 'data-toggle' => 'tooltip', 'title' => 'Duplicate', 'escape' => false, 'confirm' => __('Etes-vous sûr de vouloir supprimer # {0}?', $action_id)]);
				break;
			case 'delete':
				echo $this->Form->postLink(__('&nbsp;<i class="fa fa-trash-o"></i>&nbsp;'), ['controller'=>$controller,'action' => 'delete', $action_id], ['class' => 'btn btn-sm btn-danger', 'data-toggle' => 'tooltip', 'title' => 'Delete', 'escape' => false, 'confirm' => __('Etes-vous sûr de vouloir supprimer # {0}?', $action_id)]);
				break;
			case 'view':
				echo $this->Html->link(__('&nbsp;<i class="fa fa-eye"></i>&nbsp;'), ['controller'=>$controller,'action' => 'view', $action_id], ['class' => 'btn btn-sm btn-success', 'data-toggle' => 'tooltip', 'title' => 'View', 'escape' => false]);
				break;
			case 'wizard':
				echo $this->Html->link($merge['label'],$merge['url'],$merge['params']);
				break;
			case 'groupStart':
				echo '<div class="btn-group" role="group">';
				break;
			case 'groupEnd':
				echo '</div>';
				break;
		}
	}
?>
</div>
<?php if($td): ?>
</td>
<?php endif; ?>

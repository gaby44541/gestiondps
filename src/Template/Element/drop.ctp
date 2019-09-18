<?php
$icon = isset($icon) ? $icon : 'print';
$label = isset($label) ? $label : 'Bouton';
$align = isset($align) ? $align : '';
$size = isset($size) ? ' '.$size : '';
$color = isset($color) ? ' '.$color : '';
?>
<!-- Single button -->
<div class="btn-group<?= $size ?>">
	<button type="button" class="btn btn-sm dropdown-toggle<?= $color ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<?= $this->Html->icon($icon)?>&nbsp;<?= $label ?>&nbsp;<span class="caret"></span>
	</button>
	<ul class="dropdown-menu <?= $align ?>">
		<?php 
		foreach($actions as $action){
			$action['icon'] = isset($action['icon']) ? $this->Html->icon($action['icon']).' ' : '';
			$action['label'] = isset($action['label']) ? $action['label'] : '';
			$action['url'] = isset($action['url']) ? $action['url'] : [];
			$action['attrs'] = isset($action['attrs']) ? $action['attrs'] : ['escape'=>false];
			$action['attr'] = isset($action['attr']) ? $action['attr'] : ['escape'=>false];
			$action['li'] = isset($action['li']) ? $action['li'] : 'normal';

			switch($action['li']){
				case 'header':
					$action['attrs']['role'] = 'presentation';
					$action['attrs']['class'] = 'dropdown-header';
					$content = $action['icon'].$action['label'];
					echo $this->Html->tag('li',$content,$action['attrs']);
					break;
				case 'divider':
					$action['attrs']['role'] = 'separator';
					$action['attrs']['class'] = 'divider';
					echo '<li role="separator" class="divider"></li>';
					break;
				case 'postlink':
					$content = $this->Form->postLink($action['icon'].$action['label'], $action['url'], $action['attr']);
					echo $this->Html->tag('li',$content,$action['attrs']);	
					break;
				default:
					$content = $this->Html->link( $action['icon'].$action['label'], $action['url'],$action['attr']);
					echo $this->Html->tag('li',$content,$action['attrs']);
			}
		} 
		?>
	</ul>
</div>
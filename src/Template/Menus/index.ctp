<div class="container-fluid">
	<h1>
		<?= __('Menus') ?>
		<?= $this->element('buttons',['controller'=>'menus','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="menus">
	    <thead>
	        <tr>
				<th><?= __('Level') ?></th>
				<th><?= __('Intitule') ?></th>
				<th><?= __('Link') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($menus as $menu): ?>
	        <tr>
        	            <td>
							<a href="<?= $this->Url->build(['controller'=>'menus','action'=>'movedown', $menu->id]); ?>" 
							   data-original-title="Descendre" data-toggle="tooltip" 
							   type="button" class="btn btn-xs btn-warning">
									<i class="glyphicon glyphicon-arrow-down"></i>
							</a>
							<a href="<?= $this->Url->build(['controller'=>'menus','action'=>'moveup', $menu->id]); ?>" 
							   data-original-title="Monter" data-toggle="tooltip" 
							   type="button" class="btn btn-xs btn-warning">
									<i class="glyphicon glyphicon-arrow-up"></i>
							</a>
							<?php
							$int = (int) $this->Number->format($menu->level);
							for( $i = 0; $i < $int; $i++ ){
								echo $this->Html->icon( 'minus' );
							}
							echo $this->Html->icon( 'play' );
							?>
						<td>
							<?= $this->Html->icon( $menu->icone ) ?>
							<?= h($menu->intitule) ?>
						</td>
						<td>
							<?= $this->Html->link( ['controller'=>$menu->controller,'action'=>$menu->actions] ) ?>
						</td>
					<?= $this->element('actions',['controller'=>'menus','action_id'=>$menu->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    //$('#menus').DataTable();
</script>

<div class="container-fluid">
	<h1>
		<?= __('Organisateurs') ?>
		<?= $this->element('buttons',['controller'=>'organisateurs','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="organisateurs">
	    <thead>
	        <tr>
        	    <th><?= __('Nom') ?></th>
				<th><?= __('Fonction') ?></th>
        	    <th><?= __('Adresse') ?></th>
        	    <th><?= __('Ville') ?></th>
        	    <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($organisateurs as $organisateur): ?>
	        <tr>
				<?php // $this->element('actions',['controller'=>'organisateurs','group'=>'toolbar','pull'=>'left','options'=>['wizard'],'action_id'=>$organisateur->id]); ?>
        	            <td><?= h($organisateur->nom) ?></td>
        	            <td><?= h($organisateur->fonction) ?></td>
        	            <td><?= h($organisateur->adresse) ?></td>
        	            <td><?= h($organisateur->ville) ?></td>
				<?= $this->element('actions',['controller'=>'organisateurs','action_id'=>$organisateur->id,'options'=>['wizard','edit']]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#organisateurs').DataTable();
</script>

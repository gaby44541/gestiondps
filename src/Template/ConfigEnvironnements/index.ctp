<div class="configEnvironnements index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Config Environnements') ?>
		<?= $this->element('buttons',['controller'=>'configEnvironnements','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="configEnvironnements">
	    <thead>
	        <tr>
        	            <th><?= __('Indice') ?></th>
        	            <th><?= __('Environnement') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configEnvironnements as $configEnvironnement): ?>
	        <tr>
				<td><?= $this->Number->format($configEnvironnement->indice) ?></td>
				<td><?= h($configEnvironnement->environnement) ?></td>
				<?= $this->element('actions',['controller'=>'configEnvironnements','action_id'=>$configEnvironnement->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#configEnvironnements').DataTable();
</script>

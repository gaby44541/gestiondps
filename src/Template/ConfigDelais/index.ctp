<div class="configDelais index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Config Delais') ?>
		<?= $this->element('buttons',['controller'=>'configDelais','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="configDelais">
	    <thead>
	        <tr>
        	            <th><?= __('Indice') ?></th>
        	            <th><?= __('Designation') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configDelais as $configDelai): ?>
	        <tr>
        	            <td><?= $this->Number->format($configDelai->indice) ?></td>
        	            <td><?= h($configDelai->designation) ?></td>
					<?= $this->element('actions',['controller'=>'configDelais','action_id'=>$configDelai->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#configDelais').DataTable();
</script>

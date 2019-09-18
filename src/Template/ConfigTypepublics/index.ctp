<div class="configTypepublics index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Config Typepublics') ?>
		<?= $this->element('buttons',['controller'=>'configTypepublics','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="configTypepublics">
	    <thead>
	        <tr>
        	            <th><?= __('Indice') ?></th>
        	            <th><?= __('Designation') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configTypepublics as $configTypepublic): ?>
	        <tr>
        	            <td><?= $this->Number->format($configTypepublic->indice) ?></td>
        	            <td><?= h($configTypepublic->designation) ?></td>
					<?= $this->element('actions',['controller'=>'configTypepublics','action_id'=>$configTypepublic->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#configTypepublics').DataTable();
</script>

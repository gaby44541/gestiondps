<div class="container-fluid">
	<h1>
		<?= __('Config Postes') ?>
		<?= $this->element('buttons',['controller'=>'configPostes','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="configPostes">
	    <thead>
	        <tr>
        	            <th><?= __('Type') ?></th>
        	            <th><?= __('Mini') ?></th>
        	            <th><?= __('Maxi') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configPostes as $configPoste): ?>
	        <tr>
        	            <td><?= h($configPoste->type) ?></td>
        	            <td><?= $this->Number->format($configPoste->mini) ?></td>
        	            <td><?= $this->Number->format($configPoste->maxi) ?></td>
					<?= $this->element('actions',['controller'=>'configPostes','action_id'=>$configPoste->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#configPostes').DataTable();
</script>

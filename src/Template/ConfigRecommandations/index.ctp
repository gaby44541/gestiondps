<div class="container-fluid">
	<h1>
		<?= __('Config Recommandations') ?>
		<?= $this->element('buttons',['controller'=>'configRecommandations','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="configRecommandations">
	    <thead>
	        <tr>
        	            <th><?= __('Indice') ?></th>
        	            <th><?= __('Recommandations') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configRecommandations as $configRecommandation): ?>
	        <tr>
        	            <td><?= $this->Number->format($configRecommandation->indice) ?></td>
        	            <td><?= h($configRecommandation->recommandations) ?></td>
					<?= $this->element('actions',['controller'=>'configRecommandations','action_id'=>$configRecommandation->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#configRecommandations').DataTable();
</script>

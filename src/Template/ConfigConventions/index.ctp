<div class="configConventions index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Config Conventions') ?>
		<?= $this->element('buttons',['controller'=>'configConventions','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="configConventions">
	    <thead>
	        <tr>
        	            <th><?= __('Designation') ?></th>
        	            <th><?= __('Description') ?></th>
        	            <th><?= __('Ordre') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configConventions as $configConvention): ?>
	        <tr>
        	            <td><?= h($configConvention->designation) ?></td>
        	            <td><?= nl2br($configConvention->description) ?></td>
        	            <td><?= $this->Number->format($configConvention->ordre) ?></td>
        	            <td class="actions">
					<?= $this->element('actions',['controller'=>'configConventions','action_id'=>$configConvention->id]) ?>
	            </td>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#configConventions').DataTable();
</script>

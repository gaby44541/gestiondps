<div class="container-fluid">
	<h1>
		<?= __('Config Logs') ?>
		<?= $this->element('buttons',['controller'=>'configLogs','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="configlogs">
	    <thead>
	        <tr>
				<th><?= __('User') ?></th>
				<th><?= __('Ip') ?></th>
				<th><?= __('Controller') ?></th>
				<th><?= __('Action') ?></th>
				<th><?= __('Params') ?></th>
				<th><?= __('Date') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configLogs as $configLog): ?>
	        <tr>
				<td><?= h($configLog->user) ?></td>
				<td><?= h($configLog->ip) ?></td>
				<td><?= h($configLog->controller) ?></td>
				<td><?= h($configLog->action) ?></td>
				<td><?= h($configLog->params) ?></td>
				<td><?= h($configLog->date) ?></td>
				<?= $this->element('actions',['controller'=>'configLogs','action_id'=>$configLog->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#configlogs').DataTable();
</script>

<div class="container-fluid">
	<h1>
		<?= __('ConfigLog') ?>
		<?= $this->element('buttons',['controller'=>'configLogs','action_id'=>$configLog->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($configLog->id) ?></td>
		</tr>
		<tr>
			<th><?= __('User') ?></th>
			<td><?= h($configLog->user) ?></td>
		</tr>
		<tr>
			<th><?= __('Ip') ?></th>
			<td><?= h($configLog->ip) ?></td>
		</tr>
		<tr>
			<th><?= __('Controller') ?></th>
			<td><?= h($configLog->controller) ?></td>
		</tr>
		<tr>
			<th><?= __('Action') ?></th>
			<td><?= h($configLog->action) ?></td>
		</tr>
		<tr>
			<th><?= __('Params') ?></th>
			<td><?= h($configLog->params) ?></td>
		</tr>
		<tr>
			<th><?= __('Request') ?></th>
			<td><?= $this->Text->autoParagraph(h($configLog->request)); ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
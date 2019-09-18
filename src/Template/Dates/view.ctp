<div class="dates view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('Date') ?>
		<?= $this->element('buttons',['controller'=>'dates','action_id'=>$date->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($date->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Module') ?></th>
			<td><?= h($date->module) ?></td>
		</tr>
		<tr>
			<th><?= __('Start') ?></th>
			<td><?= h($date->start) ?></td>
		</tr>
		<tr>
			<th><?= __('End') ?></th>
			<td><?= h($date->end) ?></td>
		</tr>
		<tr>
			<th><?= __('Title') ?></th>
			<td><?= h($date->title) ?></td>
		</tr>
		<tr>
			<th><?= __('Informations') ?></th>
			<td><?= $this->Text->autoParagraph(h($date->informations)); ?></td>
		</tr>
		<tr>
			<th><?= __('Url') ?></th>
			<td><?= $this->Text->autoParagraph(h($date->url)); ?></td>
		</tr>
		<tr>
			<th><?= __('Created') ?></th>
			<td><?= h($date->created) ?></td>
		</tr>
		<tr>
			<th><?= __('Modified') ?></th>
			<td><?= h($date->modified) ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
<div class="wizards view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('Wizard') ?>
		<?= $this->element('buttons',['controller'=>'wizards','action_id'=>$wizard->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($wizard->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Uuid') ?></th>
			<td><?= h($wizard->uuid) ?></td>
		</tr>
		<tr>
			<th><?= __('Step') ?></th>
			<td><?= $this->Number->format($wizard->step) ?></td>
		</tr>
		<tr>
			<th><?= __('Datas') ?></th>
			<td><?= $this->Text->autoParagraph(h($wizard->datas)); ?></td>
		</tr>
		<tr>
			<th><?= __('Created') ?></th>
			<td><?= h($wizard->created) ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
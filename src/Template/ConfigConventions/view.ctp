<div class="configConventions view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('ConfigConvention') ?>
		<?= $this->element('buttons',['controller'=>'configConventions','action_id'=>$configConvention->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($configConvention->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Designation') ?></th>
			<td><?= h($configConvention->designation) ?></td>
		</tr>
		<tr>
			<th><?= __('Description') ?></th>
			<td><?= $this->Text->autoParagraph(h($configConvention->description)); ?></td>
		</tr>
		<tr>
			<th><?= __('Ordre') ?></th>
			<td><?= $this->Number->format($configConvention->ordre) ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
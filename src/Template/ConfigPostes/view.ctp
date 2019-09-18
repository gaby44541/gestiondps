<div class="container-fluid">
	<h1>
		<?= __('ConfigPoste') ?>
		<?= $this->element('buttons',['controller'=>'configPostes','action_id'=>$configPoste->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($configPoste->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Type') ?></th>
			<td><?= h($configPoste->type) ?></td>
		</tr>
		<tr>
			<th><?= __('Mini') ?></th>
			<td><?= $this->Number->format($configPoste->mini) ?></td>
		</tr>
		<tr>
			<th><?= __('Maxi') ?></th>
			<td><?= $this->Number->format($configPoste->maxi) ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
<div class="configParametres view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('ConfigParametre') ?>
		<?= $this->element('buttons',['controller'=>'configParametres','action_id'=>$configParametre->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($configParametre->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Pourcentage') ?></th>
			<td><?= $this->Number->format($configParametre->pourcentage) ?></td>
		</tr>
		<tr>
			<th><?= __('Cout Personnel') ?></th>
			<td><?= $this->Number->format($configParametre->cout_personnel) ?></td>
		</tr>
		<tr>
			<th><?= __('Cout Kilometres') ?></th>
			<td><?= $this->Number->format($configParametre->cout_kilometres) ?></td>
		</tr>
		<tr>
			<th><?= __('Cout Repas') ?></th>
			<td><?= $this->Number->format($configParametre->cout_repas) ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
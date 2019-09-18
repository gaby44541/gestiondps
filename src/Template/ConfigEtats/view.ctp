<div class="configEtats view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('ConfigEtat') ?>
		<?= $this->element('buttons',['controller'=>'configEtats','action_id'=>$configEtat->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($configEtat->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Designation') ?></th>
			<td><?= $this->Html->badge($configEtat->designation,['class'=>$configEtat->class]) ?></td>
		</tr>
		<tr>
			<th><?= __('Description') ?></th>
			<td><?= h($configEtat->description) ?></td>
		</tr>
		<tr>
			<th><?= __('Ordre') ?></th>
			<td><?= $this->Number->format($configEtat->ordre) ?></td>
		</tr>
	
	</table>
<div class="related">
	<h3>
		<?= __('Demandes') ?>
		<?= $this->element('buttons',['controller'=>'Demandes','options'=>'add']) ?>
	</h3>
<?php if (!empty($configEtat->demandes)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Demandes">
		<thead>
			<tr>
				<th><?= __('Manifestation') ?></th>
				<th><?= __('Organisateur Id') ?></th>
				<th><?= __('Representant') ?></th>
				<th><?= __('Representant Fonction') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($configEtat->demandes as $demandes): ?>
			<tr>
				<td><?= h($demandes->manifestation) ?></td>
				<td><?= h($demandes->organisateur_id) ?></td>
				<td><?= h($demandes->representant) ?></td>
				<td><?= h($demandes->representant_fonction) ?></td>
				<td class="actions">
				<?= $this->element('actions',['controller'=>'Demandes','action_id'=>$demandes->id]) ?>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
</div>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	    $('#Demandes').DataTable();
	</script>
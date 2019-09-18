<div class="configEnvironnements view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('ConfigEnvironnement') ?>
		<?= $this->element('buttons',['controller'=>'configEnvironnements','action_id'=>$configEnvironnement->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($configEnvironnement->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Indice') ?></th>
			<td><?= $this->Number->format($configEnvironnement->indice) ?></td>
		</tr>
		<tr>
			<th><?= __('Environnement') ?></th>
			<td><?= $this->Text->autoParagraph(h($configEnvironnement->environnement)); ?></td>
		</tr>
	
	</table>
<div class="related">
	<h3>
		<?= __('Dispositifs') ?>
		<?= $this->element('buttons',['controller'=>'Dispositifs','options'=>'add']) ?>
	</h3>
<?php if (!empty($configEnvironnement->dispositifs)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Dispositifs">
		<thead>
			<tr>
				<th><?= __('Dimensionnement Id') ?></th>
				<th><?= __('Title') ?></th>
				<th><?= __('Lieu Manifestation') ?></th>
				<th><?= __('Gestionnaire Identite') ?></th>
				<th><?= __('Gestionnaire Mail') ?></th>
				<th><?= __('Gestionnaire Telephone') ?></th>
				<th><?= __('Config Typepublic Id') ?></th>
				<th><?= __('Config Delai Id') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($configEnvironnement->dispositifs as $dispositifs): ?>
			<tr>
				<td><?= h($dispositifs->dimensionnement_id) ?></td>
				<td><?= h($dispositifs->title) ?></td>
				<td><?= h($dispositifs->lieu_manifestation) ?></td>
				<td><?= h($dispositifs->gestionnaire_identite) ?></td>
				<td><?= h($dispositifs->gestionnaire_mail) ?></td>
				<td><?= h($dispositifs->gestionnaire_telephone) ?></td>
				<td><?= h($dispositifs->config_typepublic_id) ?></td>
				<td><?= h($dispositifs->config_delai_id) ?></td>
				<td class="actions">
				<?= $this->element('actions',['controller'=>'Dispositifs','action_id'=>$dispositifs->id]) ?>
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
	    $('#Dispositifs').DataTable();
	</script>
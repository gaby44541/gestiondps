<div class="container-fluid">
	<h1>
		<?= __('ConfigRecommandation') ?>
		<?= $this->element('buttons',['controller'=>'configRecommandations','action_id'=>$configRecommandation->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($configRecommandation->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Indice') ?></th>
			<td><?= $this->Number->format($configRecommandation->indice) ?></td>
		</tr>
		<tr>
			<th><?= __('Recommandations') ?></th>
			<td><?= $this->Text->autoParagraph(h($configRecommandation->recommandations)); ?></td>
		</tr>
	
	</table>
<div class="related">
	<h3>
		<?= __('Dispositifs') ?>
		<?= $this->element('buttons',['controller'=>'Dispositifs','options'=>'add']) ?>
	</h3>
<?php if (!empty($configRecommandation->dispositifs)): ?>
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
				<th><?= __('Config Environnement Id') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($configRecommandation->dispositifs as $dispositifs): ?>
			<tr>
				<td><?= h($dispositifs->dimensionnement_id) ?></td>
				<td><?= h($dispositifs->title) ?></td>
				<td><?= h($dispositifs->lieu_manifestation) ?></td>
				<td><?= h($dispositifs->gestionnaire_identite) ?></td>
				<td><?= h($dispositifs->gestionnaire_mail) ?></td>
				<td><?= h($dispositifs->gestionnaire_telephone) ?></td>
				<td><?= h($dispositifs->config_typepublic_id) ?></td>
				<td><?= h($dispositifs->config_environnement_id) ?></td>
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
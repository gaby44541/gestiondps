<div class="container-fluid">
	<h1>
		<?= __('Organisateur') ?>
		<?= $this->element('buttons',[	'controller'=>'organisateurs',
										'action_id'=>$organisateur->id,
										'options'=>['add','edit','index','delete','wizard']
							]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($organisateur->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Uuid') ?></th>
			<td><?= h($organisateur->uuid) ?></td>
		</tr>
		<tr>
			<th><?= __('Raison Sociale') ?></th>
			<td><?= $this->Number->format($organisateur->raison_sociale) ?></td>
		</tr>
		<tr>
			<th><?= __('Nom') ?></th>
			<td><?= h($organisateur->nom) ?></td>
		</tr>
		<tr>
			<th><?= __('Fonction') ?></th>
			<td><?= h($organisateur->fonction) ?></td>
		</tr>
		<tr>
			<th><?= __('Adresse') ?></th>
			<td><?= h($organisateur->adresse) ?></td>
		</tr>
		<tr>
			<th><?= __('Adresse Suite') ?></th>
			<td><?= h($organisateur->adresse_suite) ?></td>
		</tr>
		<tr>
			<th><?= __('Code Postal') ?></th>
			<td><?= h($organisateur->code_postal) ?></td>
		</tr>
		<tr>
			<th><?= __('Ville') ?></th>
			<td><?= h($organisateur->ville) ?></td>
		</tr>
		<tr>
			<th><?= __('Telephone') ?></th>
			<td><?= h($organisateur->telephone) ?></td>
		</tr>
		<tr>
			<th><?= __('Portable') ?></th>
			<td><?= h($organisateur->portable) ?></td>
		</tr>
		<tr>
			<th><?= __('Mail') ?></th>
			<td><?= h($organisateur->mail) ?></td>
		</tr>
		<tr>
			<th><?= __('Representant Prenom') ?></th>
			<td><?= h($organisateur->representant_prenom) ?></td>
		</tr>
		<tr>
			<th><?= __('Representant Nom') ?></th>
			<td><?= h($organisateur->representant_nom) ?></td>
		</tr>
		<tr>
			<th><?= __('Tresorier Nom') ?></th>
			<td><?= h($organisateur->tresorier_nom) ?></td>
		</tr>
		<tr>
			<th><?= __('Tresorier Prenom') ?></th>
			<td><?= h($organisateur->tresorier_prenom) ?></td>
		</tr>
		<tr>
			<th><?= __('Tresorier Mail') ?></th>
			<td><?= h($organisateur->tresorier_mail) ?></td>
		</tr>
		<tr>
			<th><?= __('Tresorier Telephone') ?></th>
			<td><?= h($organisateur->tresorier_telephone) ?></td>
		</tr>

	</table>
<div class="related">
	<h3>
		<?= __('Demandes') ?>
		<?= $this->element('buttons',['controller'=>'Demandes','options'=>'add']) ?>
	</h3>
<?php if (!empty($organisateur->demandes)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Demandes">
		<thead>
			<tr>
				<th><?= __('Manifestation') ?></th>
				<th><?= __('Representant') ?></th>
				<th><?= __('Representant Fonction') ?></th>
				<th><?= __('Config Etat Id') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($organisateur->demandes as $demandes): ?>
			<tr>
				<td><?= h($demandes->manifestation) ?></td>
				<td><?= h($demandes->representant) ?></td>
				<td><?= h($demandes->representant_fonction) ?></td>
				<td><?= h($demandes->config_etat_id) ?></td>
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

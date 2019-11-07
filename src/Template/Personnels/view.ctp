<div class="personnels view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('Personnel') ?>
		<?= $this->element('buttons',['controller'=>'personnels','action_id'=>$personnel->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($personnel->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Prenom') ?></th>
			<td><?= h($personnel->prenom) ?></td>
		</tr>
		<tr>
			<th><?= __('Nom') ?></th>
			<td><?= h($personnel->nom) ?></td>
		</tr>
		<tr>
			<th><?= __('Nom Naissance') ?></th>
			<td><?= h($personnel->nom_naissance) ?></td>
		</tr>
		<tr>
			<th><?= __('Statut') ?></th>
			<td><?= h($personnel->statut) ?></td>
		</tr>
		<tr>
			<th><?= __('Rue') ?></th>
			<td><?= h($personnel->rue) ?></td>
		</tr>
		<tr>
			<th><?= __('Code Postal') ?></th>
			<td><?= h($personnel->code_postal) ?></td>
		</tr>
		<tr>
			<th><?= __('Ville') ?></th>
			<td><?= h($personnel->ville) ?></td>
		</tr>
		<tr>
			<th><?= __('Identifiant') ?></th>
			<td><?= h($personnel->identifiant) ?></td>
		</tr>
		<tr>
			<th><?= __('Portable') ?></th>
			<td><?= h($personnel->portable) ?></td>
		</tr>
		<tr>
			<th><?= __('Telephone') ?></th>
			<td><?= h($personnel->telephone) ?></td>
		</tr>
		<tr>
			<th><?= __('Mail') ?></th>
			<td><?= h($personnel->mail) ?></td>
		</tr>
		<tr>
			<th><?= __('Antenne') ?></th>
			<td><?= h($personnel->antenne) ?></td>
		</tr>
		<tr>
			<th><?= __('Entreprise') ?></th>
			<td><?= h($personnel->entreprise) ?></td>
		</tr>
		<tr>
			<th><?= __('Naissance Date') ?></th>
			<td><?= h($personnel->naissance_date) ?></td>
		</tr>
		<tr>
			<th><?= __('Naissance Lieu') ?></th>
			<td><?= h($personnel->naissance_lieu) ?></td>
		</tr>
		<tr>
			<th><?= __('Prevenir') ?></th>
			<td><?= h($personnel->prevenir) ?></td>
		</tr>
		<tr>
			<th><?= __('Prevenir Telephone') ?></th>
			<td><?= h($personnel->prevenir_telephone) ?></td>
		</tr>

	</table>
<div class="related">
	<h3>
		<?= __('Antennes') ?>
		<?= $this->element('buttons',['controller'=>'Antennes','options'=>'add']) ?>
	</h3>
<?php if (!empty($personnel->antennes)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Antennes">
		<thead>
			<tr>
				<th><?= __('Antenne') ?></th>
				<th><?= __('Adresse') ?></th>
				<th><?= __('Adresse Suite') ?></th>
				<th><?= __('Code Postal') ?></th>
				<th><?= __('Ville') ?></th>
				<th><?= __('Telephone') ?></th>
				<th><?= __('Portable') ?></th>
				<th><?= __('Mail') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($personnel->antennes as $antennes): ?>
			<tr>
				<td><?= h($antennes->antenne) ?></td>
				<td><?= h($antennes->adresse) ?></td>
				<td><?= h($antennes->adresse_suite) ?></td>
				<td><?= h($antennes->code_postal) ?></td>
				<td><?= h($antennes->ville) ?></td>
				<td><?= h($antennes->telephone) ?></td>
				<td><?= h($antennes->portable) ?></td>
				<td><?= h($antennes->mail) ?></td>
				<td class="actions">
				<?= $this->element('actions',['controller'=>'Antennes','action_id'=>$antennes->id]) ?>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
</div>
<div class="related">
	<h3>
		<?= __('Equipes') ?>
		<?= $this->element('buttons',['controller'=>'Equipes','options'=>'add']) ?>
	</h3>
<?php if (!empty($personnel->equipes)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Equipes">
		<thead>
			<tr>
				<th><?= __('Dispositif Id') ?></th>
				<th><?= __('Indicatif') ?></th>
				<th><?= __('Effectif') ?></th>
				<th><?= __('Vehicule Type') ?></th>
				<th><?= __('Vehicules Km') ?></th>
				<th><?= __('Vehicule Trajets') ?></th>
				<th><?= __('Lot A') ?></th>
				<th><?= __('Lot B') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($personnel->equipes as $equipes): ?>
			<tr>
				<td><?= h($equipes->dispositif_id) ?></td>
				<td><?= h($equipes->indicatif) ?></td>
				<td><?= h($equipes->effectif) ?></td>
				<td><?= h($equipes->lot_a) ?></td>
				<td><?= h($equipes->lot_b) ?></td>
				<td class="actions">
				<?= $this->element('actions',['controller'=>'Equipes','action_id'=>$equipes->id]) ?>
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
	    $('#Antennes').DataTable();
	    $('#Equipes').DataTable();
	</script>

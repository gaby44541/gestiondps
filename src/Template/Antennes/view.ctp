<div class="container-fluid">
	<h1>
		<?= __('Antenne') ?>
		<?= $this->element('buttons',['controller'=>'antennes','action_id'=>$antenne->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($antenne->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Antenne') ?></th>
			<td><?= h($antenne->antenne) ?></td>
		</tr>
		<tr>
			<th><?= __('Adresse') ?></th>
			<td><?= h($antenne->adresse) ?></td>
		</tr>
		<tr>
			<th><?= __('Adresse Suite') ?></th>
			<td><?= h($antenne->adresse_suite) ?></td>
		</tr>
		<tr>
			<th><?= __('Code Postal') ?></th>
			<td><?= h($antenne->code_postal) ?></td>
		</tr>
		<tr>
			<th><?= __('Ville') ?></th>
			<td><?= h($antenne->ville) ?></td>
		</tr>
		<tr>
			<th><?= __('Telephone') ?></th>
			<td><?= h($antenne->telephone) ?></td>
		</tr>
		<tr>
			<th><?= __('Portable') ?></th>
			<td><?= h($antenne->portable) ?></td>
		</tr>
		<tr>
			<th><?= __('Mail') ?></th>
			<td><?= h($antenne->mail) ?></td>
		</tr>
		<tr>
			<th><?= __('Fax') ?></th>
			<td><?= h($antenne->fax) ?></td>
		</tr>
		<tr>
			<th><?= __('Rib Etablissemnt') ?></th>
			<td><?= h($antenne->rib_etablissemnt) ?></td>
		</tr>
		<tr>
			<th><?= __('Rib Guichet') ?></th>
			<td><?= h($antenne->rib_guichet) ?></td>
		</tr>
		<tr>
			<th><?= __('Rib Compte') ?></th>
			<td><?= h($antenne->rib_compte) ?></td>
		</tr>
		<tr>
			<th><?= __('Rib Rice') ?></th>
			<td><?= h($antenne->rib_rice) ?></td>
		</tr>
		<tr>
			<th><?= __('Rib Domicile') ?></th>
			<td><?= h($antenne->rib_domicile) ?></td>
		</tr>
		<tr>
			<th><?= __('Rib Bic') ?></th>
			<td><?= h($antenne->rib_bic) ?></td>
		</tr>
		<tr>
			<th><?= __('Rib Iban') ?></th>
			<td><?= h($antenne->rib_iban) ?></td>
		</tr>
		<tr>
			<th><?= __('Cheque') ?></th>
			<td><?= h($antenne->cheque) ?></td>
		</tr>
		<tr>
			<th><?= __('Etat') ?></th>
			<td><?= h($antenne->etat) ?></td>
		</tr>
	
	</table>
<div class="related">
	<h3>
		<?= __('Demandes') ?>
		<?= $this->element('buttons',['controller'=>'Demandes','options'=>'add']) ?>
	</h3>
<?php if (!empty($antenne->demandes)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Demandes">
		<thead>
			<tr>
				<th><?= __('Manifestation') ?></th>
				<th><?= __('Organisateur Id') ?></th>
				<th><?= __('Representant') ?></th>
				<th><?= __('Representant Fonction') ?></th>
				<th><?= __('Config Etat Id') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($antenne->demandes as $demandes): ?>
			<tr>
				<td><?= h($demandes->manifestation) ?></td>
				<td><?= h($demandes->organisateur_id) ?></td>
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
<div class="related">
	<h3>
		<?= __('Personnels') ?>
		<?= $this->element('buttons',['controller'=>'Personnels','options'=>'add']) ?>
	</h3>
<?php if (!empty($antenne->personnels)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Personnels">
		<thead>
			<tr>
				<th><?= __('Prenom') ?></th>
				<th><?= __('Nom') ?></th>
				<th><?= __('Nom Naissance') ?></th>
				<th><?= __('Statut') ?></th>
				<th><?= __('Rue') ?></th>
				<th><?= __('Code Postal') ?></th>
				<th><?= __('Ville') ?></th>
				<th><?= __('Identifiant') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($antenne->personnels as $personnels): ?>
			<tr>
				<td><?= h($personnels->prenom) ?></td>
				<td><?= h($personnels->nom) ?></td>
				<td><?= h($personnels->nom_naissance) ?></td>
				<td><?= h($personnels->statut) ?></td>
				<td><?= h($personnels->rue) ?></td>
				<td><?= h($personnels->code_postal) ?></td>
				<td><?= h($personnels->ville) ?></td>
				<td><?= h($personnels->identifiant) ?></td>
				<td class="actions">
				<?= $this->element('actions',['controller'=>'Personnels','action_id'=>$personnels->id]) ?>
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
	    $('#Personnels').DataTable();
	</script>
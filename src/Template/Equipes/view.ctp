<div class="equipes view content">
	<h1>
		<?= __('Equipe') ?>
		<?= $this->element('buttons',['controller'=>'equipes','action_id'=>$equipe->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($equipe->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Dispositif') ?></th>
			<td><?= $equipe->has('dispositif') ? $this->Html->link($equipe->dispositif->title, ['controller' => 'Dispositifs', 'action' => 'view', $equipe->dispositif->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Indicatif') ?></th>
			<td><?= h($equipe->indicatif) ?></td>
		</tr>
		<tr>
			<th><?= __('Effectif') ?></th>
			<td><?= $this->Number->format($equipe->effectif) ?></td>
		</tr>
		<tr>
			<th><?= __('Lot A') ?></th>
			<td><?= $this->Number->format($equipe->lot_a) ?></td>
		</tr>
		<tr>
			<th><?= __('Lot B') ?></th>
			<td><?= $this->Number->format($equipe->lot_b) ?></td>
		</tr>
		<tr>
			<th><?= __('Lot C') ?></th>
			<td><?= $this->Number->format($equipe->lot_c) ?></td>
		</tr>
		<tr>
			<th><?= __('Autre') ?></th>
			<td><?= h($equipe->autre) ?></td>
		</tr>
		<tr>
			<th><?= __('Consignes') ?></th>
			<td><?= $this->Text->autoParagraph(h($equipe->consignes)); ?></td>
		</tr>
		<tr>
			<th><?= __('Position') ?></th>
			<td><?= $this->Text->autoParagraph(h($equipe->position)); ?></td>
		</tr>
		<tr>
			<th><?= __('Horaires Convocation') ?></th>
			<td><?= h($equipe->horaires_convocation) ?></td>
		</tr>
		<tr>
			<th><?= __('Horaires Place') ?></th>
			<td><?= h($equipe->horaires_place) ?></td>
		</tr>
		<tr>
			<th><?= __('Horaires Fin') ?></th>
			<td><?= h($equipe->horaires_fin) ?></td>
		</tr>
		<tr>
			<th><?= __('Horaires Retour') ?></th>
			<td><?= h($equipe->horaires_retour) ?></td>
		</tr>
		<tr>
			<th><?= __('Duree') ?></th>
			<td><?= $this->Number->format($equipe->duree) ?></td>
		</tr>
		<tr>
			<th><?= __('Remarques') ?></th>
			<td><?= $this->Text->autoParagraph(h($equipe->remarques)); ?></td>
		</tr>
		<tr>
			<th><?= __('Remise') ?></th>
			<td><?= $this->Number->format($equipe->remise) ?></td>
		</tr>
		<tr>
			<th><?= __('Cout Repas') ?></th>
			<td><?= $this->Number->format($equipe->cout_repas) ?></td>
		</tr>
		<tr>
			<th><?= __('Cout Remise') ?></th>
			<td><?= $this->Number->format($equipe->cout_remise) ?></td>
		</tr>
		<tr>
			<th><?= __('Cout Economie') ?></th>
			<td><?= $this->Number->format($equipe->cout_economie) ?></td>
		</tr>
		<tr>
			<th><?= __('Repartition Antenne') ?></th>
			<td><?= $this->Number->format($equipe->repartition_antenne) ?></td>
		</tr>
		<tr>
			<th><?= __('Repartition Adpc') ?></th>
			<td><?= $this->Number->format($equipe->repartition_adpc) ?></td>
		</tr>
		<tr>
			<th><?= __('Repas Matin') ?></th>
			<td><?= $this->Number->format($equipe->repas_matin) ?></td>
		</tr>
		<tr>
			<th><?= __('Repas Midi') ?></th>
			<td><?= $this->Number->format($equipe->repas_midi) ?></td>
		</tr>
		<tr>
			<th><?= __('Repas Soir') ?></th>
			<td><?= $this->Number->format($equipe->repas_soir) ?></td>
		</tr>
		<tr>
			<th><?= __('Repas Charge') ?></th>
			<td><?= $equipe->repas_charge ? __('Yes') : __('No'); ?></td>
		</tr>

	</table>
<div class="related">
	<h3>
		<?= __('Personnels') ?>
		<?= $this->element('buttons',['controller'=>'Personnels','options'=>'add']) ?>
	</h3>
<?php if (!empty($equipe->personnels)): ?>
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
<?php foreach ($equipe->personnels as $personnels): ?>
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
	    $('#Personnels').DataTable();
	</script>

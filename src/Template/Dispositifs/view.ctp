<div class="dispositifs view content">
	<h1>
		<?= __('Dispositif') ?>
		<?= $this->element('buttons',['controller'=>'dispositifs','action_id'=>$dispositif->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($dispositif->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Dimensionnement') ?></th>
			<td><?= $dispositif->has('dimensionnement') ? $this->Html->link($dispositif->dimensionnement->intitule, ['controller' => 'Dimensionnements', 'action' => 'view', $dispositif->dimensionnement->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Title') ?></th>
			<td><?= h($dispositif->title) ?></td>
		</tr>
		<tr>
			<th><?= __('Gestionnaire Identite') ?></th>
			<td><?= h($dispositif->gestionnaire_identite) ?></td>
		</tr>
		<tr>
			<th><?= __('Gestionnaire Mail') ?></th>
			<td><?= h($dispositif->gestionnaire_mail) ?></td>
		</tr>
		<tr>
			<th><?= __('Gestionnaire Telephone') ?></th>
			<td><?= h($dispositif->gestionnaire_telephone) ?></td>
		</tr>
		<tr>
			<th><?= __('Config Typepublic') ?></th>
			<td><?= $dispositif->has('config_typepublic') ? $this->Html->link($dispositif->config_typepublic->designation, ['controller' => 'ConfigTypepublics', 'action' => 'view', $dispositif->config_typepublic->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Config Environnement') ?></th>
			<td><?= $dispositif->has('config_environnement') ? $this->Html->link($dispositif->config_environnement->environnement, ['controller' => 'ConfigEnvironnements', 'action' => 'view', $dispositif->config_environnement->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Config Delai') ?></th>
			<td><?= $dispositif->has('config_delai') ? $this->Html->link($dispositif->config_delai->designation, ['controller' => 'ConfigDelais', 'action' => 'view', $dispositif->config_delai->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Ris') ?></th>
			<td><?= $this->Number->format($dispositif->ris) ?></td>
		</tr>
		<tr>
			<th><?= __('Recommandation Type') ?></th>
			<td><?= h($dispositif->recommandation_type) ?></td>
		</tr>
		<tr>
			<th><?= __('Recommandation Poste') ?></th>
			<td><?= $this->Text->autoParagraph(h($dispositif->recommandation_poste)); ?></td>
		</tr>
		<tr>
			<th><?= __('Personnels Public') ?></th>
			<td><?= $this->Number->format($dispositif->personnels_public) ?></td>
		</tr>
		<tr>
			<th><?= __('Organisation Public') ?></th>
			<td><?= $this->Text->autoParagraph(h($dispositif->organisation_public)); ?></td>
		</tr>
		<tr>
			<th><?= __('Personnels Acteurs') ?></th>
			<td><?= $this->Number->format($dispositif->personnels_acteurs) ?></td>
		</tr>
		<tr>
			<th><?= __('Organisation Acteurs') ?></th>
			<td><?= $this->Text->autoParagraph(h($dispositif->organisation_acteurs)); ?></td>
		</tr>
		<tr>
			<th><?= __('Personnels Total') ?></th>
			<td><?= $this->Number->format($dispositif->personnels_total) ?></td>
		</tr>
		<tr>
			<th><?= __('Organisation Poste') ?></th>
			<td><?= $this->Text->autoParagraph(h($dispositif->organisation_poste)); ?></td>
		</tr>
		<tr>
			<th><?= __('Organisation Transport') ?></th>
			<td><?= $this->Text->autoParagraph(h($dispositif->organisation_transport)); ?></td>
		</tr>
		<tr>
			<th><?= __('Consignes Generales') ?></th>
			<td><?= $this->Text->autoParagraph(h($dispositif->consignes_generales)); ?></td>
		</tr>
		<tr>
			<th><?= __('Assiste') ?></th>
			<td><?= $this->Number->format($dispositif->assiste) ?></td>
		</tr>
		<tr>
			<th><?= __('Leger') ?></th>
			<td><?= $this->Number->format($dispositif->leger) ?></td>
		</tr>
		<tr>
			<th><?= __('Medicalise') ?></th>
			<td><?= $this->Number->format($dispositif->medicalise) ?></td>
		</tr>
		<tr>
			<th><?= __('Evacue') ?></th>
			<td><?= $this->Number->format($dispositif->evacue) ?></td>
		</tr>
		<tr>
			<th><?= __('Rapport') ?></th>
			<td><?= $this->Text->autoParagraph(h($dispositif->rapport)); ?></td>
		</tr>
		<tr>
			<th><?= __('Accord Siege') ?></th>
			<td><?= h($dispositif->accord_siege) ?></td>
		</tr>
		<tr>
			<th><?= __('Remise') ?></th>
			<td><?= $this->Number->format($dispositif->remise) ?></td>
		</tr>
	
	</table>
<div class="related">
	<h3>
		<?= __('Equipes') ?>
		<?= $this->element('buttons',['controller'=>'Equipes','options'=>'add']) ?>
	</h3>
<?php if (!empty($dispositif->equipes)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Equipes">
		<thead>
			<tr>
				<th><?= __('Indicatif') ?></th>
				<th><?= __('Effectif') ?></th>
				<th><?= __('Vehicule Type') ?></th>
				<th><?= __('Vehicules Km') ?></th>
				<th><?= __('Vehicule Trajets') ?></th>
				<th><?= __('Lot A') ?></th>
				<th><?= __('Lot B') ?></th>
				<th><?= __('Lot C') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($dispositif->equipes as $equipes): ?>
			<tr>
				<td><?= h($equipes->indicatif) ?></td>
				<td><?= h($equipes->effectif) ?></td>
				<td><?= h($equipes->vehicule_type) ?></td>
				<td><?= h($equipes->vehicules_km) ?></td>
				<td><?= h($equipes->vehicule_trajets) ?></td>
				<td><?= h($equipes->lot_a) ?></td>
				<td><?= h($equipes->lot_b) ?></td>
				<td><?= h($equipes->lot_c) ?></td>
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
	    $('#Equipes').DataTable();
	</script>
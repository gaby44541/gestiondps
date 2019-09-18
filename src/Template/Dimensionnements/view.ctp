<div class="dimensionnements view content">
	<h1>
		<?= __('Dimensionnement') ?>
		<?= $this->element('buttons',['controller'=>'dimensionnements','action_id'=>$dimensionnement->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($dimensionnement->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Demande') ?></th>
			<td><?= $dimensionnement->has('demande') ? $this->Html->link($dimensionnement->demande->manifestation, ['controller' => 'Demandes', 'action' => 'view', $dimensionnement->demande->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Intitule') ?></th>
			<td><?= h($dimensionnement->intitule) ?></td>
		</tr>
		<tr>
			<th><?= __('Horaires Debut') ?></th>
			<td><?= h($dimensionnement->horaires_debut) ?></td>
		</tr>
		<tr>
			<th><?= __('Horaires Fin') ?></th>
			<td><?= h($dimensionnement->horaires_fin) ?></td>
		</tr>
		<tr>
			<th><?= __('Lieu Manifestation') ?></th>
			<td><?= h($dimensionnement->lieu_manifestation) ?></td>
		</tr>
		<tr>
			<th><?= __('Risques Particuliers') ?></th>
			<td><?= h($dimensionnement->risques_particuliers) ?></td>
		</tr>
		<tr>
			<th><?= __('Contact Portable') ?></th>
			<td><?= h($dimensionnement->contact_portable) ?></td>
		</tr>
		<tr>
			<th><?= __('Contact Present') ?></th>
			<td><?= h($dimensionnement->contact_present) ?></td>
		</tr>
		<tr>
			<th><?= __('Contact Fonction') ?></th>
			<td><?= h($dimensionnement->contact_fonction) ?></td>
		</tr>
		<tr>
			<th><?= __('Contact Telephone') ?></th>
			<td><?= h($dimensionnement->contact_telephone) ?></td>
		</tr>
		<tr>
			<th><?= __('Public Effectif') ?></th>
			<td><?= $this->Number->format($dimensionnement->public_effectif) ?></td>
		</tr>
		<tr>
			<th><?= __('Public Age') ?></th>
			<td><?= h($dimensionnement->public_age) ?></td>
		</tr>
		<tr>
			<th><?= __('Acteurs Effectif') ?></th>
			<td><?= $this->Number->format($dimensionnement->acteurs_effectif) ?></td>
		</tr>
		<tr>
			<th><?= __('Acteurs Age') ?></th>
			<td><?= h($dimensionnement->acteurs_age) ?></td>
		</tr>
		<tr>
			<th><?= __('Circuit') ?></th>
			<td><?= $dimensionnement->circuit ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Ouvert') ?></th>
			<td><?= $dimensionnement->ouvert ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Superficie') ?></th>
			<td><?= h($dimensionnement->superficie) ?></td>
		</tr>
		<tr>
			<th><?= __('Distance Maxi') ?></th>
			<td><?= h($dimensionnement->distance_maxi) ?></td>
		</tr>
		<tr>
			<th><?= __('Acces') ?></th>
			<td><?= h($dimensionnement->acces) ?></td>
		</tr>
		<tr>
			<th><?= __('Besoins Particuliers') ?></th>
			<td><?= h($dimensionnement->besoins_particuliers) ?></td>
		</tr>
		<tr>
			<th><?= __('Pompier') ?></th>
			<td><?= h($dimensionnement->pompier) ?></td>
		</tr>
		<tr>
			<th><?= __('Pompier Distance') ?></th>
			<td><?= h($dimensionnement->pompier_distance) ?></td>
		</tr>
		<tr>
			<th><?= __('Hopital') ?></th>
			<td><?= h($dimensionnement->hopital) ?></td>
		</tr>
		<tr>
			<th><?= __('Hopital Distance') ?></th>
			<td><?= h($dimensionnement->hopital_distance) ?></td>
		</tr>
		<tr>
			<th><?= __('Arrete') ?></th>
			<td><?= $dimensionnement->arrete ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Commission') ?></th>
			<td><?= $dimensionnement->commission ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Plans') ?></th>
			<td><?= $dimensionnement->plans ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Annuaire') ?></th>
			<td><?= $dimensionnement->annuaire ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Documents Autres') ?></th>
			<td><?= $dimensionnement->documents_autres ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Medecin') ?></th>
			<td><?= h($dimensionnement->medecin) ?></td>
		</tr>
		<tr>
			<th><?= __('Medecin Telephone') ?></th>
			<td><?= h($dimensionnement->medecin_telephone) ?></td>
		</tr>
		<tr>
			<th><?= __('Infirmier') ?></th>
			<td><?= h($dimensionnement->infirmier) ?></td>
		</tr>
		<tr>
			<th><?= __('Kiné') ?></th>
			<td><?= h($dimensionnement->kiné) ?></td>
		</tr>
		<tr>
			<th><?= __('Medecin Autres') ?></th>
			<td><?= h($dimensionnement->medecin_autres) ?></td>
		</tr>
		<tr>
			<th><?= __('Smur') ?></th>
			<td><?= $dimensionnement->smur ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Sp') ?></th>
			<td><?= $dimensionnement->sp ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Police') ?></th>
			<td><?= $dimensionnement->police ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Gendarmerie') ?></th>
			<td><?= $dimensionnement->gendarmerie ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Ambulancier') ?></th>
			<td><?= h($dimensionnement->ambulancier) ?></td>
		</tr>
		<tr>
			<th><?= __('Ambulancier Telephone') ?></th>
			<td><?= h($dimensionnement->ambulancier_telephone) ?></td>
		</tr>
		<tr>
			<th><?= __('Autres Public') ?></th>
			<td><?= h($dimensionnement->autres_public) ?></td>
		</tr>
		<tr>
			<th><?= __('Autres') ?></th>
			<td><?= h($dimensionnement->autres) ?></td>
		</tr>
	
	</table>
<div class="related">
	<h3>
		<?= __('Dispositifs') ?>
		<?= $this->element('buttons',['controller'=>'Dispositifs','options'=>'add']) ?>
	</h3>
<?php if (!empty($dimensionnement->dispositifs)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Dispositifs">
		<thead>
			<tr>
				<th><?= __('Title') ?></th>
				<th><?= __('Gestionnaire Identite') ?></th>
				<th><?= __('Gestionnaire Mail') ?></th>
				<th><?= __('Gestionnaire Telephone') ?></th>
				<th><?= __('Config Typepublic Id') ?></th>
				<th><?= __('Config Environnement Id') ?></th>
				<th><?= __('Config Delai Id') ?></th>
				<th><?= __('Ris') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($dimensionnement->dispositifs as $dispositifs): ?>
			<tr>
				<td><?= h($dispositifs->title) ?></td>
				<td><?= h($dispositifs->gestionnaire_identite) ?></td>
				<td><?= h($dispositifs->gestionnaire_mail) ?></td>
				<td><?= h($dispositifs->gestionnaire_telephone) ?></td>
				<td><?= h($dispositifs->config_typepublic_id) ?></td>
				<td><?= h($dispositifs->config_environnement_id) ?></td>
				<td><?= h($dispositifs->config_delai_id) ?></td>
				<td><?= h($dispositifs->ris) ?></td>
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
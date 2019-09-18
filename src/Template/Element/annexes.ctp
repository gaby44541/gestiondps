
<?php if (!empty($demande->dimensionnements)): ?>
<?php foreach ($demande->dimensionnements as $key => $dimensionnements): ?>
<div style="page-break-after: always;"></div>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Dimensionnements">
		<tbody>
			<tr>
				<th colspan="2">
				<center>Annexe n°<?= $key ?> - <?= h($dimensionnements->intitule) ?></center>
				<div style="margin:0px; text-align:center;">
					<b>Du </b><?= $this->Time->format($dimensionnements->horaires_debut,\IntlDateFormatter::FULL) ?><b> au </b><?= $this->Time->format($dimensionnements->horaires_fin,\IntlDateFormatter::FULL) ?><br/>
					<b>Lieu de rendez-vous : </b><?= h($dimensionnements->lieu_manifestation) ?><br/>
					<b>Contact : </b><?= h($dimensionnements->contact_present) ?> - <?= h($dimensionnements->contact_fonction) ?> (<?= h($dimensionnements->contact_portable) ?> - <?= h($dimensionnements->contact_telephone) ?>)<br/>
				</div>
				</th>
			</tr>
			<tr>
				<th><center><?= __('Dimensionnement public') ?></center></th>
				<th><center><?= __('Dimensionnement acteurs') ?></center></th>
			</tr>
			<tr>
				<td>
					RIS : <?= h($dimensionnements->dispositif->ris) ?> - Recommandations :<br/>
					<?= h($dimensionnements->dispositif->recommandation_type) ?><br/>
					Personnels nécessaires et retenus : <?= h($dimensionnements->dispositif->personnels_public) ?>
				</td>
				<td>
					Personnels nécessaires et retenus : <?= h($dimensionnements->dispositif->personnels_acteurs) ?>
				</td>
			</tr>
			<tr>
				<td>
					<center>Organisation du dispositif public :</center>
				</td>
				<td>
					<center>Organisation du dispositif acteurs :</center>
				</td>
			</tr>
			<tr>
				<td style="width:50%;">
					<?= nl2br($dimensionnements->dispositif->organisation_public) ?>
				</td>
				<td>
					<?= nl2br($dimensionnements->dispositif->organisation_acteurs) ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<center>Organisation globale :</center>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?= nl2br($dimensionnements->dispositif->organisation_poste) ?>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<center>Transports :</center>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?= nl2br($dimensionnements->dispositif->organisation_transport) ?>
				</td>
			</tr>
		</tbody>
	</table>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Equipes">
		<tbody>
			<tr>
				<td><?= __('Indicatif') ?></td>
				<td><?= __('Equipier') ?></td>
				<td><?= __('Véhicule') ?></td>
				<td><?= __('Lot A') ?></td>
				<td><?= __('Lot B') ?></td>
				<td><?= __('Lot C') ?></td>
				<td><?= __('Autre') ?></td>
				<td><?= __('Convocation') ?></td>
				<td><?= __('En place') ?></td>
				<td><?= __('Termine à') ?></td>
				<td><?= __('Repas à votre charge') ?></td>
			</tr>
<?php foreach ($dimensionnements->dispositif->equipes as $equipe): ?>
			<tr>
				<td style="width:1%;">
					<?= h($equipe->indicatif) ?>
				</td>
				<td style="width:1%;">
					<?= h($equipe->effectif) ?>
				</td>
				<td>
					<?= h($equipe->vehicule_type) ?>
				</td>
				<td style="width:1%;">
					<?= h($equipe->lot_a) ?>
				</td>
				<td style="width:1%;">
					<?= h($equipe->lot_b) ?>
				</td>
				<td style="width:1%;">
					<?= h($equipe->lot_c) ?>
				</td>
				<td>
					<?= h($equipe->autre) ?>
				</td>
				<td>
					<?= $this->Time->format($equipe->horaires_convocation,\IntlDateFormatter::SHORT) ?>
				</td>
				<td>
					<?= $this->Time->format($equipe->horaires_place,\IntlDateFormatter::SHORT) ?>
				</td>
				<td>
					<?= $this->Time->format($equipe->horaires_fin,\IntlDateFormatter::SHORT) ?>
				</td>
				<td>
					<?= h($equipe->repas_charge * $equipe->repas_matin) ?> Matin<br/>
					<?= h($equipe->repas_charge * $equipe->repas_midi) ?> Midi<br/>
					<?= h($equipe->repas_charge * $equipe->repas_soir) ?> Soir
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
<?php endforeach; ?>
<?php endif; ?>

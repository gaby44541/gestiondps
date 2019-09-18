<center style="background-color:#f2f2f2;"><?= __('DISPOSITIF PREVISIONNEL DE SECOURS - FACTURE N° ') . $demande->id ?></center>
<br/>
<table cellpadding="0" cellspacing="0" class="table" >
	<thead>
		<tr>
			<td style="width:60%;">
				<b>Représentant légal :</b><br />
				<?= h($demande->organisateur->representant_nom) ?> <?= h($demande->organisateur->representant_prenom) ?> -  <?= h($demande->organisateur->fonction) ?><br />
				<?= h($demande->organisateur->telephone) ?> <?= h($demande->organisateur->portable) ?><br />
				<?= h($demande->organisateur->mail) ?><br />
				<br /> 
				<b>En charge des modalités financières :</b><br />
				<?= h($demande->organisateur->tresorier_nom) ?> <?= h($demande->organisateur->trésorier_prenom) ?> - <?= h($demande->organisateur->trésorier_telephone) ?><br />
				<?= h($demande->organisateur->trésorier_mail) ?>			
			</td>
			<td style="width:40%; font-size:22px;">
				<?= h($demande->organisateur->nom) ?><br />
				<?= h($demande->organisateur->adresse) ?><br />
				<?= h($demande->organisateur->adresse_suite) ?><br />
				<?= h($demande->organisateur->code_postal) ?> <?= h($demande->organisateur->ville) ?>
			</td>
		</tr>
	</thead>
</table>
<br/>
<center>La facture concerne les éléments suivants</center>
<div style="text-align:center;"><b>Evénement : <?= h($demande->manifestation) ?></b></div>
<br/>
<table class="table">
<?php foreach ($demande->dimensionnements as $key => $dimensionnements): ?>
<tr>>
	<td><b><?= h($dimensionnements->intitule) ?></b></td>
	<td>Du <?= $this->Time->format($dimensionnements->horaires_debut,\IntlDateFormatter::FULL) ?></td>
	<td>au <?= $this->Time->format($dimensionnements->horaires_fin,\IntlDateFormatter::FULL) ?></td>
</tr>
<?php endforeach; ?>
</table>
<br/>	
<center style="background-color:#f2f2f2;">Montant total à régler : <?= h($demande->total_cout_total) ?> €</center>
<?php if( $demande->remise_pourcent != 0 ){?>
<div style="color:red; margin:0px;">
    <b><?= nl2br($demande->remise_justification) ?></b>
</div>
<br/>	
<?php } ?>
<br/>
<div style="color:red; margin:0px;">
    <b>Attention : nous vous rappelons que le réglement doit s'effectuer sous 7 jours. Passé ce délai, vous vous exposez à une majoration de 10% du montant toal et par semaine de retard.</b>
</div>
<br/>
<br/>
<table class="table">
<tbody>
<tr>
<td style="width:50%; border:1px solid #f2f2f2;">
	<center>Virement bancaire (à privilégier)</center>
	Etablissement : <?= h($demande->antenne->rib_etablissement) ?><br/>
	Guichet : <?= h($demande->antenne->rib_guichet) ?><br/>
	Compte : <?= h($demande->antenne->rib_compte) ?><br/>
	RICE : <?= h($demande->antenne->rib_rice) ?><br/>
	Domicile : <?= h($demande->antenne->rib_domicile) ?><br/>
	BIC : <?= h($demande->antenne->rib_bic) ?><br/>
	IBAN : <?= h($demande->antenne->rib_iban) ?><br/>
</td>
<td style="width:50%; border:1px solid #f2f2f2;">
	<center>Par chèque bancaire</center>
	Ordre : <?= h($demande->antenne->cheque) ?><br/>
	(Précisez le numéro de la facture au dos)<br/>
	<br/>
	<b>A envoyer à : </b><br/>
	<?= h($demande->antenne->antenne) ?><br/>
	<?= h($demande->antenne->adresse) ?><br/>
	<?= h($demande->antenne->adresse_suite) ?><br/>
	<?= h($demande->antenne->code_postal) ?> <?= h($demande->antenne->ville) ?><br/>
</td>
</tr>
</tbody>
</table>
<div style="text-align:right;">
Fait à <?= h($demande->antenne->ville) ?>, le <?= date('d/m/Y') ?><br/>
<br/>
<i>Pour la Protection Civile et par délégation,</i>
<?= h($demande->gestionnaire_nom) ?><br/>
<?= h($demande->gestionnaire_telephone) ?><br/>
<?= h($demande->gestionnaire_mail) ?><br/>
</div>
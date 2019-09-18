<center style="background-color:#f2f2f2;"><?= __('DISPOSITIF PREVISIONNEL DE SECOURS - CONVENTION N° ') . $demande->id ?></center>
<br/>
<table cellpadding="0" cellspacing="0" class="table" >
	<thead>
		<tr>
			<td>
				<b><?= __('Entre d\'une part') ?></b><br/>
				<?= __('La Protection Civile') ?><br/>
				<?= h($demande->antenne->antenne) ?><br/>
				<?= h($demande->antenne->adresse) ?><br />
				<?= h($demande->antenne->adresse_suite) ?><br />
				<?= h($demande->antenne->code_postal) ?> <?= h($demande->antenne->ville) ?><br />
				<b>Représentée par son président départemental</b><br />
				<b>Donnant délégation pour ce dossier à</b><br />
				<?= h($demande->gestionnaire_nom) ?> - <?= h($demande->gestionnaire_telephone) ?><br/>
				<?= h($demande->gestionnaire_mail) ?><br/>
			</td>
			<td style="width:50%;">
				<b><?= __('Et d\'autre part l\'organisateur') ?></b><br/>
				<?= h($demande->organisateur->nom) ?><br />
				<?= h($demande->organisateur->adresse) ?><br />
				<?= h($demande->organisateur->adresse_suite) ?><br />
				<?= h($demande->organisateur->code_postal) ?> <?= h($demande->organisateur->ville) ?><br />
				<?= h($demande->organisateur->telephone) ?> <?= h($demande->organisateur->portable) ?><br />
				<?= h($demande->organisateur->mail) ?><br />
				<br />
				<b>Représenté par :</b><br />
				<?= h($demande->organisateur->representant_nom) ?> <?= h($demande->organisateur->representant_prenom) ?> -  <?= h($demande->organisateur->fonction) ?><br />
				<br /> 
			</td>
		</tr>
	</thead>
</table>
<?php foreach( $convention as $article ): ?>
<div>
<center>Article <?= $article->ordre ?> - <?= $article->designation ?></center>
<?= nl2br( $article->description ) ?>
</div>
<br/>
<?php endforeach; ?>
<br/>
<div>
<center>Détails de la manifestation et liste des annexes</center>
<ul>
<?php foreach ($demande->dimensionnements as $key => $dimensionnements): ?>
<li>
<b><?= h($dimensionnements->intitule) ?></b>&nbsp;<b style="color:red;">( Annexe n°<?= $key ?> )</b><br/>
<b>Du </b><?= $this->Time->format($dimensionnements->horaires_debut,\IntlDateFormatter::FULL) ?><b> au </b><?= $this->Time->format($dimensionnements->horaires_fin,\IntlDateFormatter::FULL) ?><br/>

</li>
<?php endforeach; ?>
</ul>
</div>
<table class="table">
<tbody>
<tr>
<td style="width:50%; border:1px solid #f2f2f2; text-align:center;"><b>Cadre réservé à la Protection Civile<br/>Nom, prénom, fonction, signature.</b><br/><br/><br/><br/><br/><br/><br/><br/><b>Fait à _____________________ , le ___ / ___ / _______</b></td>
<td style="width:50%; border:1px solid #f2f2f2; text-align:center;"><b>Cadre réservé à l'organisateur<br/>Nom, prénom, fonction, cachet, signature.</b><br/><br/><br/><br/><br/><br/><br/><br/><b>Fait à _____________________ , le ___ / ___ / _______</b></td>
</tr>
</tbody>
</table>
<?= $this->element('annexes',['demande'=>$demande]) ?>
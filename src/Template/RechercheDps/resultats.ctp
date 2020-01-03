<div class="container-fluid">
	<h1>
		<?= __('Résultats') ?>
	</h1>


	 <h3>DPS</h3>
         <table class="vertical-table table table-striped">
             <tr>
                 <th>Date DPS</th>
                 <th>Nom DPS</th>
                 <th>Relances</th>
                 <th>Mail organisateur</th>
                 <th>Mail responsable protection civile</th>
             </tr>
            <?php  foreach($resultats as $resultat):
            if(isset($resultat->dimensionnements[0])){
            ?>
                 <tr>
                     <td>Du <?= h($resultat->dimensionnements[0]->horaires_debut) ?> Au <?= h($resultat->dimensionnements[0]->horaires_fin) ?></td>
                     <td><?= h($resultat->dimensionnements[0]->intitule) ?></td>
                     <td><?= h($resultat->id) ?></td>
                     <td><a href="mailto:<?=$resultat->organisateur->mail?>"><?= h($resultat->organisateur->mail) ?></a></td>
                     <td><a href="mailto:<?=$resultat->gestionnaire_mail?>"><?= h($resultat->gestionnaire_mail) ?></a></td>
                 </tr>
                  <?php
                  } endforeach; ?>
         </table>
</div>

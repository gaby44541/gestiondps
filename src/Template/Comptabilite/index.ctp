<div class="container-fluid">
	<h1>
		<?= __('Comptabilité') ?>
	</h1>

    <?= $this->Html->link("Recherche DPS", array('controller' => 'rechercheDps','action'=> 'index', 'rule' => 'button'), array( 'class' => 'btn btn-primary btn-sm')) ?>

     <h3>DPS facturés</h3>
     <table class="vertical-table table table-striped">
         <tr>
             <th>Date DPS</th>
             <th>Nom DPS</th>
             <th>Relances</th>
             <th>Mail organisateur</th>
             <th>Mail responsable protection civile</th>
         </tr>
        <?php  foreach($demandesFacturees as $demandeFacturees):
        if(isset($demandeFacturees->dimensionnements[0])){
        ?>
             <tr>
                 <td>Du <?= h($demandeFacturees->dimensionnements[0]->horaires_debut) ?> Au <?= h($demandeFacturees->dimensionnements[0]->horaires_fin) ?></td>
                 <td><?= h($demandeFacturees->dimensionnements[0]->intitule) ?></td>
                 <td><?= h($demandeFacturees->id) ?></td>
                 <td><a href="mailto:<?=$demandeFacturees->organisateur->mail?>"><?= h($demandeFacturees->organisateur->mail) ?></a></td>
                 <td><a href="mailto:<?=$demandeFacturees->gestionnaire_mail?>"><?= h($demandeFacturees->gestionnaire_mail) ?></a></td>
             </tr>
              <?php
              } endforeach; ?>
     </table>


     <h3>DPS non réglés</h3>
    <table class="vertical-table table table-striped">
           <tr>
               <th>Date DPS</th>
               <th>Nom DPS</th>
               <th>Relances</th>
               <th>Mail organisateur</th>
               <th>Mail responsable protection civile</th>
           </tr>
          <?php  foreach($demandesAttenteFacture as $demandeAttenteFacture):
          if(isset($demandeAttenteFacture->dimensionnements[0])){
          ?>
               <tr>
                   <td>Du <?= h($demandeAttenteFacture->dimensionnements[0]->horaires_debut) ?> Au <?= h($demandeAttenteFacture->dimensionnements[0]->horaires_fin) ?></td>
                   <td><?= h($demandeAttenteFacture->dimensionnements[0]->intitule) ?></td>
                   <td><?= h($demandeAttenteFacture->id) ?></td>
                   <td><a href="mailto:<?=$demandeAttenteFacture->organisateur->mail?>"><?= h($demandeAttenteFacture->organisateur->mail) ?></a></td>
                   <td><a href="mailto:<?=$demandeAttenteFacture->gestionnaire_mail?>"><?= h($demandeAttenteFacture->gestionnaire_mail) ?></a></td>
               </tr>
                <?php
                } endforeach; ?>
       </table>

</div>

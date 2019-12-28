<div class="container-fluid">
	<h1>
		<?= __('Comptabilité') ?>
	</h1>

     <?= $this->Form->button('Rechercher',[]) ?>

     <h3>DPS facturés</h3>
     <table class="vertical-table table table-striped">
         <tr>
             <th>Date DPS</th>
             <th>Nom DPS</th>
             <th>Relances</th>
             <th>Mail organisateur</th>
             <th>Mail trésorier</th>
         </tr>
        <?php  foreach($demandes as $demande):
        ?>
             <tr>
                 <td>Du <?= h($demande->dimensionnements[0]->horaires_debut) ?> Au <?= h($demande->dimensionnements[0]->horaires_fin) ?></td>
                 <td><?= h($demande->dimensionnements[0]->intitule) ?></td>
                 <td><?= h($demande->id) ?></td>
                 <td><?= h($demande->id) ?></td>
                 <td><?= h($demande->id) ?></td>
             </tr>
              <?php endforeach; ?>
     </table>


     <h3>DPS non réglés</h3>
    <table class="vertical-table table table-striped">
    <tr>
    <th>Date DPS</th>
    <th>Nom DPS</th>
    <th>Relances</th>
    <th>Mail organisateur</th>
    <th>Mail trésorier</th>
    </tr>
    <tr>
     <td>a</td>
     <td>b</td>
     <td>c</td>
     <td>d</td>
     <td>e</td>
    </tr>
    </table>

</div>

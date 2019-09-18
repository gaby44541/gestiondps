<div class="dimensionnements index content">
	<h1>
		<?= __('Dimensionnements') ?>
		<?= $this->element('buttons',['controller'=>'dimensionnements','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="dimensionnements">
	    <thead>
	        <tr>
        	            <th><?= __('Demande Id') ?></th>
        	            <th><?= __('Intitule') ?></th>
        	            <th><?= __('Horaires Debut') ?></th>
        	            <th><?= __('Horaires Fin') ?></th>
        	            <th><?= __('Lieu Manifestation') ?></th>
        	            <th><?= __('Risques Particuliers') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($dimensionnements as $dimensionnement): ?>
	        <tr>
        	            <td><?= $dimensionnement->has('demande') ? $this->Html->link($dimensionnement->demande->manifestation, ['controller' => 'Demandes', 'action' => 'view', $dimensionnement->demande->id]) : '' ?></td>
        	            <td><?= h($dimensionnement->intitule) ?></td>
        	            <td><?= h($dimensionnement->horaires_debut) ?></td>
        	            <td><?= h($dimensionnement->horaires_fin) ?></td>
        	            <td><?= h($dimensionnement->lieu_manifestation) ?></td>
        	            <td><?= h($dimensionnement->risques_particuliers) ?></td>
					<?= $this->element('actions',['controller'=>'dimensionnements','action_id'=>$dimensionnement->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#dimensionnements').DataTable();
</script>

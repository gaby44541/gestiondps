<div class="dispositifs index content">
	<h1>
		<?= __('Dispositifs') ?>
		<?= $this->element('buttons',['controller'=>'dispositifs','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="dispositifs">
	    <thead>
	        <tr>
        	            <th><?= __('Dimensionnement Id') ?></th>
        	            <th><?= __('Title') ?></th>
        	            <th><?= __('Personnels Total') ?></th>
        	            <th><?= __('Organisation Poste') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($dispositifs as $dispositif): ?>
	        <tr>
        	            <td><?= $dispositif->has('dimensionnement') ? $this->Html->link($dispositif->dimensionnement->intitule, ['controller' => 'Dimensionnements', 'action' => 'view', $dispositif->dimensionnement->id]) : '' ?></td>
        	            <td><?= h($dispositif->title) ?></td>
        	            <td><?= h($dispositif->personnels_total) ?></td>
						<td><?= nl2br($dispositif->organisation_poste) ?></td>
						<?= $this->element('actions',['controller'=>'dispositifs','action_id'=>$dispositif->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#dispositifs').DataTable();
</script>

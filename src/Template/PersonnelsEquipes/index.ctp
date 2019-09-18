<div class="personnelsEquipes index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Personnels Equipes') ?>
		<?= $this->element('buttons',['controller'=>'personnelsEquipes','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="personnelsEquipes">
	    <thead>
	        <tr>
        	            <th><?= __('Equipe Id') ?></th>
        	            <th><?= __('Personnel Id') ?></th>
        	            <th><?= __('Disponible') ?></th>
        	            <th><?= __('Selection') ?></th>
						<th><?= __('Chef') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($personnelsEquipes as $personnelsEquipe): ?>
	        <tr>
        	            <td><?= $personnelsEquipe->has('equipe') ? $this->Html->link($personnelsEquipe->equipe->id, ['controller' => 'Equipes', 'action' => 'view', $personnelsEquipe->equipe->id]) : '' ?></td>
        	            <td><?= $personnelsEquipe->has('personnel') ? $this->Html->link($personnelsEquipe->personnel->id, ['controller' => 'Personnels', 'action' => 'view', $personnelsEquipe->personnel->id]) : '' ?></td>
        	            <td><?= h($personnelsEquipe->disponibilite) ?></td>
						<td><?= h($personnelsEquipe->selection) ?></td>
						<td><?= h($personnelsEquipe->chef) ?></td>
					<?= $this->element('actions',['controller'=>'personnelsEquipes','action_id'=>$personnelsEquipe->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#personnelsEquipes').DataTable();
</script>

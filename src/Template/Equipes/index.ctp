<div class="equipes index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Equipes') ?>
		<?= $this->element('buttons',['controller'=>'equipes','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="equipes">
	    <thead>
	        <tr>
        	            <th><?= __('Dispositif Id') ?></th>
        	            <th><?= __('Indicatif') ?></th>
        	            <th><?= __('Effectif') ?></th>
        	            <th><?= __('Vehicule Type') ?></th>
        	            <th><?= __('Vehicules Km') ?></th>
        	            <th><?= __('Vehicule Trajets') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($equipes as $equipe): ?>
	        <tr>
        	            <td><?= $equipe->has('dispositif') ? $this->Html->link($equipe->dispositif->title, ['controller' => 'Dispositifs', 'action' => 'view', $equipe->dispositif->id]) : '' ?></td>
        	            <td><?= h($equipe->indicatif) ?></td>
        	            <td><?= $this->Number->format($equipe->effectif) ?></td>
        	            <td><?= h($equipe->vehicule_type) ?></td>
        	            <td><?= $this->Number->format($equipe->vehicules_km) ?></td>
        	            <td><?= $this->Number->format($equipe->vehicule_trajets) ?></td>
					<?= $this->element('actions',['controller'=>'equipes','action_id'=>$equipe->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#equipes').DataTable();
</script>

<div class="personnels index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Personnels') ?>
		<?= $this->element('buttons',['controller'=>'personnels','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="personnels">
	    <thead>
	        <tr>
        	            <th><?= __('Prenom') ?></th>
        	            <th><?= __('Nom') ?></th>
        	            <th><?= __('Nom Naissance') ?></th>
        	            <th><?= __('Statut') ?></th>
        	            <th><?= __('Rue') ?></th>
        	            <th><?= __('Code Postal') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($personnels as $personnel): ?>
	        <tr>
        	            <td><?= h($personnel->prenom) ?></td>
        	            <td><?= h($personnel->nom) ?></td>
        	            <td><?= h($personnel->nom_naissance) ?></td>
        	            <td><?= h($personnel->statut) ?></td>
        	            <td><?= h($personnel->rue) ?></td>
        	            <td><?= h($personnel->code_postal) ?></td>
				<?= $this->element('actions',['controller'=>'personnels','action_id'=>$personnel->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#personnels').DataTable();
</script>

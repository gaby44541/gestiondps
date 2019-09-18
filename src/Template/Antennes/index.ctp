<div class="container-fluid">
	<h1>
		<?= __('Antennes') ?>
		<?= $this->element('buttons',['controller'=>'antennes','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="antennes">
	    <thead>
	        <tr>
        	            <th><?= __('Antenne') ?></th>
        	            <th><?= __('Adresse') ?></th>
        	            <th><?= __('Adresse Suite') ?></th>
        	            <th><?= __('Code Postal') ?></th>
        	            <th><?= __('Ville') ?></th>
        	            <th><?= __('Telephone') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($antennes as $antenne): ?>
	        <tr>
        	            <td><?= h($antenne->antenne) ?></td>
        	            <td><?= h($antenne->adresse) ?></td>
        	            <td><?= h($antenne->adresse_suite) ?></td>
        	            <td><?= h($antenne->code_postal) ?></td>
        	            <td><?= h($antenne->ville) ?></td>
        	            <td><?= h($antenne->telephone) ?></td>
					<?= $this->element('actions',['controller'=>'antennes','action_id'=>$antenne->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#antennes').DataTable();
</script>

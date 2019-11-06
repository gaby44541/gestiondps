<div class="configParametres index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Config Parametres') ?>
	</h1>

	<table class="table table-hover table-striped" id="configParametres">
	    <thead>
	        <tr>
        	            <th><?= __('Pourcentage') ?></th>
        	            <th><?= __('Cout Kilometres') ?></th>
        	            <th><?= __('Cout Repas') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configParametres as $configParametre): ?>
	        <tr>
        	            <td><?= $this->Number->format($configParametre->pourcentage) ?></td>
        	            <td><?= $this->Number->format($configParametre->cout_kilometres) ?></td>
        	            <td><?= $this->Number->format($configParametre->cout_repas) ?></td>
					<?= $this->element('actions',['controller'=>'configParametres','action_id'=>$configParametre->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#configParametres').DataTable();
</script>

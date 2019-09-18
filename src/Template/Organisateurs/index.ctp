<div class="container-fluid">
	<h1>
		<?= __('Organisateurs') ?>
		<?= $this->element('buttons',['controller'=>'organisateurs','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="organisateurs">
	    <thead>
	        <tr>
        	            <th><?= __('Nom') ?></th>
        	            <th><?= __('Fonction') ?></th>
        	            <th><?= __('Adresse') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($organisateurs as $organisateur): ?>
	        <tr>
        	            <td>
							<b><?= h($organisateur->nom) ?></b><br />
							<small><?= h($organisateur->uuid) ?></small><br />
							<small><?= h($organisateur->mail) ?></small>
						</td>
        	            <td>
							<?= h($organisateur->representant_nom) ?>&nbsp;<?= h($organisateur->representant_prenom) ?><br/>
							<small><?= h($organisateur->fonction) ?></small>
						</td>
        	            <td>
							<small>
							<?= h($organisateur->adresse) ?><br/>
							<?= h($organisateur->adresse_suite) ?><br/>
							<?= h($organisateur->code_postal) ?>&nbsp;<?= h($organisateur->ville) ?>
							</small>
						</td>
				<?= $this->element('actions',['controller'=>'organisateurs','action_id'=>$organisateur->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#organisateurs').DataTable();
</script>

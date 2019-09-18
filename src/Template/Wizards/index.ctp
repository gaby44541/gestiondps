<div class="wizards index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Wizards') ?>
		<?= $this->element('buttons',['controller'=>'wizards','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="wizards">
	    <thead>
	        <tr>
        	            <th><?= __('Uuid') ?></th>
        	            <th><?= __('Step') ?></th>
        	            <th><?= __('Datas') ?></th>
        	            <th><?= __('Created') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($wizards as $wizard): ?>
	        <tr>
        	            <td><?= h($wizard->uuid) ?></td>
        	            <td><?= $this->Number->format($wizard->step) ?></td>
        	            <td><?= h($wizard->datas) ?></td>
        	            <td><?= h($wizard->created) ?></td>
        	            <td class="actions">
					<?= $this->element('actions',['controller'=>'wizards','action_id'=>$wizard->id]) ?>
	            </td>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#wizards').DataTable();
</script>

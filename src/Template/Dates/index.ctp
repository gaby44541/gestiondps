<div class="dates index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Dates') ?>
		<?= $this->element('buttons',['controller'=>'dates','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="dates">
	    <thead>
	        <tr>
        	            <th><?= __('Module') ?></th>
        	            <th><?= __('Start') ?></th>
        	            <th><?= __('End') ?></th>
        	            <th><?= __('Title') ?></th>
        	            <th><?= __('Informations') ?></th>
        	            <th><?= __('Url') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($dates as $date): ?>
	        <tr>
        	            <td><?= h($date->module) ?></td>
        	            <td><?= h($date->start) ?></td>
        	            <td><?= h($date->end) ?></td>
        	            <td><?= h($date->title) ?></td>
        	            <td><?= h($date->informations) ?></td>
        	            <td><?= h($date->url) ?></td>
					<?= $this->element('actions',['controller'=>'dates','action_id'=>$date->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#dates').DataTable();
</script>

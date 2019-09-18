<div class="personnelsAntennes index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Personnels Antennes') ?>
		<?= $this->element('buttons',['controller'=>'personnelsAntennes','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="personnelsAntennes">
	    <thead>
	        <tr>
        	            <th><?= __('Antenne Id') ?></th>
        	            <th><?= __('Personnel Id') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($personnelsAntennes as $personnelsAntenne): ?>
	        <tr>
        	            <td><?= $personnelsAntenne->has('antenne') ? $this->Html->link($personnelsAntenne->antenne->id, ['controller' => 'Antennes', 'action' => 'view', $personnelsAntenne->antenne->id]) : '' ?></td>
        	            <td><?= $personnelsAntenne->has('personnel') ? $this->Html->link($personnelsAntenne->personnel->id, ['controller' => 'Personnels', 'action' => 'view', $personnelsAntenne->personnel->id]) : '' ?></td>
					<?= $this->element('actions',['controller'=>'personnelsAntennes','action_id'=>$personnelsAntenne->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#personnelsAntennes').DataTable();
</script>

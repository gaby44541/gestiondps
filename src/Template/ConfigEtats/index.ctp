<div class="configEtats index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Config Etats') ?>
		<?= $this->element('buttons',['controller'=>'configEtats','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="configEtats">
	    <thead>
	        <tr>
        	            <th><?= __('Designation') ?></th>
						<th><?= __('Description') ?></th>
        	            <th><?= __('Ordre') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($configEtats as $configEtat): ?>
	        <tr>
        	            <td style="width:30%;">
							<?= $this->Html->badge(count($configEtat->demandes),['class'=>$configEtat->class]) ?>
							&nbsp;
							<?= $this->Html->badge($configEtat->designation,['class'=>$configEtat->class]) ?>
						</td>
        	            <td style="text-align:justify;"><?= nl2br($configEtat->description) ?></td>
						<td><?= $this->Number->format($configEtat->ordre) ?></td>
					<?= $this->element('actions',['controller'=>'configEtats','action_id'=>$configEtat->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    //$('#configEtats').DataTable();
</script>

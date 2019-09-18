<div class="personnelsAntennes view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('PersonnelsAntenne') ?>
		<?= $this->element('buttons',['controller'=>'personnelsAntennes','action_id'=>$personnelsAntenne->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($personnelsAntenne->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Antenne') ?></th>
			<td><?= $personnelsAntenne->has('antenne') ? $this->Html->link($personnelsAntenne->antenne->id, ['controller' => 'Antennes', 'action' => 'view', $personnelsAntenne->antenne->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Personnel') ?></th>
			<td><?= $personnelsAntenne->has('personnel') ? $this->Html->link($personnelsAntenne->personnel->id, ['controller' => 'Personnels', 'action' => 'view', $personnelsAntenne->personnel->id]) : '' ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
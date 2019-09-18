<div class="personnelsEquipes view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('PersonnelsEquipe') ?>
		<?= $this->element('buttons',['controller'=>'personnelsEquipes','action_id'=>$personnelsEquipe->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($personnelsEquipe->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Equipe') ?></th>
			<td><?= $personnelsEquipe->has('equipe') ? $this->Html->link($personnelsEquipe->equipe->id, ['controller' => 'Equipes', 'action' => 'view', $personnelsEquipe->equipe->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Personnel') ?></th>
			<td><?= $personnelsEquipe->has('personnel') ? $this->Html->link($personnelsEquipe->personnel->id, ['controller' => 'Personnels', 'action' => 'view', $personnelsEquipe->personnel->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Chef') ?></th>
			<td><?= $personnelsEquipe->chef ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Oui') ?></th>
			<td><?= $personnelsEquipe->selection ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Non') ?></th>
			<td><?= $personnelsEquipe->disponibilite ? __('Yes') : __('No'); ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
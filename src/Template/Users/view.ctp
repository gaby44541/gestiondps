<div class="users view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('User') ?>
		<?= $this->element('buttons',['controller'=>'users','action_id'=>$user->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($user->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Email') ?></th>
			<td><?= h($user->email) ?></td>
		</tr>
		<tr>
			<th><?= __('Password') ?></th>
			<td><?= h($user->password) ?></td>
		</tr>
		<tr>
			<th><?= __('Nom') ?></th>
			<td><?= h($user->nom) ?></td>
		</tr>
		<tr>
			<th><?= __('Telephone') ?></th>
			<td><?= h($user->telephone) ?></td>
		</tr>
		<tr>
			<th><?= __('Externe') ?></th>
			<td><?= $user->externe ? __('Yes') : __('No'); ?></td>
		</tr>
		<tr>
			<th><?= __('Created') ?></th>
			<td><?= h($user->created) ?></td>
		</tr>
		<tr>
			<th><?= __('Modified') ?></th>
			<td><?= h($user->modified) ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
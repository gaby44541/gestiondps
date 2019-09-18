<div class="users index col-lg-12 col-md-2 content">
	<h1>
		<?= __('Users') ?>
		<?= $this->element('buttons',['controller'=>'users','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="users">
	    <thead>
	        <tr>
        	            <th><?= __('Email') ?></th>
        	            <th><?= __('Nom') ?></th>
        	            <th><?= __('Telephone') ?></th>
        	            <th><?= __('Externe') ?></th>
        	            <th><?= __('Created') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($users as $user): ?>
	        <tr>
        	            <td><?= h($user->username) ?></td>
        	            <td><?= h($user->nom) ?></td>
        	            <td><?= h($user->telephone) ?></td>
        	            <td><?= h($user->externe) ?></td>
        	            <td><?= h($user->created) ?></td>
        	            <td class="actions">
					<?= $this->element('actions',['controller'=>'users','action_id'=>$user->id]) ?>
	            </td>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#users').DataTable();
</script>

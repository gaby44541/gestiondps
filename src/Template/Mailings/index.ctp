<div class="container-fluid">
	<h1>
		<?= __('Mailings') ?>
		<?= $this->element('buttons',['controller'=>'mailings','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="mailings">
	    <thead>
	        <tr>
				<th><?= __('Destinataire') ?></th>
				<th><?= __('Mail') ?></th>
				<th><?= __('Send') ?></th>
				<th><?= __('Read') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($mailings as $mailing): ?>
	        <tr>
				<td><?= h($mailing->destinataire) ?></td>
				<td><?= $mailing->has('mail') ? $this->Html->link($mailing->mail->subject, ['controller' => 'Mails', 'action' => 'view', $mailing->mail->id]) : '' ?></td>
				<td><?= h($mailing->send) ?></td>
				<td><?= h($mailing->read) ?></td>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#mailings').DataTable();
</script>

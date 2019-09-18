<div class="container-fluid">
	<h1>
		<?= __('Mailing') ?>
		<?= $this->element('buttons',['controller'=>'mailings','action_id'=>$mailing->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($mailing->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Uuid') ?></th>
			<td><?= h($mailing->uuid) ?></td>
		</tr>
		<tr>
			<th><?= __('Destinataire') ?></th>
			<td><?= h($mailing->destinataire) ?></td>
		</tr>
		<tr>
			<th><?= __('Send') ?></th>
			<td><?= h($mailing->send) ?></td>
		</tr>
		<tr>
			<th><?= __('Read') ?></th>
			<td><?= h($mailing->read) ?></td>
		</tr>
		<tr>
			<th><?= __('Message') ?></th>
			<td><?= $this->Text->autoParagraph(h($mailing->message)); ?></td>
		</tr>
		<tr>
			<th><?= __('Mail') ?></th>
			<td><?= $mailing->has('mail') ? $this->Html->link($mailing->mail->id, ['controller' => 'Mails', 'action' => 'view', $mailing->mail->id]) : '' ?></td>
		</tr>
	
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	</script>
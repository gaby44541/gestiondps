<div class="container-fluid">
	<h1>
		<?= __('Mail') ?>
		<?= $this->element('buttons',['controller'=>'mails','action_id'=>$mail->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($mail->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Type') ?></th>
			<td><?= h($mail->type) ?></td>
		</tr>
		<tr>
			<th><?= __('Controller') ?></th>
			<td><?= h($mail->controller) ?></td>
		</tr>
		<tr>
			<th><?= __('Action') ?></th>
			<td><?= h($mail->action) ?></td>
		</tr>
		<tr>
			<th><?= __('Subject') ?></th>
			<td><?= h($mail->subject) ?></td>
		</tr>
		<tr>
			<th><?= __('Message') ?></th>
			<td><?= $this->Text->autoParagraph(h($mail->message)); ?></td>
		</tr>
		<tr>
			<th><?= __('Format') ?></th>
			<td><?= h($mail->format) ?></td>
		</tr>
		<tr>
			<th><?= __('Attachments') ?></th>
			<td><?= $this->Text->autoParagraph(h($mail->attachments)); ?></td>
		</tr>
		<tr>
			<th><?= __('Publish') ?></th>
			<td><?= $mail->publish ? __('Yes') : __('No'); ?></td>
		</tr>
	
	</table>
<div class="related">
	<h3>
		<?= __('Mailings') ?>
		<?= $this->element('buttons',['controller'=>'Mailings','options'=>'add']) ?>
	</h3>
<?php if (!empty($mail->mailings)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="Mailings">
		<thead>
			<tr>
				<th><?= __('Destinataire') ?></th>
				<th><?= __('Send') ?></th>
				<th><?= __('Read') ?></th>
				<th><?= __('Message') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($mail->mailings as $mailings): ?>
			<tr>
				<td><?= h($mailings->destinataire) ?></td>
				<td><?= h($mailings->send) ?></td>
				<td><?= h($mailings->read) ?></td>
				<td><?= h($mailings->message) ?></td>
				<td class="actions">
				<?= $this->element('actions',['controller'=>'Mailings','action_id'=>$mailings->id]) ?>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
</div>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	    $('#Mailings').DataTable();
	</script>
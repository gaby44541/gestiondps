<div class="container-fluid">
	<h1>
		<?= __('Mails') ?>
		<?= $this->element('buttons',['controller'=>'mails','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="mails">
	    <thead>
	        <tr>
				<th><?= __('Type') ?></th>
				<th><?= __('Controller') ?></th>
				<th><?= __('Action') ?></th>
				<th><?= __('Subject') ?></th>
				<th><?= __('Message') ?></th>
				<th><?= __('Format') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($mails as $mail): ?>
	        <tr>
				<td><?= h($mail->type) ?></td>
				<td><?= h($mail->controller) ?></td>
				<td><?= h($mail->action) ?></td>
				<td><?= h($mail->subject) ?></td>
				<td><?= nl2br($this->Text->truncate($mail->message,300,[
					'ellipsis' => '...',
					'exact' => false,
					'html' => false
				])) ?></td>
				<td><?= h($mail->format) ?></td>
				<?= $this->element('actions',['controller'=>'mails','action_id'=>$mail->id,'options'=>['view','edit','duplicate','delete']]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#mails').DataTable();
</script>

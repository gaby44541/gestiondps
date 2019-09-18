<div class="users form col-lg-6 col-lg-offset-3">

	<?php echo $this->Panel->create('i:start Connexion', ['type' => 'primary']); ?>
		<?= $this->Flash->render('auth') ?>
		<?= $this->Form->create() ?>
		<fieldset>
			<legend><?= __('Veuillez saisir votre identifiant et votre mot de passe') ?></legend>
			<?= $this->Form->control('username') ?>
			<?= $this->Form->control('password') ?>
		</fieldset>
		<?= $this->Form->button(__('Connexion')); ?>
		<?= $this->Form->end() ?>
	<?php echo $this->Panel->end(); ?>

</div>

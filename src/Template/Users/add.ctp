<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="users form col-lg-6 col-lg-offset-3 content">
	<h1>
	<?= __('Créer un compte utilisateur') ?>
	<?= $this->element('buttons',['controller'=>'users','options'=>'index']) ?>
	</h1>
    <?= $this->Form->create($user, ['horizontal' => true]) ?>
    <fieldset>       
        <?php
            echo $this->Form->control('nom', ['class' => 'form-control','help' => __('Votre nom complet : nom et prénom')]);
            echo $this->Form->control('telephone', ['class' => 'form-control','help' => __('Pour pouvoir vous contacter en cas de difficultés')]);
            echo $this->Form->control('username', ['class' => 'form-control','help' => __('Ce champ doit être impérativement un email')]);
            echo $this->Form->control('password', ['class' => 'form-control','help' => __('Doit contenir des chiffres et des lettres')]);
			echo $this->Form->control('externe', ['class' => 'form-control','help' => __('Type de compte')]);
		?>
    </fieldset>
    <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
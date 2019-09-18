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
	<?= __('Mettre à jour mon compte utilisateur') ?>
	</h1>
	<h4>
	<?= __('Modifier mes informations') ?>
	</h4>
    <?= $this->Form->create($user, ['horizontal' => true]) ?>
    <fieldset>
        <?php
            echo $this->Form->control('nom', ['class' => 'form-control','help' => __('Nom complet : nom et prénom')]);
            echo $this->Form->control('telephone', ['class' => 'form-control','help' => __('Pour pouvoir vous contacter en cas de difficultés')]);
            echo $this->Form->control('username', ['class' => 'form-control','help' => __('Ce champ doit être impérativement un email')]);
        ?>
    </fieldset>
	<h4>
	<?= __('Changer mon mot de passe') ?>
	</h4>
    <?= $this->Form->create($user, ['horizontal' => true]) ?>
    <fieldset>
        <?php
            echo $this->Form->control('nouveau', ['class' => 'form-control','help' => __('Doit contenir des chiffres et des lettres uniquement')]);
			echo $this->Form->control('confirmer', ['class' => 'form-control','help' => __('Doit être identique au champ du dessus')]);
		?>
    </fieldset>
    <?= $this->Form->button(__('Mettre à jour mes informations'),['class' => 'btn btn-large btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>

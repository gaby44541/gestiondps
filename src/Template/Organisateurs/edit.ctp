<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Organisateur $organisateur
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="container-fluid">
	<h1>
	<?= __('Modifier Organisateur') ?>
	<?= $this->element('buttons',['controller'=>'organisateurs','options'=>'index']) ?>
	</h1>
	<?php
	$this->Form->setConfig('columns', [
		'sm' => [
			'label' => 4,
			'input' => 8,
			'error' => 0
		],
		'md' => [
			'label' => 4,
			'input' => 8,
			'error' => 0
		]
	]);
	?>
    <?= $this->Form->create($organisateur, ['horizontal' => true]) ?>
    <fieldset>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Coordonnées juridiques') ?></h3>
        <?php

            echo $this->Form->control('uuid', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('raison_sociale', ['class' => 'form-control','help' => __(''),'options'=>['Association','Entreprise','Collectivité','Particulier']]);
            echo $this->Form->control('nom', ['class' => 'form-control','help' => __('Nom légal de la structure')]);
            echo $this->Form->control('adresse', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('adresse_suite', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('code_postal', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('ville', ['class' => 'form-control','help' => __('')]);
		?>
		<h3><?= __('Représentant légal') ?></h3>
		<?php
            echo $this->Form->control('representant_prenom', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('representant_nom', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('fonction', ['class' => 'form-control','help' => __('')]);

		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Coordonnées téléphopniques') ?></h3>
		<?php
            echo $this->Form->control('telephone', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('portable', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('mail', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
    </fieldset>
    <?= $this->Form->button(__('Soumettre le formulaire'),['class' => 'btn btn-large btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>

<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 0
    });
</script>

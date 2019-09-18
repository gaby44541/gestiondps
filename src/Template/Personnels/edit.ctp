<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Personnel $personnel
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="personnels edit col-lg-12 col-md-12 columns content">
	<h1>
	<?= __('Edit Personnel') ?>
	<?= $this->element('buttons',['controller'=>'personnels','options'=>'index']) ?>
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
    <?= $this->Form->create($personnel, ['horizontal' => true]) ?>
    <fieldset>
        
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('personnel') ?></h3>        
        <?php

            echo $this->Form->control('prenom', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('nom', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('nom_naissance', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('statut', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('rue', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('code_postal', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('ville', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('identifiant', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('portable', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('personnel') ?></h3>  
		<?php            echo $this->Form->control('telephone', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('mail', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('antenne', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('entreprise', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('naissance_date', ['type'=>'datetimepicking','empty' => true,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('naissance_lieu', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('prevenir', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('prevenir_telephone', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('antennes._ids', ['options' => $antennes]);
            echo $this->Form->control('equipes._ids', ['options' => $equipes]);
        ?>
		</div>
    </fieldset>
    <?= $this->Form->button(__('Submit'),['class' => 'btn btn-large btn-primary']) ?>
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

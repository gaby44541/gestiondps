<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Antenne $antenne
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="container-fluid">
	<h1>
	<?= __('Modifier Antenne') ?>
	<?= $this->element('buttons',['controller'=>'antennes','options'=>'index']) ?>
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
    <?= $this->Form->create($antenne, ['horizontal' => true]) ?>
    <fieldset>
        
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Coordonnées') ?></h3>        
        <?php

            echo $this->Form->control('antenne', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('adresse', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('adresse_suite', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('code_postal', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('ville', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('telephone', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('portable', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('mail', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('fax', ['class' => 'form-control','help' => __('')]);          
		?>
		<h3><?= __('Personnes ressources') ?></h3>  
		<?php      
			echo $this->Form->control('technique_nom', ['class' => 'form-control','help' => __('Nom et Prénom')]);
			echo $this->Form->control('technique_mail', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('tresorier_nom', ['class' => 'form-control','help' => __('Nom et Prénom')]);
			echo $this->Form->control('tresorier_mail', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Finances') ?></h3>  
		<?php            
			echo $this->Form->control('rib_etablissemnt', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('rib_guichet', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('rib_compte', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('rib_rice', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('rib_domicile', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('rib_bic', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('rib_iban', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cheque', ['class' => 'form-control','help' => __('')]);
		?>
		<h3><?= __('Activité') ?></h3>  
		<?php    
            echo $this->Form->control('etat', ['options'=>['ACTIF'=>'Active','INACTIF'=>'Non active'],'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('personnels._ids', ['options' => $personnels]);
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

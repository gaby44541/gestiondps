<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Demande $demande
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="demandes edit content">
	<h1>
	<?= __('Modifier Demande') ?>
	<?= $this->element('buttons',['controller'=>'demandes','options'=>'index']) ?>
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
    <?= $this->Form->create($demande, ['horizontal' => true]) ?>
    <fieldset>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Manifestation') ?></h3>        
        <?php
            echo $this->Form->control('manifestation', ['class' => 'form-control','help' => __('Nom de la manifestation')]);
            echo $this->Form->control('organisateur_id', ['options' => $organisateurs,'class' => 'form-control','empty' => false ,'help' => __('')]);
		?>
		<h3><?= __('Représentant sur place') ?></h3>  		
		<?php
            echo $this->Form->control('representant', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('representant_fonction', ['class' => 'form-control','help' => __('')]);
		?>
		<h3><?= __('Affectation') ?></h3>  		
		<?php
            echo $this->Form->control('antenne_id', ['options' => $antennes,'class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">

		<h3><?= __('Protection Civile') ?></h3>  
		<?php            
			echo $this->Form->control('gestionnaire_nom', ['class' => 'form-control','help' => __('Directeur Opérationnel en charge du dossier')]);
            echo $this->Form->control('gestionnaire_mail', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('gestionnaire_telephone', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('config_etat_id', ['options' => $configEtats,'class' => 'form-control','help' => __('')]);
		?> 		
		<?php
            //echo __('Financement consentis');
			//echo $this->Form->control('remise_justification', ['class' => 'form-control','help' => __('')]);
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
        showMeridian: 0,
		format:'yyyy-mm-dd hh:ii'
    });
</script>

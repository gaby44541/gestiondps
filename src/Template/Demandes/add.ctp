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
	<?= __('Créer une demande') ?>
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
		?>
		<h3><?= __('Organisateur') ?></h3>  		
		<?php
			echo $this->Form->control('organisateur_id', ['options' => $organisateurs,'class' => 'form-control','empty' => false ,'help' => __('')]);
            echo $this->Form->control('representant', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('representant_fonction', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">

		<h3><?= __('Gestion du dossier') ?></h3>  
		<?php            
            echo $this->Form->control('antenne_id', ['options' => $antennes,'class' => 'form-control','help' => __('')]);
			echo $this->Form->control('gestionnaire_nom', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('gestionnaire_mail', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('gestionnaire_telephone', ['class' => 'form-control','help' => __('')]);
            //echo $this->Form->control('config_etat_id', ['options' => $configEtats,'class' => 'form-control','help' => __('')]);
		?>
		<?= $this->Form->button(__('Soumettre le formulaire'),['class' => 'btn btn-large btn-primary']) ?>
		</div>
    </fieldset>
    
    <?= $this->Form->end() ?>
</div>

<?= $this->Html->script('plugins/chosen/chosen.jquery'); ?>
<?= $this->Html->css('chosen'); ?>

<script type="text/javascript">
    $('#organisateur-id').chosen();
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

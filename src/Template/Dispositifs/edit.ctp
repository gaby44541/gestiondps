<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dispositif $dispositif
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="dispositifs edit content">
	<h1>
	<?= __('Modifier Dispositif') ?>
	<?= $this->element('buttons',['controller'=>'dispositifs','options'=>'index']) ?>
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
    <?= $this->Form->create($dispositif, ['horizontal' => true]) ?>
    <fieldset>
        
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Général') ?></h3>        
        <?php
            echo $this->Form->control('dimensionnement_id', ['options' => $dimensionnements,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('title', ['class' => 'form-control','help' => __('Numéro d\'accord du poste : '.$dispositif->accord_siege)]);
		?>
		<h3><?= __('Organisation pour le public') ?></h3>  
		<?php
            echo $this->Form->control('config_typepublic_id', ['options' => $configTypepublics,'class' => 'form-control','empty' => true ,'help' => __('')]);
            echo $this->Form->control('config_environnement_id', ['options' => $configEnvironnements,'class' => 'form-control','empty' => true ,'help' => __('')]);
            echo $this->Form->control('config_delai_id', ['options' => $configDelais,'class' => 'form-control','empty' => true ,'help' => __('')]);
            echo $this->Form->control('ris', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('recommandation_type', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('recommandation_poste', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('personnels_public', ['class' => 'form-control','help' => __('Le RIS étant de : '.$dispositif->ris.', la recommandation est un dispositif de type '.$dispositif->recommandation_type)]);
            echo $this->Form->control('organisation_public', ['class' => 'form-control','help' => nl2br($dispositif->recommandation_poste)]);
		?>
		<h3><?= __('Organisation pour les acteurs') ?></h3>  
		<?php 
            echo $this->Form->control('personnels_acteurs', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('organisation_acteurs', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Organisation générale') ?></h3>  
		<?php
            echo $this->Form->control('personnels_total', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('organisation_poste', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('organisation_transport', ['class' => 'form-control','help' => __('Laisser vide pour appliquer les consignes par défaut.')]);
		?>
		<h3><?= __('Divers') ?></h3>  
		<?php
            echo $this->Form->control('consignes_generales', ['class' => 'form-control','help' => __('Consignes générales à destination des équipiers qui seront sur ce dispositif. Laisser vide pour appliquer les consignes par défaut.')]);
            echo $this->Form->control('accord_siege', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            //echo $this->Form->control('remise', ['class' => 'form-control','help' => __('En pourcentage, exemple : écrire 10 pour consentir d\'une remise de 10%')]);
        ?>
		<h3><?= __('Enregistrement') ?></h3>
		<?php $this->Form->horizontal = true; ?>
		<?= $this->Form->input('actions',[
			'options'=>['Recalculer','Sortir'],
			'type'=>'select',
			'append' => [ $this->Form->button(__('Soumettre le formulaire'),['class' => 'btn btn-large btn-primary']) ]
		]) ?>
		</div>
    </fieldset>
    <?= $this->Form->end() ?>
</div>

<script type="text/javascript">
	$('#dimenstionnement-id').chosen();
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

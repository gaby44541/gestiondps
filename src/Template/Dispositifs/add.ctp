<?php
/**
 * N'est pas utilisé pour le moment.
 * L'ajout de dispositif se fait directement à partir du dimensionnement.
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
	<?= __('Ajouter une  Dispositif') ?>
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
		<h3><?= __('Demande associée') ?></h3>
        <?php
            echo $this->Form->control('dimensionnement_id', ['options' => $dimensionnements,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('title', ['class' => 'form-control','help' => __('')]);
		?>
		<h3><?= __('Pré-saisie public') ?></h3>
        <?php
            echo $this->Form->control('config_typepublic_id', ['options' => $configTypepublics,'class' => 'form-control','empty' => true ,'help' => __('')]);
            echo $this->Form->control('config_environnement_id', ['options' => $configEnvironnements,'class' => 'form-control','empty' => true ,'help' => __('')]);
            echo $this->Form->control('config_delai_id', ['options' => $configDelais,'class' => 'form-control','empty' => true ,'help' => __('')]);
            // echo $this->Form->control('ris', ['class' => 'form-control','help' => __('')]);
            // echo $this->Form->control('recommandation_type', ['class' => 'form-control','help' => __('')]);
            // echo $this->Form->control('recommandation_poste', ['class' => 'form-control','help' => __('')]);
            // echo $this->Form->control('personnels_public', ['class' => 'form-control','help' => __('')]);
            // echo $this->Form->control('organisation_public', ['class' => 'form-control','help' => __('')]);
            // echo $this->Form->control('personnels_acteurs', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Configuration générale') ?></h3>
		<?php
			// echo $this->Form->control('organisation_acteurs', ['class' => 'form-control','help' => __('')]);
            // echo $this->Form->control('personnels_total', ['class' => 'form-control','help' => __('')]);
            //echo $this->Form->control('organisation_poste', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('organisation_transport', ['class' => 'form-control','help' => __('Laisser vide pour appliquer les consignes par défaut.')]);
            //echo $this->Form->control('consignes_generales', ['class' => 'form-control','help' => __('')]);
            //echo $this->Form->control('accord_siege', ['class' => 'form-control','help' => __('')]);
        ?>
		</div>
    </fieldset>
    <?= $this->Form->button(__('Soumettre le formulaire'),['class' => 'btn btn-large btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>

<script type="text/javascript">
    // $('.datetimepicker').datetimepicker({
        // language:  'fr',
        // weekStart: 1,
        // todayBtn:  1,
		// autoclose: 1,
		// todayHighlight: 1,
		// startView: 2,
		// forceParse: 0,
        // showMeridian: 0,
		// format:'yyyy-mm-dd hh:ii'
    // });
</script>

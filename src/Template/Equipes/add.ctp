<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Equipe $equipe
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="equipes edit columns content">
	<h1>
	<?= __('Ajouter une  Equipe') ?>
	<?= $this->element('buttons',['controller'=>'equipes','options'=>'index']) ?>
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
    <?= $this->Form->create($equipe, ['horizontal' => true]) ?>
    <fieldset>
        
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Equipe') ?></h3>        
        <?php
            echo $this->Form->control('dispositif_id', ['options' => $dispositifs,'class' => 'form-control','help' => __('')]);
		?>
		<h3><?= __('Configuration') ?></h3> 		
		<?php
            echo $this->Form->control('indicatif', ['class' => 'form-control','help' => __('Exemple : Equipe 1, RAB1, ...')]);
            echo $this->Form->control('effectif', ['class' => 'form-control','help' => __('Minimum 2 pour un binôme, maximum 4 pour une équipe')]);
		?>
		<h3><?= __('Véhicules') ?></h3> 		
		<?php
            echo $this->Form->control('vehicule_type', ['class' => 'form-control','help' => __('Exemple : 1 VPSP, 1 4x4, 1 VTP, 2 VL')]);
            echo $this->Form->control('vehicules_km', ['class' => 'form-control','help' => __('Prévoir les km au plus défavorable (antenne la plus loin)')]);
            echo $this->Form->control('vehicule_trajets', ['class' => 'form-control','help' => __('Prévoir autant de trajets que de véhicules')]);
		?>
		<h3><?= __('Lot de matériel') ?></h3> 		
		<?php
            echo $this->Form->control('lot_a', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('lot_b', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('lot_c', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('autre', ['class' => 'form-control','help' => __('Exemple : 2 tentes, ...')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Consignes à l\'équipe') ?></h3> 		
		<?php
            echo $this->Form->control('consignes', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('position', ['class' => 'form-control','help' => __('Position sur la manifestation : adresse, jalon, km, ...')]);
            echo $this->Form->control('horaires_convocation', ['type'=>'datetimepicking','empty' => true,'class' => 'form-control','help' => __('En général la durée du trajet, avec une marge de sécurité de 15 à 30min avant l\'heure de mise en place')]);
            echo $this->Form->control('horaires_place', ['type'=>'datetimepicking','empty' => true,'class' => 'form-control','help' => __('En général 1h avant le début du poste')]);
            echo $this->Form->control('horaires_fin', ['type'=>'datetimepicking','empty' => true,'class' => 'form-control','help' => __('')]);
			echo $this->Form->control('horaires_retour', ['type'=>'datetimepicking','empty' => true,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('duree', ['class' => 'form-control','help' => __(''),'disabled']);
		?>
		<h3><?= __('Repas à prévoir sur l\'amplitude horaire') ?></h3> 		
		<?php
            echo $this->Form->control('repas_charge', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('repas_matin', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('repas_midi', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('repas_soir', ['class' => 'form-control','help' => __('')]);
            //echo $this->Form->control('personnels._ids', ['options' => $personnels]);
		?>
		<h3><?= __('Mémo pour les années futures') ?></h3> 		
		<?php			
			echo $this->Form->control('remarques', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('remise', ['class' => 'form-control','help' => __('En pourcentage')]);
        ?>
		<h3><?= __('Calculs automatiques') ?></h3>  
		<?php
            echo $this->Form->control('cout_personnel', ['class' => 'form-control','help' => __(''),'disabled']);
            echo $this->Form->control('cout_kilometres', ['class' => 'form-control','help' => __(''),'disabled']);
            echo $this->Form->control('cout_repas', ['class' => 'form-control','help' => __(''),'disabled']);
            echo $this->Form->control('cout_remise', ['class' => 'form-control','help' => __(''),'disabled']);
			echo $this->Form->control('cout_economie', ['class' => 'form-control','help' => __(''),'disabled']);
            echo $this->Form->control('repartition_antenne', ['class' => 'form-control','help' => __(''),'disabled']);
            echo $this->Form->control('repartition_adpc', ['class' => 'form-control','help' => __(''),'disabled']);
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

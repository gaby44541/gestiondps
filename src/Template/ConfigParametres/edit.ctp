<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConfigParametre $configParametre
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="configParametres edit col-lg-12 col-md-12 columns content">
	<h1>
	<?= __('Edition des paramètres') ?>
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
    <?= $this->Form->create($configParametre, ['horizontal' => true]) ?>
    <fieldset>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Véhicules') ?></h3>
		<?php
		    echo $this->Form->control('cout_vpsp', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_vtu', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_vtp', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_quad', ['class' => 'form-control','help' => __('')]);
        ?>
		</div>

        <div class="col-lg-6 col-md-12 columns">
        <h3><?= __('Personnel') ?></h3>
        <p><i>Sur une base de 8h</i></p>
        <?php
            echo $this->Form->control('cout_pse2', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_pse1', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_lat', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_medecin', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_infirmier', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_ce_cp', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_stagiaire', ['class' => 'form-control','help' => __('')]);
        ?>
        </div>
        <div class="col-lg-6 col-md-12 columns">
        <h3><?= __('Matériel') ?></h3>
        <?php
            echo $this->Form->control('cout_lot_a', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_lot_b', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_lot_c', ['class' => 'form-control','help' => __('')]);
        ?>
        </div>
        <div class="col-lg-6 col-md-12 columns">
        <h3><?= __('Divers') ?></h3>
        <?php
            echo $this->Form->control('cout_hebergement', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_kilometres', ['class' => 'form-control','help' => __('Prix d\'1 kilomètre')]);
            echo $this->Form->control('cout_repas', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_portatif', ['class' => 'form-control','help' => __('Licence inclue'),'step'=>'0.01', min=>'0','lang'=>'en']);
        ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'),['class' => 'btn btn-large btn-primary']) ?>
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

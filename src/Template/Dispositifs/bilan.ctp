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
	<?= __('Edit Dispositif') ?>
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
		<h3><?= __('dispositif') ?></h3>        
        <?php
            echo $this->Form->control('assiste', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('leger', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('medicalise', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('evacue', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('dispositif') ?></h3>  
		<?php
            echo $this->Form->control('rapport', ['class' => 'form-control','help' => __('')]);
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
        showMeridian: 0,
		format:'yyyy-mm-dd hh:ii'
    });
</script>

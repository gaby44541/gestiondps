<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Date $date
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="dates edit col-lg-12 col-md-12 columns content">
	<h1>
	<?= __('Add Date') ?>
	<?= $this->element('buttons',['controller'=>'dates','options'=>'index']) ?>
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
    <?= $this->Form->create($date, ['horizontal' => true]) ?>
    <fieldset>
        
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('date') ?></h3>        
        <?php

            echo $this->Form->control('module', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('start', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('end', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('title', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('informations', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('date') ?></h3>  
		<?php            echo $this->Form->control('url', ['class' => 'form-control','help' => __('')]);
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

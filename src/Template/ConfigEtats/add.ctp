<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ConfigEtat $configEtat
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="configEtats edit col-lg-12 col-md-12 columns content">
	<h1>
	<?= __('Add Config Etat') ?>
	<?= $this->element('buttons',['controller'=>'configEtats','options'=>'index']) ?>
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
    <?= $this->Form->create($configEtat, ['horizontal' => true]) ?>
    <fieldset>
        
		<div class="col-lg-6 col-md-12 columns">      
        <?php

            echo $this->Form->control('designation', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('ordre', ['class' => 'form-control','help' => __('')]);
			echo $this->Form->control('class', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns"> 
		<?php
			echo $this->Form->control('description', ['class' => 'form-control','help' => __('')]);
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

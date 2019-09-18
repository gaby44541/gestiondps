<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Menu $menu
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="container-fluid">
	<h1>
	<?= __('Edit Menu') ?>
	<?= $this->element('buttons',['controller'=>'menus','options'=>'index']) ?>
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
    <?= $this->Form->create($menu, ['horizontal' => true]) ?>
    <fieldset>
        
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('menu') ?></h3>        
        <?php

            echo $this->Form->control('parent_id', ['options' => $parentMenus,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('level', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('icone', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('intitule', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('controller', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('menu') ?></h3>  
		<?php            echo $this->Form->control('actions', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('params', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('home', ['class' => 'form-control','help' => __('')]);
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

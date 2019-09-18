<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mail $mail
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="container-fluid">
	<h1>
	<?= __('Edit Mail') ?>
	<?= $this->element('buttons',['controller'=>'mails','options'=>'index']) ?>
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
    <?= $this->Form->create($mail, ['horizontal' => true]) ?>
    <fieldset>
        
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('mail') ?></h3>        
        <?php
			echo $this->Form->control('subject', ['class' => 'form-control','help' => __('Sujet du mail')]);
            echo $this->Form->control('type', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('controller', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('action', ['class' => 'form-control','help' => __('')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('mail') ?></h3>  
		<?php            
			echo $this->Form->control('format', ['options'=>['text'=>'text','html'=>'html','both'=>'both'],'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('attachments', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('publish', ['class' => 'form-control','help' => __('')]);
        ?>
		</div>
		<div class="col-lg-12 col-md-12 columns">
		<h3><?= __('Contenu du message') ?></h3>  
		<?php
			echo $this->Form->horizontal = false;
			echo $this->Form->control('message', ['label'=>false,'rows'=>30,'class' => 'form-control','help' => __('Accepte les balises HTML')]);
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

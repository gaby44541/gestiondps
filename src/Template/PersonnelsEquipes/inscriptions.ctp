<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PersonnelsEquipe $personnelsEquipe
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="personnelsEquipes edit col-lg-12 col-md-12 columns content">
	<h1>
	<?= __('Inscription sur des missions') ?>
	<?= $this->element('buttons',['controller'=>'personnelsEquipes','options'=>'index']) ?>
	</h1>
	<?= $this->Form->create($personnelsEquipes) ?>
	<?php foreach($inscriptions as $inscription){ ?>
	<div class="row-fluid">
		<div class="row" style="border-top:1px solid #CCE5FF; padding:10px;">
			<div class="col-lg-6">
				<?= str_replace(':00 UTC','',$inscription->du_au) ?>
			</div>
			<div class="col-lg-2">
				<?= $this->Html->icon('user') ?>
				<?= $inscription->effectif_groupby . __(' équipiers') ?>
			</div>
			<div class="col-lg-4">
				<?= $this->Form->inlineRadio('PersonnelsEquipes['.$inscription->id.'][disponibilite]', [1=>'Incertain',2=>'Disponible',3=>'Indisponible'], ['help' => __('')])?>
				<?= $this->Form->hidden('PersonnelsEquipes['.$inscription->id.'][equipe_id]',['value'=>0])?>
				<?= $this->Form->hidden('PersonnelsEquipes['.$inscription->id.'][chef]',['value'=>0])?>
				<?= $this->Form->hidden('PersonnelsEquipes['.$inscription->id.'][personnel_id]',['value'=>0])?>
				<?= $this->Form->hidden('PersonnelsEquipes['.$inscription->id.'][selection]',['value'=>$inscription->strtotime_distinct])?>
			
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="row-fluid">
		<div class="col-lg-12"><?= $this->Form->submit(__('Enregistrer mes disponibilités'),['class'=>'btn btn-primary btn-block']) ?></div>
	</div>
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

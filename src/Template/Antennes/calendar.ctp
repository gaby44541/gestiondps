<?php
$this->prepend( 'script' , $this->Html->script('calendar') );
$this->prepend( 'script' , $this->Html->script('plugins/fullcalendar/locale/fr') );
$this->prepend( 'script' , $this->Html->script('plugins/fullcalendar/scheduler') );
$this->prepend( 'script' , $this->Html->script('plugins/fullcalendar/fullcalendar.min') );
$this->prepend( 'script' , $this->Html->script('plugins/fullcalendar/moment.min') );
$this->prepend( 'script' , $this->Html->script('jquery-ui.min') );
$this->prepend( 'css' , $this->Html->css('scheduler') );
$this->prepend( 'css' , $this->Html->css('fullcalendar') );
$this->prepend( 'css' , $this->Html->css('fullcalendar.print',['media'=>'print']) );

?>
<div class="container-fluid">
	<div class="row">
		<?php
		$this->Breadcrumbs->add('Créer un dossier en mode express', ['controller' => 'organisateurs','action'=>'wizard']);
		$this->Breadcrumbs->add('Basculer en vue :');
		$this->Breadcrumbs->add('Liste', ['controller' => 'demandes','action'=>'index']);
		$this->Breadcrumbs->add('Calendrier');
		$this->Breadcrumbs->add('Tableau de bord',['controller' => 'pages','action'=>'display','accueil']);

		echo $this->Breadcrumbs->render(
			['class' => 'breadcrumb'],
			[]
		);
		?>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div id="calendar" data-url1="<?= $this->Url->build(['controller'=>'demandes','action'=>'ajax']) ?>" data-url2="<?= $this->Url->build(['controller'=>'demandes','action'=>'ajax']) ?>">
		</div>
	</div>
</div>

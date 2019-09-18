<div class="navbar navbar-default navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a href="<?php echo $this->Url->build('/'); ?>" class="navbar-brand">Opérationnel</a>
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse" id="navbar-main">
			<?php $this->CellTree->map( 'Menus' , 8 ); ?>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?php echo $this->Url->build('/'); ?>organisateurs/wizard/"><i class="glyphicon glyphicon-flash"></i>&nbsp;Mode express</a></li>
			</ul>
		</div>
	</div>
</div>
<br />
<nav class="navbar navbar-inverse">
	<div class="container">
		<div class="navbar-header">
			<a href="<?php echo $this->Url->build('/'); ?>" class="navbar-brand"><i class="glyphicon glyphicon-flash"></i>&nbsp;Mode dossier</a>
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-wizard">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse" id="navbar-wizard">
			<ul class="nav navbar-nav" role="menu">
			<?php $force = (array) $wizard[$wizard_step]['url']; ?>
			<?php $previous = (array) $wizard[$wizard_step]['url']; ?>
			<?php $previous[0] = 'previous'; ?>
			<?php $next = (array) $wizard[$wizard_step]['url']; ?>
			<?php $next[0] = 'next'; ?>
				<?php foreach( $wizard as $key => $wiz ){ ?>
				<?php if($wizard_step>1 && $wizard_step == $key){ ?>
				<li><a href="<?php echo $this->Url->build($previous); ?>"><i class="glyphicon glyphicon-triangle-left"></i>&nbsp;</a></li>
				<?php } ?>
				<?php if($key <= $wizard_limit && $wiz['navigate'] ){ ?>
				<?php $force[0] = $key; ?>
				<li class="nav-item <?php if( $wizard_step == $key ) { ?>active<?php } ?>">
					<a href="<?php echo $this->Url->build($force); ?>"><?= $wiz['label'] ?></a>
				</li>
				<?php } else { ?>
				<li class="nav-item">
					<a><?= $wiz['label'] ?></a>
				</li>
				<?php } ?>
				<?php if($wizard_step == $key){ ?>
				<li><a href="<?php echo $this->Url->build($next); ?>"><i class="glyphicon glyphicon-triangle-right"></i>&nbsp;</a></li>
				<?php } ?>
			<?php } ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?php echo $this->Url->build('/'); ?>organisateurs/wizard/quit"><i class="glyphicon glyphicon-log-out"></i>&nbsp;Quitter</a></li>
			</ul>
		</div>
	</div>
</nav>
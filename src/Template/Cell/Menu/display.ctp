<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="<?= $this->Url->image('logo.png') ?>"></a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
			<?php $this->CellTree->map( $menus ); ?>	
            <ul class="nav navbar-nav navbar-right">
<?php /*?>                <form class="navbar-form navbar-left" role="search" id="searchbox">
                    <div class="input-group" >
                        <input  type="text" class="form-control searchbar" placeholder="Personnel, caserne, ...">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                    </div>
                </form>
<?php */?>
                <?php //$cell = $this->cell('Notifications');
                //echo $cell; ?>


<?php /*?>                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-envelope"></span></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= $this->Url->build(['controller' => 'Messages','action' => 'send' ]); ?>"><span class="glyphicon glyphicon-pencil"></span> Ecrire un Mail</a></li>
                        <li><a href="<?= $this->Url->build(['controller' => 'Messages','action' => 'index' ]); ?>"><span class="glyphicon glyphicon-inbox"></span> Reception </a></li>
                        <li><a href="<?= $this->Url->build(['controller' => 'Messages','action' => 'dispatch' ]); ?>"><span class="glyphicon glyphicon-send"></span> Envoyé</a></li>
                        <li class="divider"></li>
                        <li><a href="#"><span class="glyphicon glyphicon-warning-sign"></span> Archivé</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-trash"></span> Corbeille</a></li>
                    </ul>
                </li><?php */?>

                <li class="dropdown">
                    <?php echo $this->cell('Login'); ?>
                </li>
            </ul>
		</div>
	</div>
</nav>
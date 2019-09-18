<?php
$etat = isset($etat) ? $etat : 1;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Hash;
use Cake\I18n\FrozenTime;

$configs = TableRegistry::get('ConfigEtats');
$configs = $configs->find('all')->order(['ordre']);
	
?>
	<small>
		<?= __('Vous trouverez ci-dessous l\'état d\'avancement du dossier en consultation et les actions à mener en fonction de chaque étape :') ?>
	</small>
	</div>
	<ul class="list-group">
		<?php 
		foreach($configs as $config){ 
			if($config->ordre < $etat ){
		?>
		<a class="list-group-item disabled" href="#"><small><?= $this->Html->icon('ok').' '.$config->designation ?></small></a>
		<?php 
			} elseif( $config->ordre > $etat) {
		?>
		<a class="list-group-item disabled" href="#"><small><?= $config->designation ?></small></a>	
		<?php 
			} else { 
		?>
		<a class="list-group-item" href="#"><small><?= $config->designation ?></small></a>		
		<?php 
			}
		}
		?>
	</ul>
</div>
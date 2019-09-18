<div class="container-fluid">
	<h1>
		<?= __('Dispositifs') ?>
		<?php if( ! empty($dimensionnements) ){?>
		<?= $this->element('dropdown',[	'controller'=>'dispositifs',
										'label' => $this->Html->icon('plus').'&nbsp;'.__('Traiter une déclaration').'&nbsp;'.$this->Html->badge('Reste : '.count($dimensionnements)),
										'action'=>'add',
										'actions'=>$dimensionnements]) ?>
		<?= $this->element('buttons',[	'controller'=>'demandes',
									'text'=>true,
									'space'=>' ',
									'link'=>false,
									'options'=>['divers'],
									'merge'=>[
												'divers'=>[
															'url'  =>['controller'=>'dispositifs','action'=>'generate',$demande_id,0],
															'attr' =>['class'=>'btn btn-inverse btn-default btn-sm','title'=>__('Crée tous les dispositifs par défaut en une seule action'),'data-toggle'=>'tooltip','escape'=>false],
															'label'=>['icon'=>'flash','text'=>__('Création rapide de tous les dispositifs')]
														]
												]
									]); ?>
		<?php } ?>
		<?= $this->element('buttons',[	'controller'=>'demandes',
									'text'=>true,
									'space'=>' ',
									'link'=>false,
									//'action_id'=>$line->id,
									'options'=>['divers'],
									'merge'=>[
												'divers'=>[
															'url'  =>['controller'=>'dispositifs','action'=>'generate',$demande_id,1],
															'attr' =>['class'=>'btn btn-inverse btn-default btn-sm','title'=>__('Supprime les dispositifs existants et recrée les dispositif par défaut'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous réellement réinitialiser les dispositifs et perdre les modifications antérieurs, y compris les équipes associées ? # {0}', $demande_id)],
															'label'=>['icon'=>'flash','text'=>__('Réinitialiser les dispositifs')]
														]
												]
									]); ?>
	</h1>

	<table class="table table-hover table-striped" id="dispositifs">
	    <thead>
	        <tr>
        	            <th><?= __('Title') ?></th>
        	            <th><?= __('Personnels') ?></th>
						<th><?= __('Organisation générale') ?></th>
        	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($dispositifs as $dispositif): ?>
	        <tr>

        	            <td>
							<?= h($dispositif->title) ?><br/>
							<?= $dispositif->has('dimensionnement') ? $this->Html->link($dispositif->dimensionnement->intitule, ['controller' => 'Dimensionnements', 'action' => 'view', $dispositif->dimensionnement->id]) : '' ?>
						</td>
        	            <td>Total : <?= h($dispositif->personnels_total) ?><br/>
							<?= $this->Html->badge('RIS '.h($dispositif->ris),['class'=>'btn-primary'])?>
							<?= $this->Html->badge(h($dispositif->personnels_public).' P',['class'=>'btn-primary'])?>
							<?= $this->Html->badge(h($dispositif->personnels_acteurs).' A',['class'=>'btn-warning'])?>
						</td>
        	            <td><?= nl2br($dispositif->organisation_poste) ?></td>
					<?= $this->element('actions',['controller'=>'dispositifs','action_id'=>$dispositif->id]) ?>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#dispositifs').DataTable();
</script>

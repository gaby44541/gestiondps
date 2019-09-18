<div class="container-fluid">

	<div class="row">
		<div class="col-lg-7">
			<?php
			$this->Breadcrumbs->add('Créer un dossier en mode express', ['controller' => 'organisateurs','action'=>'wizard']);
			$this->Breadcrumbs->add('Basculer en vue :');
			$this->Breadcrumbs->add('Liste');
			$this->Breadcrumbs->add('Calendrier', ['controller' => 'antennes','action'=>'calendar']);
			$this->Breadcrumbs->add('Tableau de bord',['controller' => 'pages','action'=>'display','accueil']);

			echo $this->Breadcrumbs->render(
				['class' => 'breadcrumb'],
				[]
			); 
			?>	
		</div>
		<div class="col-lg-5">
			<?= $this->element('dropdown',[	'controller'=>'demandes',
											'pull'=>'pull-right',
											'style'=>'margin-left:10px;',
											'label' => $this->Html->icon('plus').'&nbsp;'.__('Filtrer par état').'&nbsp;'.$this->Html->badge( isset($etats[$etat]) ?$etats[$etat]:'' ),
											'action'=>'index',
											'actions'=>$etats]) ?>
											
			<?= $this->element('buttons',[	'controller'=>'demandes',
											'pull'=>'pull-right',
											//'class'=>'btn btn-success btn-sm',
											'options'=>['add','index']]) ?>

		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">								
	<table class="table table-hover table-striped" id="demandes">
	    <thead>
	        <tr>
				<th><?= $this->Html->icon('th-list') ?></th>
				<th><?= $this->Html->icon('alert') ?></th>
				<th><?= __('Manifestation') ?></th>
				<th class="visible-md visible-lg"><?= __('Dates et horaires') ?></th>
				<th><?= __('Dossier') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($demandes as $demande): ?>
	        <tr>
				<td>
					<?= $demande->has('config_etat') ? $this->Html->link($this->Html->badge($demande->config_etat->ordre,['title'=>__($demande->config_etat->designation),'data-toggle'=>'tooltip','class'=>$demande->config_etat->class]), ['controller' => 'ConfigEtats', 'action' => 'view', $demande->config_etat->id],['escape'=>false]) : '' ?>
				</td>
				<td>
					<?php
						switch($demande->alerts){
							case 0:
							case 1:
							case 2:
								echo $this->Html->badge($demande->alerts,['title'=>__('Priorité absolue'),'data-toggle'=>'tooltip','class'=>'btn-danger']);
								break;
							case 3:
								echo $this->Html->badge($demande->alerts,['title'=>__('Priorité medium'),'data-toggle'=>'tooltip','class'=>'btn-warning']);
								break;
							case 13:
								echo $this->Html->badge($demande->alerts,['title'=>__('Dépassé et encore actif'),'data-toggle'=>'tooltip','class'=>'btn-primary']);
								break;
							default:
								echo $this->Html->badge($demande->alerts,['title'=>__('Non prioritaire'),'data-toggle'=>'tooltip','class'=>'btn-success']);
						}
					?>
				</td>
				<td>
					<?= h($demande->manifestation) ?><br />
					<small><?= $demande->has('organisateur') ? $this->Html->link($demande->organisateur->nom, ['controller' => 'Organisateurs', 'action' => 'view', $demande->organisateur->id]) : '' ?></small><br />
				</td>
				<td class="visible-md visible-lg">
				<div class="collapsedf" id="collapseExample">
					<?php foreach( $demande->dimensionnements as $dimensionnement ): ?>
						<div style="margin-left :20px;" class="hide_show">
							<small><?= 'du '.$dimensionnement->horaires_debut.' au '.$dimensionnement->horaires_fin.' : '.$dimensionnement->intitule; ?>
							<?php // $this->element('buttons',['controller'=>'dimensionnements','text'=>false,'options'=>['edit','view'],'space'=>' ','action_id'=>$dimensionnement->id,'merge'=>['edit'=>['attr'=>['title'=>__('Editer'),'data-toggle'=>'tooltip','escape'=>false]],'view'=>['attr'=>['title'=>__('Voir'),'data-toggle'=>'tooltip','escape'=>false]]]]) ?>
							</small>
						</div>
					<?php endforeach; ?>
					</div>
				
				</td>
				<td class="actions" style="width:15%;">
					<?php 
					$actions = [
						['li'=>'header','label'=>'Dossier :'],
						['icon'=>'flash','label'=>'Modifier le dossier','url'=>['controller'=>'demandes','action'=>'wizard',3,'demandes__'.$demande->id]],
						['icon'=>'eye-open','label'=>'Voir le dossier','url'=>['controller'=>'demandes','action'=>'view',$demande->id]],
						['li'=>'header','label'=>'Demande :'],
						['icon'=>'pencil','label'=>'Modifier la demande','url'=>['controller'=>'demandes','action'=>'dispatch',$demande->id]],
						['li'=>'postlink','icon'=>'trash','label'=>'Supprimer','url'=>['controller'=>'demandes','action'=>'view',$demande->id],'attr'=>['title'=>__('Supprimer'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Are you sure you want to delete # {0}?', $demande->id)]],
						['li'=>'divider'],
						['li'=>'header','label'=>'Documents disponibles :']
					];
					if($demande->config_etat->ordre >= 3){ 
					$actions = array_merge( $actions, [
						['icon'=>'print','label'=>'Etude','url'=>['controller'=>'demandes','action'=>'etude',$demande->id]],
						['icon'=>'print','label'=>'Convention','url'=>['controller'=>'demandes','action'=>'convention',$demande->id]],
						['icon'=>'print','label'=>'Mission','url'=>['controller'=>'demandes','action'=>'mission',$demande->id]],
						['icon'=>'print','label'=>'Facture','url'=>['controller'=>'demandes','action'=>'facture',$demande->id]],
						['icon'=>'print','label'=>'Planning','url'=>['controller'=>'demandes','action'=>'planning',$demande->id]]
					]);
					}
					?>
					<?= $this->element('drop',['align'=>'dropdown-menu-right','label'=>'Traiter ce dossier','icon'=>'list','actions' => $actions]) ?>
				</td>				
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
	</div>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#demandes').DataTable();
</script>

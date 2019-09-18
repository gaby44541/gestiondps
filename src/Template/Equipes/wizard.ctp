<div class="equipes wizard content">
	<h1>
		<?= __('Equipes') ?>
		<?= $this->element('dropdown',[	'controller'=>'equipes',
										'label' => $this->Html->icon('plus').'&nbsp;'.__('Ajouter une équipe sur un dispositif').'&nbsp;',
										'option'=>'add',
										'actions'=>$dispositifs]) ?>
		<?= $this->element('buttons',[	'controller'=>'demandes',
									'text'=>true,
									'space'=>' ',
									'link'=>false,
									//'action_id'=>$demande_id,
									'options'=>['divers'],
									'merge'=>[
												'divers'=>[
															'url'  =>['controller'=>'equipes','action'=>'generate',$demande_id,0],
															'attr' =>['class'=>'btn btn-inverse btn-default btn-sm','title'=>__('Crée toutes les équipes par défaut en une seule action'),'data-toggle'=>'tooltip','escape'=>false],
															'label'=>['icon'=>'flash','text'=>__('Création rapide de toutes les équipes')]
														]
												]
									]); ?>
		<?= $this->element('buttons',[	'controller'=>'demandes',
									'text'=>true,
									'space'=>' ',
									'link'=>false,
									//'action_id'=>$line->id,
									'options'=>['divers'],
									'merge'=>[
												'divers'=>[
															'url'  =>['controller'=>'equipes','action'=>'generate',$demande_id,1],
															'attr' =>['class'=>'btn btn-inverse btn-default btn-sm','title'=>__('Supprime les équipes existantes et recrée les équipes par défaut'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous réellement réinitialiser les équipes et perdre les modifications antérieurs ? # {0}', $demande_id)],
															'label'=>['icon'=>'flash','text'=>__('Réinitialiser les équipes')]
														]
												]
									]); ?>
	</h1>
	
	<?php
/*	
		$this->ProgressGantt->setConfig('datas.conditions',['where'=>['Dimensionnements.demande_id'=>$demande_id],
															'contain' => ['Dispositifs.Dimensionnements'],
															'order'=>['dispositif_id'=>'asc','horaires_convocation'=>'asc']]);
		$this->ProgressGantt->setConfig('limits',0);
		
		$this->ProgressGantt->getLimits(['demande_id'=>$demande_id]);
		
		$this->ProgressGantt->build(); 
*/
	?>

	<div class="container-fluid" style="background-image:	linear-gradient(transparent 0px, rgba(200,200,180,0.5) 1px, transparent 1px),  
															linear-gradient(90deg,transparent 0px,rgba(220,220,200,.5) 1px,transparent 1px),  
															linear-gradient(90deg,transparent 0px,black 1px,transparent 1px),  
															linear-gradient(90deg,transparent 0px,grey 1px,transparent 1px); 
															background-size: 100% 28px, <?= $strtotime['heure'] ?>% 100%, <?= $strtotime['day'] ?>% 100%, <?= $strtotime['6h'] ?>% 100%;">
		<div class="row" style="height: 28px; background-color:white;">
			<center>1 graduation = 1h, traits moyennements foncés = 6h, traits foncés = 1 journée</center>
		</div>
		<div class="row" style="height: 28px; background-color:white;">
			<div class="pull-left">
				<?= $strtotime['start'] ?>
			</div>
			<div class="pull-right">
				<?= $strtotime['end'] ?>
			</div>
		</div>
		<?php foreach($equipes as $equipe): ?>
		<div class="row">
			<div style="height: 28px; width:<?= $equipe->pourcent_convocation ?>%; float:left; background-color:transparent;">&nbsp;</div>
			<div style="height: 26px; width:<?= $equipe->pourcent_place ?>%; float:left; background-color:rgb(3, 60, 115); color:white;" data-toggle="tooltip" data-original-title="<?= $equipe->horaires_convocation ?>">&nbsp;<?= $equipe->effectif ?>&nbsp;</div>
			<div style="height: 26px; width:<?= $equipe->pourcent_fin ?>%; float:left; background-color:rgb(47, 164, 231); color:white;" data-toggle="tooltip" data-placement="left" title="<?= $equipe->horaires_place ?>">
				<small><?= $equipe->indicatif ?></small>
			</div>
			<div style="height: 26px; width:<?= $equipe->pourcent_retour ?>%; float:left; background-color:rgb(3, 60, 115);"  data-toggle="tooltip" data-placement="top" title="<?= $equipe->horaires_fin ?>">&nbsp;</div>
			<div style="height: 26px; width:<?= $equipe->pourcent_reste ?>%; float:left; background-color:transparent;">&nbsp;
			<?php 
				$return = [];
				
				$return[] = ['li'=>'header','icon'=>'user','label'=>$equipe->effectif.' personnel(s)' ];
				$return[] = ['li'=>'header','icon'=>'map-marker','label'=>$equipe->position];
				$return[] = ['li'=>'header','icon'=>'time','label'=>$equipe->duree.' heure(s)' ];
				$return[] = ['li'=>'divider' ];
				$return[] = ['li'=>'edit','icon'=>'pencil','label'=>'Modifier','url'=>['controller'=>'equipes','action'=>'edit',$equipe->id],'attr'=>['title'=>__('Modifier cette équipe'),'data-toggle'=>'tooltip','escape'=>false]];
				$return[] = ['li'=>'postlink','icon'=>'duplicate','label'=>'Copier','url'=>['controller'=>'equipes','action'=>'duplicate',$equipe->id],'attr'=>['title'=>__('Dupliquer cette équipe'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment dupliquer cette équipe # {0} ?', $equipe->id)]];
				$return[] = ['li'=>'postlink','icon'=>'remove','label'=>'Supprimer','url'=>['controller'=>'equipes','action'=>'delete',$equipe->id],'attr'=>['title'=>__('Supprimer cette équipe'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment supprimer cette équipe # {0} ?', $equipe->id)]];
				$return[] = ['li'=>'divider' ];
				$return[] = ['li'=>'header','icon'=>'calendar','label'=>$equipe->horaires_convocation.' (convocation)' ];
				$return[] = ['li'=>'header','icon'=>'calendar','label'=>$equipe->horaires_retour.' (retour caserne)' ];
				
				echo $this->element('drop',['align'=>'dropdown-menu-right','size'=>'btn-group-xs','label'=>false,'icon'=>'cog','actions' => $return]);
			?>
			</div>			
		</div>
		<?php endforeach; ?>
	</div>
</div>
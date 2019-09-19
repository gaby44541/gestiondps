<div class="container-fluid">
	<div class="row">
		<?php
		$this->Breadcrumbs->add('<b>Dossier n°'.$this->Number->format($demande->id).'</b>');
		$this->Breadcrumbs->add('Retourner en vue :');
		$this->Breadcrumbs->add('Liste', ['controller' => 'demandes','action'=>'index']);
		$this->Breadcrumbs->add('Calendrier', ['controller' => 'antennes','action'=>'calendar']);
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
	<div class="col-md-3">
	<?php
		echo $this->Panel->create('i:home Dossier n°'.$this->Number->format($demande->id), ['type' => 'primary']);
		echo $this->element('progression',['etat'=>$demande->config_etat->ordre]);
		//echo $this->Panel->end();

	?>
	</div>
	<div class="col-md-6">
	<?php
		echo $this->Panel->create('i:home Manifestation', ['type' => 'primary']);
		echo '<table cellspacing="10" style="width:100%;">';
		echo '<tr>';
		echo '<td style="width:49%;">';
		echo h($demande->manifestation).'<br/>';
		echo h($demande->representant).'<br/>';
		echo h($demande->representant_fonction).'<br/>';
		echo $demande->has('organisateur') ? $this->Html->link($demande->organisateur->nom, ['controller' => 'Organisateurs', 'action' => 'view', $demande->organisateur->id]) : ''.'</p><br/>';
		echo '</small>';
		echo '</td>';
		echo '<td style="width:2%;">';
		echo '</td>';
		echo '<td style="width:49%;">';
		/*
		$actions = [
						['li'=>'header','label'=>'Dossier :'],
						3=>['icon'=>'flash','label'=>'Modifier le dossier','url'=>['controller'=>'demandes','action'=>'wizard',3,'demandes__'.$demande->id]],
						4=>['li'=>'postlink','icon'=>'envelope','label'=>'Envoyer l\'étude pour signature','url'=>['controller'=>'demandes','action'=>'etude-envoyee',$demande->id],'attr'=>['title'=>__('Envoyer l\'étude manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer l\'étude à l\'organisateur manuellement # {0} ?', $demande->id)]],
						5=>['li'=>'postlink','icon'=>'ok','label'=>'Retour de l\'étude signée','url'=>['controller'=>'demandes','action'=>'etude-signee',$demande->id],'attr'=>['title'=>__('Envoyer l\'étude manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer l\'étude à l\'organisateur manuellement # {0} ?', $demande->id)]],
						6=>['li'=>'postlink','icon'=>'envelope','label'=>'Envoyer la convention pour signature','url'=>['controller'=>'demandes','action'=>'convention-envoyee',$demande->id],'attr'=>['title'=>__('Envoyer la convention manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer la convention à l\'organisateur manuellement # {0} ?', $demande->id)]],
						7=>['li'=>'postlink','icon'=>'ok','label'=>'Retour de la convention signée','url'=>['controller'=>'demandes','action'=>'convention-signee',$demande->id],'attr'=>['title'=>__('Envoyer l\'étude manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer l\'étude à l\'organisateur manuellement # {0} ?', $demande->id)]],
						8=>['li'=>'postlink','icon'=>'calendar','label'=>'Poste réalisé','url'=>['controller'=>'demandes','action'=>'poste-realise',$demande->id],'attr'=>['title'=>__('Le poste a été réalisé'),'data-toggle'=>'tooltip','escape'=>false]],
						9=>['li'=>'postlink','icon'=>'list-alt','label'=>'Rédiger le bilan','url'=>['controller'=>'demandes','action'=>'poste-bilan',$demande->id],'attr'=>['title'=>__('Le poste a été réalisé'),'data-toggle'=>'tooltip','escape'=>false]],
						10=>['li'=>'postlink','icon'=>'envelope','label'=>'Envoyer la facture','url'=>['controller'=>'demandes','action'=>'poste-facture',$demande->id],'attr'=>['title'=>__('Envoyer la facture manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer la convention à l\'organisateur manuellement # {0} ?', $demande->id)]],
						11=>['li'=>'postlink','icon'=>'euro','label'=>'Valider le règlement','url'=>['controller'=>'demandes','action'=>'poste-paye',$demande->id],'attr'=>['title'=>__('Valider le règlement du dossier'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment valider le règlement du dossier # {0} ?', $demande->id)]],
						12=>['li'=>'postlink','icon'=>'remove','label'=>'Annuler le dossier','url'=>['controller'=>'demandes','action'=>'poste-annule',$demande->id],'attr'=>['title'=>__('Annuler ce dossier'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment annuler le dossier # {0} ?', $demande->id)]],
						//['li'=>'header','label'=>'Demande :'],
						//['icon'=>'pencil','label'=>'Modifier la demande','url'=>['controller'=>'demandes','action'=>'edit',$demande->id]],
						//['li'=>'postlink','icon'=>'trash','label'=>'Supprimer','url'=>['controller'=>'demandes','action'=>'view',$demande->id],'attr'=>['title'=>__('Supprimer'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Etes-vous sûr de vouloir supprimer # {0}?', $demande->id)]],
						//['li'=>'divider'],
						//['li'=>'header','label'=>'Documents disponibles :']
					];
		*/
		$return =[0=>['li'=>'header','label'=>'Dossier :']];
		if($demande->config_etat->ordre <11){
			$return[12] = ['li'=>'postlink','icon'=>'remove','label'=>'Annuler le dossier','url'=>['controller'=>'demandes','action'=>'poste-annule',$demande->id],'attr'=>['title'=>__('Annuler ce dossier'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment annuler le dossier # {0} ?', $demande->id)]];
		}
		if($demande->config_etat->ordre <=4){
			$return[1] = ['icon'=>'flash','label'=>'Modifier le dossier','url'=>['controller'=>'demandes','action'=>'wizard',3,'demandes__'.$demande->id]];
		}
		if($demande->config_etat->ordre ==3){
			$return[2] = ['li'=>'postlink','icon'=>'envelope','label'=>'Envoyer l\'étude pour signature','url'=>['controller'=>'demandes','action'=>'etude-envoyee',$demande->id],'attr'=>['title'=>__('Envoyer l\'étude manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer l\'étude à l\'organisateur manuellement # {0} ?', $demande->id)]];
		}
		if($demande->config_etat->ordre ==4){
				$return[3] = ['li'=>'postlink','icon'=>'ok','label'=>'Retour de l\'étude signée','url'=>['controller'=>'demandes','action'=>'etude-signee',$demande->id],'attr'=>['title'=>__('Envoyer l\'étude manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer l\'étude à l\'organisateur manuellement # {0} ?', $demande->id)]];
		}
		if($demande->config_etat->ordre ==5){
			$return[5] = ['li'=>'postlink','icon'=>'envelope','label'=>'Envoyer la convention pour signature','url'=>['controller'=>'demandes','action'=>'convention-envoyee',$demande->id],'attr'=>['title'=>__('Envoyer la convention manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer la convention à l\'organisateur manuellement # {0} ?', $demande->id)]];
		}
		if($demande->config_etat->ordre ==6){
			$return[6] = ['li'=>'postlink','icon'=>'ok','label'=>'Retour de la convention signée','url'=>['controller'=>'demandes','action'=>'convention-signee',$demande->id],'attr'=>['title'=>__('Envoyer l\'étude manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer l\'étude à l\'organisateur manuellement # {0} ?', $demande->id)]];
		}
		if($demande->config_etat->ordre ==7){
			$return[7] = ['li'=>'postlink','icon'=>'calendar','label'=>'Poste réalisé','url'=>['controller'=>'demandes','action'=>'poste-realise',$demande->id],'attr'=>['title'=>__('Le poste a été réalisé'),'data-toggle'=>'tooltip','escape'=>false]];
		}
		if($demande->config_etat->ordre ==8){
			$return[8] = ['li'=>'postlink','icon'=>'list-alt','label'=>'Rédiger le bilan','url'=>['controller'=>'demandes','action'=>'poste-bilan',$demande->id],'attr'=>['title'=>__('Le poste a été réalisé'),'data-toggle'=>'tooltip','escape'=>false]];
		}
		if($demande->config_etat->ordre ==9){
			$return[9] = ['li'=>'postlink','icon'=>'envelope','label'=>'Envoyer la facture','url'=>['controller'=>'demandes','action'=>'poste-facture',$demande->id],'attr'=>['title'=>__('Envoyer la facture manuellement pour signature'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment envoyer la facture à l\'organisateur manuellement # {0} ?', $demande->id)]];
		}
		if($demande->config_etat->ordre ==10){
			$return[10] = ['li'=>'postlink','icon'=>'euro','label'=>'Valider le règlement','url'=>['controller'=>'demandes','action'=>'poste-paye',$demande->id],'attr'=>['title'=>__('Valider le règlement du dossier'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment valider le règlement du dossier # {0} ?', $demande->id)]];
		}
		if($demande->config_etat->ordre >10){
			$return[14] = ['li'=>'header','label'=>'Ce dossier est clôturé vous ne pouvez plus rien faire.'];
			$return[15] = ['li'=>'header','label'=>'Prochainement une option pour dupliquer un dossier.'];
		}
		ksort($return);
		echo $this->element('drop',['align'=>'dropdown-menu-right','label'=>'Traiter ce dossier','icon'=>'list','actions' => $return]);

		//echo $this->element('actions',['td'=>false,'controller'=>'demandes','group'=>'toolbar','pull'=>'left','options'=>['wizard'=>['type'=>'wizard','label'=>__('<i class="glyphicon glyphicon-flash"></i> Apporter des modifications au dossier'),'url'=>['controller'=>'demandes','action' => 'wizard',5,'demandes__'.$demande->id],'params'=>['class' => 'btn btn-xs btn-primary', 'data-toggle' => 'tooltip', 'title' => 'Sélectionner cet item et passer à la suite du dossier', 'escape' => false]]],'action_id'=>$demande->id]);
		echo '<br/>';
		echo '<br/>';
		echo '<br/>';
		echo '<br/>';
		echo '</td>';
		echo '</tr>';
		echo '</table>';
		echo $this->Panel->end();

		echo $this->Panel->create('i:plus Déclaration(s) et dispositif(s)', ['type' => 'primary']);
		echo '<small>';
		echo __('Les déclarations suivantes ont été saisies :');
		echo '</small>';
		//echo $this->Panel->footer();
		//echo $this->element('buttons',['controller'=>'Dimensionnements','options'=>'association','association'=>$demande->id,'text'=>['association'=>__('Déclarer une épreuve ou date')]]);
		if(count($demande->dimensionnements)<1){
			echo '&nbsp;'.$this->Html->badge(__('Le dossier est bloqué : saisir au moins une déclaration'),['class'=>'btn-danger']);
			echo $this->element('actions',['td'=>false,'controller'=>'demandes','group'=>'toolbar','pull'=>'left','options'=>['wizard'=>['type'=>'wizard','label'=>__('<i class="glyphicon glyphicon-flash"></i> Faire au moins une déclaration'),'url'=>['controller'=>'demandes','action' => 'wizard',3,'demandes__'.$demande->id],'params'=>['class' => 'btn btn-xs btn-primary', 'data-toggle' => 'tooltip', 'title' => 'Sélectionner cet item et passer à la suite du dossier', 'escape' => false]]],'action_id'=>$demande->id]);
		}

		foreach ($demande->dimensionnements as $dimensionnements):
		echo $this->Panel->footer();
		echo '<table cellspacing="10" style="width:100%;">';
		echo '<tr>';
		echo '<td style="width:49%;">';
		echo '<b>'.h($dimensionnements->intitule).'</b><br/>';
		echo '<small>';
		echo h($dimensionnements->horaires_debut).' au '.h($dimensionnements->horaires_fin).'<br/>';
		echo 'Lieu : '. h($dimensionnements->lieu_manifestation).'<br/>';
		echo 'Contact : '. h($dimensionnements->contact_present);
		echo '</small>';
		echo '</td>';
		echo '<td style="width:2%;">';
		echo '</td>';
		echo '<td style="width:49%;">';
		if(isset($dimensionnements->dispositif)){
		echo '<b>'.h('Composition du dispositif :').'</b><br/>';
		echo '<small>';
		echo h($dimensionnements->dispositif->personnels_total).' équipiers sont prévus<br/>';
		if(count($dimensionnements->dispositif->equipes)>0){
			echo '<br/>';
			echo count($dimensionnements->dispositif->equipes).' équipes sont prévues<br/>';
		} else {
			//echo $this->element('actions',['td'=>false,'controller'=>'demandes','group'=>'toolbar','pull'=>'left','options'=>['wizard'=>['type'=>'wizard','label'=>__('<i class="glyphicon glyphicon-flash"></i> Créer les équipes correspondantes'),'url'=>['controller'=>'demandes','action' => 'wizard',5,'demandes__'.$demande->id],'params'=>['class' => 'btn btn-xs btn-primary', 'data-toggle' => 'tooltip', 'title' => 'Sélectionner cet item et passer à la suite du dossier', 'escape' => false]]],'action_id'=>$demande->id]);
			echo $this->Html->label(_('Pour calculer le tarif, créez toutes les équipes'),'warning');
			echo '<br/>';
			echo $this->element('buttons',[	'controller'=>'demandes',
									'text'=>true,
									'space'=>' ',
									'link'=>false,
									//'action_id'=>$demande_id,
									'options'=>['divers'],
									'merge'=>[
												'divers'=>[
															'url'  =>['controller'=>'equipes','action'=>'generate',$demande->id,0],
															'attr' =>['class'=>'btn btn-inverse btn-default btn-xs','title'=>__('Crée toutes les équipes par défaut en une seule action'),'data-toggle'=>'tooltip','escape'=>false],
															'label'=>['icon'=>'flash','text'=>__('Créer les équipes pour continuer')]
														]
												]
									]);
		}
		} else {
			echo $this->element('actions',['td'=>false,'controller'=>'demandes','group'=>'toolbar','pull'=>'left','options'=>['wizard'=>['type'=>'wizard','label'=>__('<i class="glyphicon glyphicon-flash"></i> Créer le dispositif correspondant'),'url'=>['controller'=>'demandes','action' => 'wizard',4,'demandes__'.$demande->id],'params'=>['class' => 'btn btn-xs btn-primary', 'data-toggle' => 'tooltip', 'title' => 'Sélectionner cet item et passer à la suite du dossier', 'escape' => false]]],'action_id'=>$demande->id]);
		}
		echo '</small>';
		echo '</td>';
		//echo $this->element('actions',['controller'=>'Dimensionnements','action_id'=>$dimensionnements->id]);
		echo '</tr>';
		echo '</table>';
		endforeach;
		echo $this->Panel->end();
	?>
	</div>
	<div class="col-md-3">
	<?php
		echo $this->Panel->create('i:phone En charge du dossier', ['type' => 'primary']);
		echo h($demande->gestionnaire_nom).'<br/>';
		echo '<a href="mailto:'.h($demande->gestionnaire_mail).'"><small>'.h($demande->gestionnaire_mail).'</small></a><br/>';
		echo '<a href="tel:'.h($demande->gestionnaire_telephone).'"><small>'.h($demande->gestionnaire_telephone).'</small></a><br/>';
		if(empty($demande->gestionnaire_nom)){
			echo '<p>'.__('En attente d\'affectation.').'</p>';
		}
		echo $this->Panel->end();

		if(!empty($demande->total_cout)){
		$pourcent = (int) (( $demande->total_economie * 100 ) / $demande->total_cout);
		} else {
		$pourcent  = 0;
		}

		//if($demande->remise_justification){
		if(isset($dimensionnements->dispositif->equipes)&&$demande->config_etat->ordre<20){
		echo $this->Panel->create('i:euro Facturation : '.$demande->total_remise.' €', ['type' => 'danger']);
		echo $this->Form->create($demande, ['url' => ['action' => 'remise',$demande->id]]);
		echo $this->Form->control('pourcentage', ['readonly'=>$readonly,'class' => 'form-control','label'=>false,'placeholder'=>__('Montant de l\'adaptation'),'append'=>'%','help' => __('Applicable au '.$demande->total_cout.' € d\'origine'),'value'=>$pourcent]);
		echo $this->Form->control('remise_justification', ['readonly'=>$readonly,'type'=>'textarea','rows'=>2,'class' => 'form-control','label'=>false,'placeholder'=>__('Justification de l\'adaptation'),'help' => __('Justification de l\'adaptation'),'value'=>$demande->remise_justification]);
		if(!$readonly) {
			echo $this->Form->button(__('Adapter la tarification'),['class' => 'btn btn-xs btn-danger']);
		}
		echo $this->Form->end();
		echo $this->Panel->end();
		}
		//}

		echo $this->Panel->create('i:print Documents administratifs', ['type' => 'primary']);
		echo '<small>';
		if($demande->config_etat->ordre<3){
			echo __('Aucun document n\'est disponible actuellement car le dossier est en cours de traitement. Merci de le compléter pour pouvoir continuer.');
		} else {
			echo __('Espace de téléchargement et d\'impression de vos  documents officiels.');
		}
		echo '</small>';
		if($demande->config_etat->ordre>=3&&$demande->config_etat->ordre<12){
			echo $this->Panel->footer();
			echo '<center>';
			echo $this->Html->link(
				'Etude de poste',
				['controller'=>'demandes','action'=>'etude',$demande->id],
				['class' => 'button', 'target' => '_blank']
			);
			echo '</center>';
		}
		if($demande->config_etat->ordre>=7&&$demande->config_etat->ordre<12){
			echo $this->Panel->footer();
			echo '<center>';
			echo $this->Html->link(
				'Convention de poste',
				['controller'=>'demandes','action'=>'convention',$demande->id],
				['class' => 'button', 'target' => '_blank']
			);
			echo '</center>';
		}
		if($demande->config_etat->ordre>=3&&$demande->config_etat->ordre<12){
			echo $this->Panel->footer();
			echo '<center>';
			echo $this->Html->link(
				'Planning mission',
				['controller'=>'demandes','action'=>'planning',$demande->id],
				['class' => 'button', 'target' => '_blank']
			);
			echo '</center>';
		}
		if($demande->config_etat->ordre>=9&&$demande->config_etat->ordre<12){
			echo $this->Panel->footer();
			echo '<center>';
			echo $this->Html->link(
				'Facture',
				['controller'=>'demandes','action'=>'facture',$demande->id],
				['class' => 'button', 'target' => '_blank']
			);
			echo '</center>';
		}
		echo $this->Panel->end();
	?>
	</div>
</div>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	    $('#Dimensionnements').DataTable();
	</script>

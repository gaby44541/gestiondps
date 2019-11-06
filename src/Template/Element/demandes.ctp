<?php
$etat = isset($etat) ? $etat : true;
$icon = isset($icon) ? $icon : ['edit','view'];

$organisateur_identifies = isset($organisateur_identifie) ? $organisateur_identifies : 0;
$mini = isset($mini) ? $mini : 0;
$maxi = isset($maxi) ? $maxi : 0;

$mini = (int) $mini;
$maxi = (int) $maxi;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Hash;
use Cake\I18n\FrozenTime;

$demande = TableRegistry::get('Demandes');

if($mini==9||$mini==8||$mini==5){
	$demandes = $demande->listeMiniMaxiAll($mini,$maxi);
	$solde_total = 0;
} else {
	$demandes = $demande->listeMiniMaxi($mini,$maxi);
}

foreach($demandes as $line):

	$horaires_debut = Hash::extract($line->dimensionnements,'0.horaires_debut');

	if($mini==9||$mini==8){
		$total = Hash::extract($line,'dimensionnements.{n}.dispositif.equipes.{n}.cout_remise');
		$solde_total += array_sum($total);
	}

	if(isset($horaires_debut[0])){

		$time = $horaires_debut[0]->format('m / Y');

		if($mini!=9&&$mini!=8){
			if($horaires_debut[0]->isWithinNext('3 months')){
				$this->append('demandes_3months');
			}elseif($horaires_debut[0]->wasWithinLast('6 months')) {
				$this->append('demandes_late');
			}else{
				$this->append('demandes_6months');
			}
		} else {
			$this->append('demandes_nodate');
		}
		if($mini!=9){
		echo '<p>'.$this->element('buttons',[	'controller'=>'demandes',
									'text'=>true,
									'space'=>' ',
									'action_id'=>$line->id,
									'options'=>['divers'],
									'merge'=>[
												'divers'=>[
															'url'  =>['controller'=>'demandes','action' => 'dispatch',$line->id],
															'attr' =>['class'=>'btn btn-inverse btn-default btn-xs','title'=>__('Sélectionner ce dossier'),'data-toggle'=>'tooltip','escape'=>false],
															'label'=>['icon'=>'pencil','text'=>$time]
														]
												]
									]);

		}
		if($mini==9){
			$return = [];
			$return[] = ['icon'=>'pencil','label'=>'Voir le dossier','url'=>['controller'=>'demandes','action'=>'dispatch',$line->id]];
			$return[] = ['li'=>'divider'];
			$return[] = ['li'=>'postlink','icon'=>'envelope','label'=>'Faire une relance','url'=>['controller'=>'demandes','action'=>'relance-facture',$line->id],'attr'=>['title'=>__('Relance de paiement'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous faire la relance pour cette facture numéro : # {0} ?', $line->id)]];
			$return[] = ['li'=>'postlink','icon'=>'euro','label'=>'Valider le règlement','url'=>['controller'=>'demandes','action'=>'poste-paye',$line->id],'attr'=>['title'=>__('Valider le règlement du dossier'),'data-toggle'=>'tooltip','escape'=>false,'confirm' => __('Voulez vous vraiment valider le règlement du dossier # {0} ?', $line->id)]];

			echo $this->element('drop',['color'=>'btn-inverse btn-default btn-xs','align'=>'dropdown-menu-left','label'=>$time,'icon'=>'pencil','actions' => $return]);
		}
		echo '&nbsp'.$line->manifestation;
		if($mini==9){
			echo '&nbsp-&nbsp<b>'.$this->Number->currency($line->somme_facturee).'</b>';
		}
		echo '</p>';
		$this->end();
	}

endforeach;
$this->append('demandes_top');
	if($mini==8){
		echo __('Total en attente de facturation : ').'<b>'.$this->Number->currency($solde_total).'</b>';
		echo '<hr/>';
	}
	if($mini==9){
		echo __('Total des factures impayées : ').'<b>'.$this->Number->currency($solde_total).'</b>';
		echo '<hr/>';
	}
	if(empty($demandes)){
		echo '<p style="text-align:justify; font-size:smaller;">Aucune demande en cours</p>';
	}
$this->end();

if($this->fetch('demandes_top')):
    echo $this->fetch('demandes_top');
endif;
if($this->fetch('demandes_late') && $mini <12 ):
	if($mini != 8){
		echo $this->Html->label($this->Html->icon('alert').'&nbsp;'.__('Poste(s) déjà passé !'),'danger').'<br/>';
	}
    echo $this->fetch('demandes_late');
endif;
if($this->fetch('demandes_3months')):
	echo $this->Html->label($this->Html->icon('alert').'&nbsp;'.__('Poste(s) dans moins de 3 mois.'),'danger').'<br/>';
	switch($mini){
		case 0;
		case 1;
		case 2;
		case 3;
			echo '<div style="text-align:justify;"><small>Il est temps de traiter ces dossiers, dans <b>1 mois</b> la préfecture devra avoir le dossier finalisé.</small></div><br/>';
			break;
		case 4;
			echo '<div style="text-align:justify;"><small>Il est temps de vérifier vos mails, <b>d\'appeler</b> les organisateurs prioritairement <b>et</b> de les relancer par mail secondairement.</small></div><br/>';
			break;
	}
    echo $this->fetch('demandes_3months');
endif;
if($this->fetch('demandes_6months')):
	echo $this->Html->label(__('Dossier plus tard dans l\'année'),$type).'<br/>';
    echo $this->fetch('demandes_6months');
endif;
if($this->fetch('demandes_nodate')):
    echo $this->fetch('demandes_nodate');
endif;

$this->reset('demandes_top');
$this->reset('demandes_late');
$this->reset('demandes_3months');
$this->reset('demandes_6months');
$this->reset('demandes_nodate');
?>

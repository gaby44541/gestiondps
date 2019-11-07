<center style="background-color:#f2f2f2;"><?= __('DISPOSITIF PREVISIONNEL DE SECOURS - ETUDE N° ') . $demande->id ?></center>
<br/>
<table cellpadding="0" cellspacing="0" class="table table-striped" >
    <thead>
        <tr>
            <td>
                <b><?= __('Manifestation') ?></b>
                <?= h($demande->manifestation) ?><br />
                <b>Déclaration en vue de dimensionnement de :</b><br />
                <?= count($demande->dimensionnements) . ' épreuve(s) ou journée(s)' ?> <br />
                <br />
                <b>Demande effectuée par :</b><br />
                <?= h($demande->representant) ?> - <?= h($demande->representant_fonction) ?><br/>
                <br />
                <b>Personnel Protection Civile en charge de votre dossier :</b><br />
                <?= h($demande->gestionnaire_nom) ?> - <?= h($demande->gestionnaire_telephone) ?><br/>
                <?= h($demande->gestionnaire_mail) ?><br/>
                <?= h($demande->antenne->antenne) ?><br/>
                <?= h($demande->antenne->adresse) ?><br />
                <?= h($demande->antenne->adresse_suite) ?><br />
                <?= h($demande->antenne->code_postal) ?> <?= h($demande->antenne->ville) ?><br />
            </td>
            <td style="width:50%;">
                <b><?= __('Organisé par') ?></b>
                <?= h($demande->organisateur->nom) ?><br />
                <?= h($demande->organisateur->adresse) ?><br />
                <?= h($demande->organisateur->adresse_suite) ?><br />
                <?= h($demande->organisateur->code_postal) ?> <?= h($demande->organisateur->ville) ?><br />
                <?= h($demande->organisateur->telephone) ?> <?= h($demande->organisateur->portable) ?><br />
                <?= h($demande->organisateur->mail) ?><br />
                <br />
                <b>Représenté par :</b><br />
                <?= h($demande->organisateur->representant_nom) ?> <?= h($demande->organisateur->representant_prenom) ?> -  <?= h($demande->organisateur->fonction) ?><br />
                <br />
            </td>
        </tr>
    </thead>
</table>
<table class="table">
    <tr>
        <th colspan="3" style="background-color:#f2f2f2;">
        <center>Synthèse de l'étude</center>
        <div style="margin:0px;">La présente étude a été dimensionnée sur la base de vos déclarations et conformément à la règlementation en vigueur pour les associations agréées de Sécurité Civile, en tenant compte d'éventuelles de fédérations le cas échéant, mais surtout en tenant compte des caractéristiques de votre manifestation et des problématiques attenantes.</div>
        </th>
    </tr>
    <tr>
        <td style="width:30%; border:1px solid #f2f2f2;">
            <center><?= $demande->total_personnel ?> personnels</center>
            <div style="margin:0px;">Nos personnels sont tous à jour de leurs obligations règlementaires en terme de formation annuelle obligatoire.<br/><br/>Ils sont identifiables par leur tenue et placés sous l'autorité exclusive de leur chef de dispositif : engagement, positionnement, moyens, ...</div>
        </td>
        <td style="width:30%;  border:1px solid #f2f2f2;">
            <center>3 véhicules</center>
            <div style="text-align:justify;">Nos véhicules sont équipés au delà de la réglementation afin de pouvoir assurer nos missions avec professionnalisme.<br/><br/>Des accès pour ces véhicules doivent être prévu par vos soins. Le gabarit maximal à prendre en compte est un L3H3.</div>
        </td>
        <td style="width:40%; border:1px solid #f2f2f2;" rowspan="2">
			<?php if($demande->total_cout != $demande->total_remise){ ?>
				<center><del><?= $this->Number->format( $demande->total_cout ) ?> €</del>&nbsp; : &nbsp;<?= $this->Number->format( $demande->total_remise ) ?> €</center>
				<div style="margin:0px;"><?= nl2br($demande->remise_justification) ?><br /><br />Nous sommes une association composée <b>entièrement de bénévoles</b>.<br/><br/> Cette contrepartie financière nous sert à <b>couvrir les frais engagés</b> pour la mission, <b>acheter et renouveler le matériel</b> pour être en conformité avec les impositions légales et le cahier des charges qui nous est imposé par l'état, <b>former nos équipes</b>.</div>
			<?php } else { ?>
				<center><?= $this->Number->format( $demande->total_remise ) ?> €</center>
				<div style="margin:0px;">Nous sommes une association composée <b>entièrement de bénévoles</b>.<br/><br/> Cette contrepartie financière nous sert à <b>couvrir les frais engagés</b> pour la mission, <b>acheter et renouveler le matériel</b> pour être en conformité avec les impositions légales et le cahier des charges qui nous est imposé par l'état, <b>former nos équipes</b>.<br/><br/>Contrairement aux idées reçues, nous ne percevons pas de subvention de l'état. Les aides locales (conseil départemental et mairies) représentent 2% de notre budget annuel moyen.</div>
			<?php } ?>

        </td>
    </tr>
    <tr>
        <td style="width:50%; border:1px solid #f2f2f2;">
            <center>Transports</center>
            <div style="margin:0px;">Les modalités de transport par nos soins vers une structure hospitalière effectués sont précisées dans le détails du dimensionnement ci-après.<br /><br />Des moyens dédiés peuvent être prévu en plus du dispositif courant qui à lui seul ne pourrait les effectuer car leur absence entrainerait l'arrêt de la manifestation durant le transport.</div>
        </td>
        <td style="width:50%; border:1px solid #f2f2f2;">
            <center>Repas à votre charge</center>
            <div style="margin:0px;">
                Matin : <?= $demande->total_repas_matin ?> repas<br/>
                Midi : <?= $demande->total_repas_midi ?> repas (prévoir 2 sans porc)<br/>
                Soir : <?= $demande->total_repas_soir ?> repas (prévoir 2 sans porc)<br/>
                <br/>
                Suite à quelques organisateurs indélicats nous vous informons qu'en cas de difficultés sur site, nous facturerons les repas par la suite.
            </div>
        </td>
    </tr>
</table>
<div style="color:red; margin:0px;">
    <b>Attention :</b> Ceci est une étude et donc en aucun cas un engagement ferme et définitif. Seule une convention établie en bonne dûe forme peut engager notre structure. Afin de vous permettre d'obtenir cette convention, merci de nous retourner dans les plus brefs délais ce document signé.<br/>
    <br />
    En l'absence de retour dans un délai de 7 jours, ou si votre dossier n'est pas complet, nous nous réservons le droit de faire passer prioritairement tout autre dossier complété.<br/>
    <br />
    <b>Merci de lire l'intégralité du document, de parapher chaque page et signer en bas de ce dernier comme indiqué. Si les pages sont non paraphées, votre dossier ne pourra être pris en compte.</b>
</div>
<table class="table">
    <tbody>
        <tr>
            <td style="width:50%; border:none;">
                <br/>
                <b>Retourner les documents signés à :</b><br/>
                <?= h($demande->gestionnaire_mail) ?> <b>ou</b><br/>
                <?= h($demande->antenne->antenne) ?><br/>
                <?= h($demande->antenne->adresse) ?>, <?= h($demande->antenne->adresse_suite) ?><br />
                <?= h($demande->antenne->code_postal) ?> <?= h($demande->antenne->ville) ?><br />
            </td>
            <td style="width:50%; border:1px solid #f2f2f2; text-align:center;"><b>Date, cachet, signature et mention manuscrite "bon pour accord.</b><br/><br/><br/><br/><br/><br/></td>
        </tr>
    </tbody>
</table>
<?= $this->element('annexes',['demande'=>$demande]) ?>

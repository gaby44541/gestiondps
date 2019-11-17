<div class="container-fluid">
	<div class="row">
	<?php
	$this->Breadcrumbs->add('Créer un dossier en mode express', ['controller' => 'organisateurs','action'=>'wizard']);
	$this->Breadcrumbs->add('Basculer en vue :');
	$this->Breadcrumbs->add('Liste', ['controller' => 'demandes','action'=>'index']);
	$this->Breadcrumbs->add('Calendrier', ['controller' => 'antennes','action'=>'calendar']);
	$this->Breadcrumbs->add('Tableau de bord');

	echo $this->Breadcrumbs->render(
		['class' => 'breadcrumb'],
		[]
	);
	?>
	</div>
</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
			<?php
				echo $this->Panel->create('i:menu-hamburger 1. Mes demandes en cours de traitement', ['type' => 'primary']);
				/*On appelle demandes.ctp en mettant les variables mini à 0 et maxi à 3*/
				echo $this->element('demandes',['mini'=>0,'maxi'=>3,'type' => 'info']);
				echo $this->Panel->end();

				echo $this->Panel->create('i:menu-hamburger 2. En attente de validation du COA', ['type' => 'warning']);
                echo $this->element('demandes',['mini'=>4,'maxi'=>4,'type' => 'warning','icon'=>['view']]);
                echo $this->Panel->end();

                echo $this->Panel->create('i:menu-hamburger 3. COA validé', ['type' => 'success']);
                echo $this->element('demandes',['mini'=>5,'maxi'=>5,'type' => 'warning','icon'=>['view']]);
                echo $this->Panel->end();
			?>

			</div>
			<div class="col-md-4">
			<?php
                echo $this->Panel->create('i:menu-hamburger 4. Attente de la signature de l\'étude', ['type' => 'warning']);
				?><!--
				<div id="countdown" class="panel panel-warning">
					<div class=" panel-heading">
						<span id="countdown_day" >--</span> jours
						<span id="countdown_hour">--</span> heures
						<span id="countdown_min" >--</span> minutes
						<span id="countdown_sec" >--</span> secondes<br/>
						avant annulation automatique de ces dossiers
					</div>
				</div>-->
				<?php
				echo $this->element('demandes',['mini'=>6,'maxi'=>6,'type' => 'warning','icon'=>[]]);
				echo $this->Panel->end();

				echo $this->Panel->create('i:ok 5. En attente de(s) convention(s) signée(s)', ['type' => 'warning']);
				echo $this->element('demandes',['mini'=>8,'maxi'=>8,'type' => 'success','icon'=>['view']]);
				echo $this->Panel->end();

				echo $this->Panel->create('i:ok 6. Convention(s) signée(s)', ['type' => 'success']);
				echo $this->element('demandes',['mini'=>9,'maxi'=>9,'type' => 'success','icon'=>['view']]);
				echo $this->Panel->end();
			?>
			</div>
			<div class="col-md-4">
			<?php
				echo $this->Panel->create('i:euro 7. Poste(s) réalisé(s) non facturé(s)', ['type' => 'primary']);
				echo $this->element('demandes',['mini'=>10,'maxi'=>10,'type' => 'primary','icon'=>['view']]);
				echo $this->Panel->end();

				echo $this->Panel->create('i:euro 8. Règlements en attente', ['type' => 'primary']);
				echo $this->element('demandes',['mini'=>12,'maxi'=>13,'type' => 'primary','icon'=>['view']]);
				echo $this->Panel->end();

				echo $this->Panel->create('i:alert Demandes refusées', ['type' => 'danger']);
				echo $this->element('demandes',['mini'=>14,'maxi'=>14,'type' => 'danger','icon'=>['view']]);
				echo $this->Panel->end();
			?>
			</div>
		</div>
    </div>

<script type="text/javascript">

	countdownManager = {
    // Configuration
    targetTime: new Date('<?php echo date('Y-m-10 00:00:00'); ?>'), // Date cible du compte à rebours (00:00:00)
    displayElement: { // Elements HTML où sont affichés les informations
        day:  null,
        hour: null,
        min:  null,
        sec:  null
    },

    // Initialisation du compte à rebours (à appeler 1 fois au chargement de la page)
    init: function(){
        // Récupération des références vers les éléments pour l'affichage
        // La référence n'est récupérée qu'une seule fois à l'initialisation pour optimiser les performances
        this.displayElement.day  = jQuery('#countdown_day');
        this.displayElement.hour = jQuery('#countdown_hour');
        this.displayElement.min  = jQuery('#countdown_min');
        this.displayElement.sec  = jQuery('#countdown_sec');

        // Lancement du compte à rebours
        this.tick(); // Premier tick tout de suite
        window.setInterval("countdownManager.tick();", 1000); // Ticks suivant, répété toutes les secondes (1000 ms)
    },

    // Met à jour le compte à rebours (tic d'horloge)
    tick: function(){
        // Instant présent
        var timeNow  = new Date();

        // On s'assure que le temps restant ne soit jamais négatif (ce qui est le cas dans le futur de targetTime)
        if( timeNow > this.targetTime ){
            timeNow = this.targetTime;
        }

        // Calcul du temps restant
        var diff = this.dateDiff(timeNow, this.targetTime);

        this.displayElement.day.text(  diff.day  );
        this.displayElement.hour.text( diff.hour );
        this.displayElement.min.text(  diff.min  );
        this.displayElement.sec.text(  diff.sec  );
    },

    // Calcul la différence entre 2 dates, en jour/heure/minute/seconde
    dateDiff: function(date1, date2){
        var diff = {}                           // Initialisation du retour
        var tmp = date2 - date1;

        tmp = Math.floor(tmp/1000);             // Nombre de secondes entre les 2 dates
        diff.sec = tmp % 60;                    // Extraction du nombre de secondes
        tmp = Math.floor((tmp-diff.sec)/60);    // Nombre de minutes (partie entière)
        diff.min = tmp % 60;                    // Extraction du nombre de minutes
        tmp = Math.floor((tmp-diff.min)/60);    // Nombre d'heures (entières)
        diff.hour = tmp % 24;                   // Extraction du nombre d'heures
        tmp = Math.floor((tmp-diff.hour)/24);   // Nombre de jours restants
        diff.day = tmp;

        return diff;
    }
};

jQuery(function($){
    // Lancement du compte à rebours au chargement de la page
    countdownManager.init();
});
</script>

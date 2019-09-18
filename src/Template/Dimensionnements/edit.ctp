<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dimensionnement $dimensionnement
 */
?>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/i18n/defaults-fr_FR.min.js"></script>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );

echo $this->Html->css('https://unpkg.com/leaflet@1.3.4/dist/leaflet.css',['integrity'=>'sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==','crossorigin'=>'']);
echo $this->Html->css('https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css');

echo $this->Html->script('https://unpkg.com/leaflet@1.3.4/dist/leaflet.js',['integrity'=>'sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==','crossorigin'=>'']);
echo $this->Html->script('https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js');
echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.4.0/gpx.min.js');
?>
<div class="dimensionnements edit content">
	<h1>
	<?= __('Modifier un dimensionnement') ?>
	<?= $this->element('buttons',['controller'=>'demandes','options'=>'cancel','action_id'=>$dimensionnement->demande_id]) ?>
	</h1>
	<?php
	$this->Form->setConfig('columns', [
		'sm' => [
			'label' => 4,
			'input' => 8,
			'error' => 0
		],
		'md' => [
			'label' => 4,
			'input' => 8,
			'error' => 0
		]
	]);
	?>
    <?= $this->Form->create($dimensionnement, ['horizontal' => true]) ?>
    <fieldset>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('dimensionnement') ?></h3>        
        <?php
            echo $this->Form->control('demande_id', ['options' => $demandes,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('intitule', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('horaires_debut', ['type'=>'datetimepicking','empty' => true,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('horaires_fin', ['type'=>'datetimepicking','empty' => true,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('lieu_manifestation', ['class' => 'form-control','help' => __('Merci d\'indiquer un lieu précis de manifestation : une adresse postale complète, ou des coordonnées GPS en latitude et longitude.')]);
            echo $this->Form->control('risques_particuliers', ['class' => 'form-control','help' => __('')]);
		?>
		// <div style="width: 100%; height: 200px;">
		// <div id="mapid" style="width: 100%; height: 200px;"></div>
		// <script>
			// var mymap = L.map('mapid').setView([51.505, -0.09], 13);

			// L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
				// maxZoom: 13,
				// attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
				// id: 'mapbox.streets'
			// }).addTo(mymap);

			// <?= $this->OpenLayers->layers() ?>
			// <?= $this->OpenLayers->gpx('http://www.protectioncivile-vosges.org/gestiondps/img/tracedetrail_48027.gpx') ?>
		// </script>
		// </div>

		<h3><?= __('Contact présent') ?></h3> 
		<?php
            echo $this->Form->control('contact_portable', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('contact_present', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('contact_fonction', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('contact_telephone', ['class' => 'form-control','help' => __('')]);
		?>
		<h3><?= __('Public') ?></h3> 
		<?php
			echo $this->Panel->create();
			echo $this->Panel->body();
			echo __('L\'effectif a inscrire doit être l\'effectif pondéré, soit au plus fort de la journée ou manifestation, quel est le maximum de public présent simultanément.');
			echo $this->Panel->end();
			
            echo $this->Form->control('public_effectif', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('public_age', ['class' => 'form-control','help' => __('')]);
			
			echo $this->Form->control('assis', ['class' => 'selectpicker','options'=>['Assis'=>'Assis','Debout'=>'Debout','Statique'=>'Statique','Dynamique'=>'Dynamique'],'multiple'=>true,'help' => __('Décrire les modalités d\'accès pour les véhicules et la circulation sur la manifestation')]);
			echo $this->Form->control('besoins_particuliers', ['class' => 'form-control','help' => __('Traducteur, Déplacements, ...')]);
		?>
		<h3><?= __('Acteurs') ?></h3> 
		<?php
			echo $this->Panel->create();
			echo $this->Panel->body();
			echo __('Sont considérés comme acteurs toutes personnes concourant ou à l\'initiative de la manifestation et présentes sur place. Exemple : traileur, pilotes sur un rallye, artistes sur un concert, ...');
			echo $this->Panel->end();
			
            echo $this->Form->control('acteurs_effectif', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('acteurs_age', ['class' => 'form-control','help' => __('')]);
		?>
		<h3><?= __('Configuration') ?></h3> 
		<?php
            echo $this->Form->control('circuit', ['class' => 'form-control','data-toggle'=>'toggle','data-on'=>'Circuit','data-off'=>'Espace public','help' => __('')]);
            echo $this->Form->control('ouvert', ['class' => 'form-control','data-toggle'=>'toggle','data-on'=>'Circuit ouvert','data-off'=>'Circuit fermé','help' => __('')]);
            echo $this->Form->control('superficie', ['class' => 'form-control','append'=>'hectares','help' => __('')]);
            echo $this->Form->control('distance_maxi', ['class' => 'form-control','append'=>'mètres','help' => __('Entre les points les plus éloignés de la manifestation')]);
            //echo $this->Form->control('acces', ['class' => 'form-control','help' => __('Décrire les modalités d\'accès pour les véhicules et la circulation sur la manifestation')]);
            echo $this->Form->control('acces', ['class' => 'selectpicker','options'=>$environnements,'multiple'=>true,'help' => __('Décrire les modalités d\'accès pour les véhicules et la circulation sur la manifestation')]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h3><?= __('Proximité des secours publics') ?></h3>  
		<?php      
            echo $this->Form->control('pompier', ['class' => 'form-control','help' => __('Caserne la plus proche')]);
            echo $this->Form->control('pompier_distance', ['class' => 'form-control','append'=>'km','help' => __('')]);		
			echo $this->Form->control('hopital', ['class' => 'form-control','help' => __('Hôpital avec des urgences le plus proche')]);
            echo $this->Form->control('hopital_distance', ['class' => 'form-control','append'=>'km','help' => __('')]);
		?>
		<h3><?= __('Secours publics présents') ?></h3> 
		<?php
			echo $this->Form->control('secours_presents', ['class' => 'selectpicker','options'=>['SMUR'=>'SMUR','Police'=>'Police','Gendarmerie'=>'Gendarmerie','Peloton de Montagne'=>'Peloton de Montagne','Sapeurs-Pompiers'=>'Sapeurs-Pompiers','Médecin militaire'=>'Médecin militaire','Autres associations agréées'=>'Autres associations agréées'],'multiple'=>true,'help' => __('Décrire les modalités d\'accès pour les véhicules et la circulation sur la manifestation')]);
		?>
		<h3><?= __('Documents administratifs') ?></h3> 
		<?php			
			echo $this->Form->control('documents_officiels', ['class' => 'selectpicker','options'=>['Arrêté préfectoral'=>'Arrêté préfectoral','Arrêté municipal'=>'Arrêté municipal','Rapport de commision'=>'Rapport de commision','Plans'=>'Plans','Annuaire'=>'Annuaire','Liste des participants'=>'Liste des participants'],'multiple'=>true,'help' => __('Décrire les modalités d\'accès pour les véhicules et la circulation sur la manifestation')]);
		?>
		<h3><?= __('Autres secours présents') ?></h3> 
		<?php
            echo $this->Form->control('medecin', ['class' => 'form-control','append'=>'nom et prénom','help' => __('')]);
            echo $this->Form->control('medecin_telephone', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('infirmier', ['class' => 'form-control','help' => __(''),'append'=>'nom et téléphone']);
            echo $this->Form->control('kiné', ['class' => 'form-control','help' => __(''),'append'=>'nom et téléphone']);
            echo $this->Form->control('medecin_autres', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('ambulancier', ['class' => 'form-control','help' => __(''),'append'=>'nom de  la société']);
            echo $this->Form->control('ambulancier_telephone', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('autres_public', ['class' => 'form-control','help' => __('')]);
            echo $this->Form->control('autres', ['class' => 'form-control','help' => __('')]);
        ?>
		</div>
    </fieldset>
    <?= $this->Form->button(__('Soumettre le formulaire'),['class' => 'btn btn-large btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>

<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 0,
		format:'yyyy-mm-dd hh:ii'
    });
</script>

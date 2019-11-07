<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dispositif $dispositif
 */
?>
<?php
$this->prepend( 'script' , $this->Html->script('locales/bootstrap-datetimepicker.fr.js',['charset'=>'UTF8']) );
$this->prepend( 'script' , $this->Html->script('bootstrap-datetimepicker',['charset'=>'UTF8']) );
$this->prepend( 'css' , $this->Html->css('bootstrap-datetimepicker.min',['media'=>'screen']) );
?>
<div class="dispositifs edit content">
	<h1>
	<?= __('Modifier Dispositif') ?>
	<?= $this->element('buttons',['controller'=>'dispositifs','options'=>'index']) ?>
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
    <?= $this->Form->create($dispositif, ['horizontal' => true]) ?>
    <fieldset>

		<div class="col-lg-6 col-md-12 columns">
    	<h2><?= __('Informations') ?></h2>
		<h3><?= __('Général') ?></h3>
        <?php
            echo $this->Form->control('dimensionnement_id', ['options' => $dimensionnements,'class' => 'form-control','help' => __('')]);
            echo $this->Form->control('title', ['label'=>'Titre','class' => 'form-control','help' => __('')]);
		?>
		<h3><?= __('Organisation pour le public') ?></h3>
		<?php
            echo $this->Form->control('config_typepublic_id', ['options' => $configTypepublics,'class' => 'form-control','empty' => true ,'help' => __(''),'label'=>'Type de public']);
            echo $this->Form->control('config_environnement_id', ['options' => $configEnvironnements,'class' => 'form-control','empty' => true ,'help' => __(''),'label'=>'Environnement']);
            echo $this->Form->control('config_delai_id', ['options' => $configDelais,'class' => 'form-control','empty' => true ,'help' => __(''),'label'=>'Délai']);
            echo $this->Form->control('ris', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('recommandation_type', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('recommandation_poste', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('personnels_public', ['class' => 'form-control','help' => __('Le RIS étant de : '.$dispositif->ris.', la recommandation est un dispositif de type '.$dispositif->recommandation_type)]);
		?>
		</div>
		<div class="col-lg-6 col-md-12 columns">
		<h2><?= __('Devis') ?></h2>
		<h3><?= __('Personnel') ?></h3>
		<?php
            echo $this->Form->control('nb_chef_equipe', ['class' => 'form-control','help' => __(''),'label'=>'Nombre de chefs d\'équipe']);
            echo $this->Form->control('nb_pse2', ['class' => 'form-control','help' => __(''),'label'=>'Nombre de PSE2']);
            echo $this->Form->control('nb_pse1', ['class' => 'form-control','help' => __(''),'label'=>'Nombre de PSE1']);
            echo $this->Form->control('nb_lat', ['class' => 'form-control','help' => __(''),'label'=>'Nombre de LAT']);
            echo $this->Form->control('nb_medecin', ['class' => 'form-control','help' => __(''),'label'=>'Nombre de médecins']);
            echo $this->Form->control('nb_infirmier', ['class' => 'form-control','help' => __(''),'label'=>'Nombre d\'infirmier']);
            echo $this->Form->control('nb_cadre_operationnel', ['class' => 'form-control','help' => __(''),'label'=>'Nombre de cadre opérationnel']);
            echo $this->Form->control('nb_stagiaire', ['class' => 'form-control','help' => __(''),'label'=>'Nombre de stagiaires']);
		?>
		<h3><?= __('Matériels') ?></h3>
        <?php
            echo $this->Form->control('lot_a', ['class' => 'form-control','help' => __('Nombre de lot A dans le dispositif'),'legend'=>'test']);
            echo $this->Form->control('lot_b', ['class' => 'form-control','help' => __('Nombre de lot B dans le dispositif'),'legend'=>'test']);
            echo $this->Form->control('lot_c', ['class' => 'form-control','help' => __('Nombre de lot C dans le dispositif'),'legend'=>'test']);
        ?>
        <h3><?= __('Véhicules') ?></h3>
        <?php
            echo $this->Form->control('nb_vpsp', ['class' => 'form-control','help' => __('Nombre de VPSP pour cette équipe')]);
            echo $this->Form->control('nb_vtu', ['class' => 'form-control','help' => __('Nombre de VTU pour cette équipe')]);
            echo $this->Form->control('nb_vtp', ['class' => 'form-control','help' => __('Nombre de VTP pour cette équipe')]);
            echo $this->Form->control('nb_quad', ['class' => 'form-control','help' => __('Nombre de quad pour cette équipe')]);
		?>
		<h3><?= __('Divers') ?></h3>
		<?php
          //  echo $this->Form->control('consignes_generales', ['class' => 'form-control','help' => __('Consignes générales à destination des équipiers qui seront sur ce dispositif. Laisser vide pour appliquer les consignes par défaut.')]);
            echo $this->Form->control('nb_repas', ['label' => 'Nombre de repas','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('nb_hebergement', ['label' => 'Nombre de tentes','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('nb_portatifs', ['label' => 'Nombre de portatifs','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('cout_divers', ['label' => 'Coût divers','class' => 'form-control','help' => __(''),'step'=>'0.01', min=>'0','lang'=>'en']);
            echo $this->Form->control('explication_cout_divers', ['label' => 'Explication du coût supplémentaire','class' => 'form-control','help' => __('Exemple : Structure gonflable, Relais radios, ...')]);
            echo $this->Form->control('nb_kilometres', ['class' => 'form-control','help' => __('Nombre de kilomètres pour le remboursement des secouristes.')]);
            echo $this->Form->control('accord_siege', ['type'=>'hidden','class' => 'form-control','help' => __('')]);
            echo $this->Form->control('numero_coa', ['class' => 'form-control','help' => __('')]);

            //echo $this->Form->control('remise', ['class' => 'form-control','help' => __('En pourcentage, exemple : écrire 10 pour consentir d\'une remise de 10%')]);
        ?>
   		<h3><?= __('Récapitulatif') ?></h3>

        <?php
            echo '<table class="vertical-table table table-striped">';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Coût des véhicules';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_vehicules.'€';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Coût du matériel';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_materiel.'€';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Coût du personnel';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_personnel.'€';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Coût kilomètres';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_kilometres.'€';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Coût tentes';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_hebergement.'€';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Coût repas';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_repas.'€';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Coût divers';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_divers.'€';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Coût total';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_total.'€';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'Remise (en %)';
                    echo '</th>';
                    echo '<td>';
                        echo $this->Form->control('remise', ['label'=>false,'class' => 'form-control','help' => __('')]);
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th scope="row">';
                        echo 'COUT TOTAL (après remise)';
                    echo '</th>';
                    echo '<td>';
                        echo $dispositif->cout_total_remise.'€';
                    echo '</td>';
                echo '</tr>';
            echo '</table>';
        ?>
		<h3><?= __('Enregistrement') ?></h3>
		<?php $this->Form->horizontal = true; ?>
		<?= $this->Form->input('actions',[
			'options'=>['Recalculer','Sortir'],
			'type'=>'select',
			'append' => [ $this->Form->button(__('Soumettre le formulaire'),['class' => 'btn btn-large btn-primary']) ]
		]) ?>
		</div>
    </fieldset>
    <?= $this->Form->end() ?>
</div>

<script type="text/javascript">
	$('#dimenstionnement-id').chosen();
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

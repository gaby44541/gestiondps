<?php
 echo '<table class="vertical-table table table-striped">';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Coût des véhicules';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_vehicules.'€';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Coût du matériel';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_materiel.'€';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Coût du personnel';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_personnel.'€';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Coût kilomètres';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_kilometres.'€';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Coût tentes';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_hebergement.'€';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Coût repas';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_repas.'€';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Coût divers';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_divers.'€';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Coût total';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_total.'€';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'Remise (en %)';
        echo '</th>';
        echo '<td>';
           echo $dimensionnements->dispositif->remise.'%';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<th scope="row">';
            echo 'COUT TOTAL (après remise)';
        echo '</th>';
        echo '<td>';
            echo $dimensionnements->dispositif->cout_total_remise.'€';
        echo '</td>';
    echo '</tr>';
echo '</table>';
?>

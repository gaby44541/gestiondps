<?php if (!empty($demande->dimensionnements)): ?>
<?php foreach ($demande->dimensionnements as $key => $dimensionnements): ?>
<?php
$pdf->AddPage();

$pdf->setCellPaddings(0,0,0,0);
$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', '', 11);

$pdf->Cell(0, 7, __('Annexe n° ') . $key .' - '.$dimensionnements->intitule , 0, 1, 'C',1, '', 0,false,'T','M');

$pdf->SetTextColor(105, 105, 105);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(0, 0, 'Du '.$this->Time->format($dimensionnements->horaires_debut,\IntlDateFormatter::FULL).' au '.$this->Time->format($dimensionnements->horaires_fin,\IntlDateFormatter::FULL), 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0, 'Lieu de rendez-vous :'.$dimensionnements->lieu_manifestation, 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0, 'Contact sur place '.$dimensionnements->contact_present.' - '.$dimensionnements->contact_fonction.' ('.$dimensionnements->contact_portable.' - '.$dimensionnements->contact_telephone.')', 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(240, 240, 240);
$pdf->setCellPaddings(0,0,0,0);
$pdf->MultiCell(100, 5, __('Dimensionnement public'), 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 5, __('Dimensionnement acteurs'), 0, 'C', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->Ln(1);
$pdf->SetFillColor(255,255,255);
$pdf->setCellPaddings(0,0,0,0);
$pdf->SetTextColor(105, 105, 105);

$pdf->MultiCell(100, 0, 'RIS : '.$dimensionnements->dispositif->ris .' - Recommandations :', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, ' ', 0, 'L', 1, 1, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln(1);

$pdf->MultiCell(100, 0, $dimensionnements->dispositif->recommandation_type, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, ' ', 0, 'L', 1, 1, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln(1);

$pdf->MultiCell(100, 0, __('Personnels nécessaires et retenus : ').$dimensionnements->dispositif->personnels_public, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, __('Personnels nécessaires et retenus : ').$dimensionnements->dispositif->personnels_acteurs, 0, 'L', 1, 1, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln(1);

$pdf->SetFillColor(240, 240, 240);
$pdf->setCellPaddings(0,0,0,0);
$pdf->MultiCell(100, 5, __('Organisation du dispositif public :'), 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 5, __('Organisation du dispositif acteurs :'), 0, 'C', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->Ln(1);
$pdf->SetFillColor(255,255,255);
$pdf->setCellPaddings(2,2,2,2);
$c1 = count(explode(PHP_EOL,$dimensionnements->dispositif->organisation_public));
$c2 = count(explode(PHP_EOL,$dimensionnements->dispositif->organisation_acteurs));
if($c1>$c2){
	$c2 = explode(PHP_EOL,$dimensionnements->dispositif->organisation_acteurs);
	$c2 = array_pad($c2,$c1+4,'');
	$dimensionnements->dispositif->organisation_acteurs = implode(PHP_EOL,$c2);
}
$pdf->MultiCell(100, 0, nl2br( $dimensionnements->dispositif->organisation_public ), 0, 'J', 1, 0, '', '', true, 1, true, true, 40, 'T');
$height1 = $pdf->GetY();
$pdf->MultiCell(100, 0, nl2br( $dimensionnements->dispositif->organisation_acteurs ), 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$height2 = $pdf->GetY();
if($height1>$height2){
	$pdf->SetY($height1);
	$pdf->SetLastH($height1);
}
$pdf->Ln(5);

$pdf->SetFillColor(240, 240, 240);
$pdf->setCellPaddings(0,0,0,0);

$pdf->Cell(0, 5, __('Organisation globale : '), 0, 1, 'C',1, '', 0,false,'T','M');

$pdf->SetFillColor(255,255,255);
$pdf->setCellPaddings(2,2,2,2);

$pdf->MultiCell(200, 0, nl2br( $dimensionnements->dispositif->organisation_poste ), 0, 'L', 1, 0, '', '', true, 1, true, true, 40, 'T');
$pdf->Ln();

$pdf->SetFillColor(240, 240, 240);
$pdf->setCellPaddings(0,0,0,0);

$pdf->Cell(0, 5, __('Transports : '), 0, 1, 'C',1, '', 0,false,'T','M');

$pdf->SetFillColor(255,255,255);
$pdf->setCellPaddings(2,2,2,2);

$pdf->MultiCell(200, 0, nl2br( $dimensionnements->dispositif->organisation_transport ), 0, 'L', 1, 0, '', '', true, 1, true, true, 40, 'T');
$pdf->Ln();

$pdf->SetFillColor(240);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(128, 0, 0);
$pdf->SetLineWidth(0.1);
$pdf->SetFont('helvetica', 'B',8);
// Header
$w = array(25,5,25,5,5,5,35,25,20,20,30);
$header = array(
	__('Indicatif'),
	__('Pers.'),
	__('Véhicule'),
	__('A'),
	__('B'),
	__('C'),
	__('Autre'),
	__('Convocation'),
	__('En place'),
	__('Termine à'),
	__('Repas à votre charge')
);
$num_headers = count($header);
for($i = 0; $i < $num_headers; ++$i) {
    $pdf->Cell($w[$i], 7, $header[$i], 0, 0, 'C', 1);
}
$pdf->Ln();
// Color and font restoration
$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(105);
$pdf->SetFont('');
// Data
$fill = 0;
foreach ($dimensionnements->dispositif->equipes as $equipe):
    $pdf->Cell($w[0], 6, $equipe->indicatif, 0, 0, 'L', $fill);
	$pdf->Cell($w[1], 6, $equipe->effectif, 0, 0, 'C', $fill);
	//$pdf->Cell($w[2], 6, $equipe->vehicule_type, 0, 0, 'C', $fill);
	$pdf->Cell($w[3], 6, $equipe->lot_a, 0, 0, 'C', $fill);
	$pdf->Cell($w[4], 6, $equipe->lot_b, 0, 0, 'C', $fill);
	$pdf->Cell($w[5], 6, $equipe->lot_c, 0, 0, 'C', $fill);
    $pdf->Cell($w[6], 6, $equipe->autre, 0, 0, 'C', $fill);
    $pdf->Cell($w[7], 6, $this->Time->format($equipe->horaires_convocation,'dd-MM-yyyy HH:mm'), 0, 0, 'C', $fill);
    $pdf->Cell($w[8], 6, $this->Time->format($equipe->horaires_place,'dd-MM HH:mm'), 0, 0, 'C', $fill);
	$pdf->Cell($w[9], 6, $this->Time->format($equipe->horaires_fin,'dd-MM HH:mm'), 0, 0, 'C', $fill);
	$pdf->Cell($w[10], 6, ($equipe->repas_charge * $equipe->repas_matin).' Mat. '.($equipe->repas_charge * $equipe->repas_midi).' Mid. '.($equipe->repas_charge * $equipe->repas_soir).' Soir', 0, 0, 'R', $fill);
    $pdf->Ln();
	$fill=!$fill;
endforeach;
$pdf->Cell(array_sum($w), 0, '', 0);
?>
<?php if (isset($consignes)): ?>
<?php if ($consignes): ?>
<?php
$pdf->AddPage();

$pdf->setCellPaddings(0,0,0,0);
$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', '', 11);

$pdf->Cell(0, 7, __('Annexe n° ') . $key .' - Consignes aux équipes - '.$dimensionnements->intitule , 0, 1, 'C',1, '', 0,false,'T','M');

$pdf->SetTextColor(105, 105, 105);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(0, 0, 'Du '.$this->Time->format($dimensionnements->horaires_debut,\IntlDateFormatter::FULL).' au '.$this->Time->format($dimensionnements->horaires_fin,\IntlDateFormatter::FULL), 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);

$pdf->Ln();

$pdf->SetFillColor(240, 240, 240);
$pdf->setCellPaddings(0,0,0,0);

$pdf->Cell(0, 5, __('Consignes générales à destination des équipiers'), 0, 1, 'C',1, '', 0,false,'T','M');

$pdf->SetFillColor(255,255,255);
$pdf->setCellPaddings(2,2,2,2);

$pdf->MultiCell(200, 0, nl2br( $dimensionnements->dispositif->consignes_generales ), 0, 'L', 1, 1, '', '', true, 1, true, true, 40, 'T');
$pdf->Ln();

$pdf->SetFillColor(240);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(128, 0, 0);
$pdf->SetLineWidth(0.1);
$pdf->SetFont('helvetica', 'B',8);
// Header
$w = array(25,5,25,5,5,5,35,25,20,20,30);
$header = array(
	__('Indicatif'),
	__('Pers.'),
	__('Véhicule'),
	__('A'),
	__('B'),
	__('C'),
	__('Autre'),
	__('Convocation'),
	__('En place'),
	__('Termine à'),
	__('Repas organisateur')
);
$num_headers = count($header);
for($i = 0; $i < $num_headers; ++$i) {
    $pdf->Cell($w[$i], 7, $header[$i], 0, 0, 'C', 1);
}
$pdf->Ln();
// Color and font restoration
$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(105);
$pdf->SetFont('');
// Data
$fill = 0;
foreach ($dimensionnements->dispositif->equipes as $equipe):
    $pdf->Cell($w[0], 6, $equipe->indicatif, 0, 0, 'L', $fill);
	$pdf->Cell($w[1], 6, $equipe->effectif, 0, 0, 'C', $fill);
	//$pdf->Cell($w[2], 6, $equipe->vehicule_type, 0, 0, 'C', $fill);
	$pdf->Cell($w[3], 6, $equipe->lot_a, 0, 0, 'C', $fill);
	$pdf->Cell($w[4], 6, $equipe->lot_b, 0, 0, 'C', $fill);
	$pdf->Cell($w[5], 6, $equipe->lot_c, 0, 0, 'C', $fill);
    $pdf->Cell($w[6], 6, $equipe->autre, 0, 0, 'C', $fill);
    $pdf->Cell($w[7], 6, $this->Time->format($equipe->horaires_convocation,'dd-MM-yyyy hh:mm'), 0, 0, 'C', $fill);
    $pdf->Cell($w[8], 6, $this->Time->format($equipe->horaires_place,'dd-MM hh:mm'), 0, 0, 'C', $fill);
	$pdf->Cell($w[9], 6, $this->Time->format($equipe->horaires_fin,'dd-MM hh:mm'), 0, 0, 'C', $fill);
	$pdf->Cell($w[10], 6, ($equipe->repas_charge * $equipe->repas_matin).' Mat. '.($equipe->repas_charge * $equipe->repas_midi).' Mid. '.($equipe->repas_charge * $equipe->repas_soir).' Soir', 0, 0, 'R', $fill);
    $pdf->Ln();
	$pdf->MultiCell(200, 0, __('Consignes spécifiques à l\'équipe : ').nl2br( $equipe->consignes ), 0, 'L', $fill, 1, '', '', true, 1, true, true, 40, 'T');
	$fill=!$fill;
endforeach;
$pdf->Cell(array_sum($w), 0, '', 0);
?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

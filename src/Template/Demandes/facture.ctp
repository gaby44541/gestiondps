<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Demande $demande
 */

class xtcpdf extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 5, 5, 25, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		$this->ImageSVG(WWW_ROOT . 'img/Protection_Civile_-_Logo_2017.svg', $x=7, $y=7, $w=20, $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);

        $this->SetX(25);
		$this->SetY(5);
		$this->SetXY(30,13);
		// Set font
        $this->SetFont('helvetica', 'B', 19);
		$this->SetTextColor(0, 0, 128);
        // Title
        $this->Cell(0, 11, 'PROTECTION CIVILE', 0, true, 'L', 0, '', 0, false, 'M', 'M');
		$this->SetX(30);
		$this->SetFont('helvetica', 'B', 13);
		$this->SetTextColor(255, 127, 0);
		$this->Cell(0, 11, 'AIDER - SECOURIR - FORMER', 0, true, 'L', 0, '', 0, false, 'M', 'M');
		$this->SetX(30);
		$this->SetTextColor(0, 0, 128);
		$this->Cell(0, 11, '| LOIRE-ATLANTIQUE', 0, true, 'L', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-12);
		$this->Line(0, 283, 210, 283, array('width' => 0.2, 'color' => array(255, 127, 0)));
        // Set font
        $this->SetFont('helvetica', '', 6);
		$this->SetTextColor(0, 0, 128);
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 0,'Protection Civile de Loire-Atlantique - ADPC 44 - 8 Rue Paul Beaupère, 44300 Nantes, France', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 0,'Tél : 02 40 47 87 34 - Email: loire-atlantique@protection-civile.org - Site Internet : protection-civile44.fr', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 0,'Association régie par la loi de 1901 - Membre de la Fédération Nationale de Protection Civile - Association agréée de sécurité civile - Reconnue d’utilité publique ', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
    }

}


$pdf = new xtcpdf('P', 'MM', 'A4', true, 'UTF-8', false);
// set document information
$pdf->SetCreator('Protection Civile');
$pdf->SetAuthor('Protection Civile');
$pdf->SetTitle('Facture');
$pdf->SetSubject('Facture de DPS');
$pdf->SetKeywords('DPS, Facture');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 004', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5,30,5,15);
$pdf->SetHeaderMargin(30);
$pdf->SetFooterMargin(15);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 17);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// add a page
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 12);

$pdf->SetLineStyle(array('width' => 0.1));

$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(0);

$pdf->Cell(0, 10, __('DISPOSITIF PREVISIONNEL DE SECOURS - FACTURE N° ') . $demande->id ._(' du ').date('d/m/Y'), 0, 1, 'C',1, '', 0,false,'T','M');
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);


$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(100, 0, __('Représentant légal :'), 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(100, 0, $demande->organisateur->nom, 0, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(100, 0, $demande->organisateur->representant_nom.' '.$demande->organisateur->representant_prenom.' - '.$demande->organisateur->fonction, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(100, 0, $demande->organisateur->adresse, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(100, 0, $demande->organisateur->telephone .' '.$demande->organisateur->portable, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(100, 0, $demande->organisateur->adresse_suite, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(100, 0, $demande->organisateur->mail, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(100, 0, $demande->organisateur->code_postal.' '.$demande->organisateur->ville, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(0);
$pdf->MultiCell(100, 0, __('En charge des modalités financières :'), 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetTextColor(105);
$pdf->MultiCell(100, 0, '', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();

$pdf->MultiCell(100, 0, $demande->organisateur->tresorier_nom.' '.$demande->organisateur->tresorier_prenom.' - '.$demande->organisateur->tresorier_telephone , 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, '', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();

$pdf->MultiCell(100, 0, $demande->organisateur->tresorier_mail, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, '', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');

$pdf->Ln();
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);
$pdf->Cell(0, 8, 'La facture concerne les éléments suivants', 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 8, 'Evénement : '.$demande->manifestation, 0, 1, 'L', 0, '', 0);
$pdf->Cell(0, 8, 'Détaillé comme suit : ', 0, 1, 'L', 0, '', 0);
$pdf->SetTextColor(105);
$pdf->SetFont('helvetica', '', 8);
foreach ($demande->dimensionnements as $dimensionnements):
$txt = $dimensionnements->intitule.', du '.$this->Time->format($dimensionnements->horaires_debut,\IntlDateFormatter::FULL).' au '.$this->Time->format($dimensionnements->horaires_fin,\IntlDateFormatter::FULL);
$pdf->Cell(0, 0, $txt, 0, 1, 'L', 0, '', 0);
endforeach;

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);
$pdf->Cell(0, 8, 'Faisant l\'objet d\'une convention signée par vos soins fixant le dimensionnement suivant :', 0, 1, 'L', 0, '', 0);
$pdf->setCellPaddings(10,0,0,0);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(255, 0, 0);
$pdf->MultiCell(200, 0, $demande->total_personnel .' personnels bénévoles présents sur la manifestation en vue d\'assurer le dispositif', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->MultiCell(200, 0, $demande->total_vehicules .' véhicules équipés des matériels exigés par le RNMSC DPS', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->MultiCell(200, 0, $demande->total_lota .' lot de type A - '.$demande->total_lotb .' lot de type B - '.$demande->total_lotc .' lot de type C (lots normalisés selon le RNMSC DPS)', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->MultiCell(200, 0, 'Réseau radio sur fréquence dédiée par l\'état', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->SetTextColor(0);
//$pdf->MultiCell(200, 0, $demande->total_duree .' heures de présence cumulées par nos bénévoles', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);
$pdf->setCellPaddings(0,0,0,0);
$pdf->MultiCell(200, 0, 'Valeur de la prestation : '.$this->Number->format( $demande->total_cout ) .' €', 0, 'L', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->SetTextColor(105);
$pdf->SetFont('helvetica', '', 8);
$pdf->setCellPaddings(10,0,0,0);
$pdf->MultiCell(200, 0, 'Nous sommes une association composée entièrement de bénévoles. Cette contrepartie financière nous sert à couvrir les frais engagés pour la mission, acheter et renouveler le matériel pour être en conformité avec les impositions légales et le cahier des charges qui nous est imposé par l\'état, former nos équipes.
Contrairement aux idées reçues, nous ne percevons pas de subvention de l\'état. Les aides locales (conseil départemental et mairies) représentent en moyenne 2% de notre budget annuel moyen pour répondre au cahier des charges du Ministère de l\'Intérieur.
', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->setCellPaddings(0,0,0,0);
if($demande->total_cout != $demande->total_remise){
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(200, 0, 'Montant facturé : '.$this->Number->format( $demande->total_remise ) .' €', 0, 'L', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->SetTextColor(105);
$pdf->SetFont('helvetica', '', 8);
$pdf->setCellPaddings(10,0,0,0);
$pdf->MultiCell(200, 0, 'Le dispositif dimensionné vous aurait normalement coûté '. $this->Number->format( $demande->total_cout ).' €, toutefois nous avons choisis de vous le facturer '.$this->Number->format( $demande->total_remise ).' €.', 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');
$pdf->SetTextColor(0);
$pdf->setCellPaddings(0,0,0,0);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 8, 'Pour les raisons suivantes :', 0, 1, 'L', 0, '', 0);
$pdf->SetTextColor(105);
$pdf->SetFont('helvetica', '', 8);
$pdf->setCellPaddings(10,0,0,0);
$pdf->MultiCell(200, 0, nl2br($demande->remise_justification), 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
}
$pdf->setCellPaddings(0,0,0,0);
$pdf->SetFont('helvetica', 'B', 12);

$pdf->SetLineStyle(array('width' => 0.1));

$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(0);

$pdf->Cell(0, 10, __('Total à règler : ') . $this->Number->format( $demande->total_remise ) .' € ', 0, 1, 'R',1, '', 0,false,'T','M');
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFillColor(255);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(255, 0, 0);
$pdf->MultiCell(200, 0, '<b>Attention :</b> suite à divers impayés de la part d\'organisateurs, nous vous rappelons que le réglement doit s\'effectuer sous 7 jours. Passé ce délai, vous vous exposez à une majoration de 10% du montant total et par semaine de retard. En cas de non paiement, nous transmettrons le dossier auprès de notre autorité ministérielle de tutelle.', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->Ln(0);

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);
$pdf->MultiCell(200, 0, 'Règlement par virement :',0, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->SetTextColor(105);
$pdf->MultiCell(25, 0, 'Etablissement' ,0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(50, 0, $demande->antenne->rib_etablissement,0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(15, 0, 'Guichet',0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(45, 0, $demande->antenne->rib_guichet,0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(15, 0, 'Compte' ,0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(50, 0, $demande->antenne->rib_compte,0, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->Ln(0);
$pdf->MultiCell(15, 0, 'RICE',0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(50, 0, $demande->antenne->rib_rice,0, 'L', 1, 00, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(25, 0, 'Domicile' ,0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(50, 0, $demande->antenne->rib_domicile,0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(15, 0, 'BIC',0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(50, 0, $demande->antenne->rib_bic,0, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->Ln(0);
$pdf->MultiCell(50, 0, 'IBAN' ,0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(150, 0, $demande->antenne->rib_iban,0, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');

$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(200, 0, 'Dans le cas ou vous ne pourriez règler par virement, il vous est encore possible de nous envoyer un chèque complété à l\'ordre de '.$demande->antenne->cheque.' et à envoyer par courrier à  : '.$demande->antenne->antenne.' '.$demande->antenne->adresse.' '.$demande->antenne->antenne_suite.' '.$demande->antenne->code_postal.' '.$demande->antenne->ville,0, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->SetTextColor(0);

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->setCellPaddings(100,0,0,0);
$pdf->MultiCell(200, 0, __('Fait à ').$demande->antenne->ville.__(', le ').date('d/m/Y'), 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(200, 0, __('Pour la Protection Civile et par délégation,'), 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(200, 0, $demande->gestionnaire_nom, 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(200, 0, $demande->gestionnaire_telephone, 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(200, 0, $demande->gestionnaire_mail, 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');

//$this->element('tcpdfannexes',['demande'=>$demande,'pdf'=>$pdf]);

//Close and output PDF document
$pdf->Output('example_012.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>

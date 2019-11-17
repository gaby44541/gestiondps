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
		$this->ImageSVG('https://upload.wikimedia.org/wikipedia/commons/3/3f/Protection_Civile_-_Logo_2017.svg', $x=7, $y=7, $w=20, $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);

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
$pdf->SetTitle('Etude');
$pdf->SetSubject('Etude de DPS');
$pdf->SetKeywords('DPS, Etude');

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
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell(0, 10, __('DISPOSITIF PREVISIONNEL DE SECOURS - ETUDE N° ') . $demande->id, 0, 1, 'C',1, '', 0,false,'T','M');
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255, 255, 255);

$pdf->MultiCell(100, 0, __('Manifestation'), 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 0, __('Organisée par'), 0, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105, 105, 105);

$pdf->MultiCell(100, 0, $demande->manifestation, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->nom, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, count($demande->dimensionnements) . __(' épreuve(s) ou journée(s) sont déclarées en vue du dimensionnement'), 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->adresse, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();

$pdf->MultiCell(100, 0, ' ', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->adresse_suite, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(0);
$pdf->MultiCell(100, 0, __('Demande effectuée par :'), 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetTextColor(105);
$pdf->MultiCell(100, 0, $demande->organisateur->cp_ville, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();

$pdf->MultiCell(100, 0, $demande->representant.' - '.$demande->representant_fonction, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->telephone.' '.$demande->organisateur->portable, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();

$pdf->MultiCell(100, 0, ' ', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->mail, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(0);
$pdf->MultiCell(100, 0, 'Personnel Protection Civile en charge de votre dossier :', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, 'Représenté par :', 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->SetTextColor(105, 105, 105);
$pdf->MultiCell(100, 0, $demande->gestionnaire_nom.' - '.$demande->gestionnaire_telephone, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->representant_fonction, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->SetTextColor(105, 105, 105);
$pdf->MultiCell(100, 0, $demande->gestionnaire_mail, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, '', 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->antenne->antenne, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetTextColor(0);
$pdf->MultiCell(100, 0, __('Personne en charge des modalités financières :'), 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->SetTextColor(105);
$pdf->MultiCell(100, 0, $demande->antenne->adresse, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->tresorier, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->antenne->adresse_suite, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->tresorier_telephone, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->antenne->cp_ville, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->tresorier_mail, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);
$pdf->Cell(0, 8, 'Synthèse de l\'étude', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(200, 0, 'La présente étude a été dimensionnée sur la base de vos déclarations et conformément à la règlementation en vigueur pour les associations agréées de Sécurité Civile, en tenant compte d\'éventuelles de fédérations le cas échéant, mais surtout en tenant compte des caractéristiques de votre manifestation et des problématiques attenantes.', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->Ln(0);
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(30, 0, $demande->total_personnel .' personnels', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(170, 0, 'Nos personnels sont tous à jour de leurs obligations règlementaires en terme de formation annuelle obligatoire. Ils sont identifiables par leur tenue et placés sous l\'autorité exclusive de leur chef de dispositif : engagement, positionnement, moyens, ...', 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(30, 0, $demande->total_vehicules.' véhicules', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(170, 0, 'Nos véhicules sont équipés au delà de la réglementation afin de pouvoir assurer nos missions avec professionnalisme. Des accès pour ces véhicules doivent être prévu par vos soins. Le gabarit maximal à prendre en compte est un L3H3.', 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(30, 0, 'Transports', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(170, 0, 'Les modalités de transport par nos soins vers une structure hospitalière effectués sont précisées dans le détails de chaque dimensionnement ci-après. Les vecteurs de transport proposés sont toujours en sus de la règlementation des dispositifs prévisionnels de secours et sont permis par une convention tripartite entre la Protection Civile, le SDIS et le SAMU.' , 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(30, 0, 'A votre charge', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
//$pdf->MultiCell(170, 0, 'Matin : '.$demande->total_repas_matin.' repas -- Midi : '.$demande->total_repas_midi.' repas (prévoir 2 sans porc) -- Soir :'.$demande->total_repas_soir.' repas (prévoir 2 sans porc)
Vous trouverez en annexe le détails, par journée et par équipe, des repas à prévoir. Suite à quelques organisateurs indélicats nous vous informons que toute difficulté rencontrée sur site au sujet des repas entraînera une majoration de la facturation par la suite afin de couvrir les frais supplémentaires engendrés.
' , 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
if($demande->total_cout != $demande->total_remise){
$pdf->MultiCell(40, 0, 'Valeur de la prestation : '.$this->Number->format( $demande->total_cout ) .' €', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
} else {
$pdf->MultiCell(40, 0, 'Facturation de la prestation : '.$this->Number->format( $demande->total_cout ) .' €', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');

}
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(160, 0, 'Nous sommes une association composée entièrement de bénévoles. Cette contrepartie financière nous sert à couvrir les frais engagés pour la mission, acheter et renouveler le matériel pour être en conformité avec les impositions légales et le cahier des charges qui nous est imposé par l\'état, former nos équipes.

Contrairement aux idées reçues, nous ne percevons pas de subvention de l\'état. Les aides locales (mairies) représentent 2% de notre budget annuel moyen pour répondre au cahier des charges du Ministère de l\'Intérieur.
' , 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

if($demande->total_cout != $demande->total_remise){
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(40, 0, 'Facturation de la prestation : '.$this->Number->format( $demande->total_remise ) .' €', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(160, 0, 'Le dispositif dimensionné vous aurait normalement coûté '. $this->Number->format( $demande->total_cout ).' €, toutefois vous serez facturé de '.$this->Number->format( $demande->total_remise ).' €. Cette adaptation du tarif est entre autre motivée par :
'.$demande->remise_justification.PHP_EOL.'
', 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');
}
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(255, 0, 0);
$pdf->MultiCell(200, 0, '<b>Attention :</b> Ceci est une étude et donc en aucun cas un engagement ferme et définitif. Seule une convention établie en bonne dûe forme peut engager notre structure. Afin de vous permettre d\'obtenir cette convention, merci de nous retourner dans les plus brefs délais ce document signé.<br/><br/>
En l\'absence de retour dans un délai de 7 jours, ou si votre dossier n\'est pas complet, nous nous réservons le droit de faire passer prioritairement tout autre dossier complété avant le vôtre.<br/><br/>
Merci  de  lire  l\'intégralité  du  document,  de  parapher  chaque  page, y compris des '.count($demande->dimensionnements).' annexes jointes,  et  de signer  en  bas  de  ce  dernier  comme  indiqué.  Si  les  pages  sont  non  paraphées,  votre  dossier ne pourra être pris en compte.', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->Ln(0);

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(0);
$pdf->MultiCell(100, 0, __('Retourner les documents signés à :'), 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 0, __('Date, cachet, signature et mention manuscrite "bon pour accord".'), 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105, 105, 105);

$pdf->MultiCell(100, 0, $demande->gestionnaire_mail . ' ou ', 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 0, '', 0, 'C', 1, 1, '', '', true, 0, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->antenne->coordonnees, 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 0, '', 0, 'C', 1, 1, '', '', true, 0, false, true, 40, 'T');

$this->element('tcpdfannexes',['demande'=>$demande,'pdf'=>$pdf]);

//Close and output PDF document
$pdf->Output('etude.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>

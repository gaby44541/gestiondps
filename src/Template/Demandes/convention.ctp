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
$pdf->SetTitle('Convention');
$pdf->SetSubject('Convention de DPS');
$pdf->SetKeywords('DPS, Convention');

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

$pdf->Cell(0, 10, __('DISPOSITIF PREVISIONNEL DE SECOURS - CONVENTION N° ') . $demande->id, 0, 1, 'C',1, '', 0,false,'T','M');
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(255, 255, 255);

$pdf->MultiCell(100, 0, __('Entre d\'une part, la Protection Civile'), 0, 'C', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 0, __('Et d\'autre part, l\'Organisateur'), 0, 'C', 1, 1, '', '', true, 0, false, true, 40, 'T');

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105, 105, 105);

$pdf->MultiCell(100, 0, $demande->antenne->antenne, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->nom, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 5, $demande->antenne->adresse, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 5, $demande->organisateur->adresse, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');

$pdf->MultiCell(100, 5, $demande->antenne->adresse_suite, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 5, $demande->organisateur->adresse_suite, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();

$pdf->MultiCell(100, 5, $demande->antenne->code_postal.' '.$demande->antenne->ville, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 5, $demande->organisateur->code_postal.' '.$demande->organisateur->ville, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->Ln();


$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', 'B', 8);
$pdf->MultiCell(100, 0, 'Représentée par son président départemental', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, 'Représenté par :', 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(100, 0, 'donnant délégation à :', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->representant_nom . ' '.$demande->organisateur->representant_prenom.' - '.$demande->organisateur->fonction, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->gestionnaire_nom.' - '.$demande->gestionnaire_telephone, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->telephone .' - '.$demande->organisateur->portable, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->gestionnaire_mail, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->mail, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

foreach( $convention as $article ):
	$pdf->SetFont('helvetica', '', 10);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->Cell(0, 8, 'Article '.$article->ordre .' - '.$article->designation, 0, 1, 'C', 0, '', 0);
	$pdf->SetFont('helvetica', '', 8);
	$pdf->SetTextColor(105, 105, 105);
	$pdf->MultiCell(200, 0, nl2br( $article->description ), 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
	$pdf->Ln(0);
endforeach;

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, 'Détails de la manifestation et liste des annexes', 0, 1, 'C', 0, '', 0);

foreach ($demande->dimensionnements as $key => $dimensionnements):

$txt = 'Annexe n°'.$key.' : '.$dimensionnements->intitule.', du '.$this->Time->format($dimensionnements->horaires_debut,\IntlDateFormatter::FULL).' au '.$this->Time->format($dimensionnements->horaires_fin,\IntlDateFormatter::FULL);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105, 105, 105);
$pdf->Cell(0, 0, $txt, 0, 1, 'L', 0, '', 0);

endforeach;

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->MultiCell(100, 0, __('Pour la Protection Civile'), 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 0, __('Pour l\'Organisation'), 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105, 105, 105);

$pdf->MultiCell(100, 5, __('Fait à _____________________, le __ / __ / ____'), 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 5, __('Fait à _____________________, le __ / __ / ____'), 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');

$pdf->MultiCell(100, 0, __('Nom, prénom, fonction et signature'), 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
$pdf->MultiCell(100, 0, __('Nom, prénom, fonction, cachet et signature'), 0, 'R', 1, 1, '', '', true, 0, false, true, 40, 'T');


$this->element('tcpdfannexes',['demande'=>$demande,'pdf'=>$pdf]);

//Close and output PDF document
$pdf->Output('example_012.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>

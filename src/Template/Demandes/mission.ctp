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
$pdf->SetTitle('Ordre de mission');
$pdf->SetSubject('Ordre de mission de DPS');
$pdf->SetKeywords('DPS, Ordre de mission');

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

$pdf->Cell(0, 10, __('DISPOSITIF PREVISIONNEL DE SECOURS - ORDRE DE MISSION N° ') . $demande->id, 0, 1, 'C',1, '', 0,false,'T','M');
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
$pdf->MultiCell(100, 0, $demande->organisateur->code_postal.' '.$demande->organisateur->ville, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
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
$pdf->MultiCell(100, 0, $demande->organisateur->representant_nom . ' '.$demande->organisateur->representant_prenom.' - '.$demande->organisateur->fonction, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->SetTextColor(105, 105, 105);
$pdf->MultiCell(100, 0, $demande->gestionnaire_mail, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, '', 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->antenne->antenne, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetTextColor(0);
$pdf->MultiCell(100, 0, __('Personne en charge des modalités financières :'), 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->SetTextColor(105);
$pdf->MultiCell(100, 0, $demande->antenne->adresse, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->tresorier_nom.' '.$demande->organisateur->tresorier_prenom, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->antenne->adresse_suite, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->tresorier_telephone, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->MultiCell(100, 0, $demande->antenne->code_postal.' '.$demande->antenne->ville, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->MultiCell(100, 0, $demande->organisateur->tresorier_mail, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);
$pdf->Cell(0, 8, 'Descriptif général du dispositif', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(30, 0, $demande->total_personnel .' personnels', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(170, 0, 'Les équipes seront constituées sur le terrain par le chef de dispositif en fonction des qualifications, de l\'expérience et des besoins de dernières minutes constatés sur la manifestation.

Si vous avez la moindre question sur le dispositif, veuillez contacter le chef de dispositif, ou à défaut, la personne en charge du dossier de l\'équipe opérationnel.
', 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(30, 0, $demande->total_vehicules.' véhicules', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(170, 0, 'Lors de votre arrivée au point de rendez-vous, chaque équipier doit prendre obligatoirement connaissance du véhicule et matériel le composant.

Chaque équipier est le garant de la bonne utilisation du véhicule et du matériel à son bord. Nous vous rappelons que le chauffeur doit avoir ses papiers sur lui et est pénalement et civilement responsable en cas de problématique.

Les avrtisseurs sonores et lumineux ne sont utilisés qu\'en fonction des instructions du chef de dispositif.
', 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(30, 0, 'Transports', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(170, 0, 'Les modalités de transport vers une structure hospitalière sont précisées dans le détails de chaque dimensionnement ci-après. 
Par défaut nous n\'effectuons aucun transport vers structure hospitalière, sauf cas particulier. Si un transport doit être effectué hors du cadre de la convention, le cadre d\'astreinte doit être immédiatement avisé.

Respectez scrupuleusement les consignes inscrites dans les annexes.
' , 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(30, 0, 'A charge de l\'organisateur', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(105);
$pdf->MultiCell(170, 0, 'Matin : '.$demande->total_repas_matin.' repas -- Midi : '.$demande->total_repas_midi.' repas (dont 2 sans porc) -- Soir : '.$demande->total_repas_soir.' repas (dont 2 sans porc)

En cas de problématique avec les repas, en aviser immédiatement le cadre d\'astreinte ou le chef de dispositif.
' , 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0);
$pdf->Cell(0, 8, 'Consignes générales', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(255, 0, 0);
$pdf->MultiCell(200, 0, '<b>Attention :</b> Chaque équipe ou dispositif peut avoir des consignes différentes. Merci de vous référer aux consignes indiquées dans les annexes. A la lecture des documents, en cas de doute ou questionnement, veuillez contacter votre chef de dispositif.', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->Ln(0);

$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(255, 0, 0);
$pdf->MultiCell(200, 0, '<b>Nous vous rappelons que vous devez impérativement lire le présent ordre de mission pour comprendre le dispositif dans lequel vous allez vous inscrire, et pouvoir poser les questions avant le dispositif au chef de dispositif ou au directeur opérationnel en charge du dossier.</b>', 0, 'J', 1, 1, '', '', true,1, true, true, 40, 'T');
$pdf->Ln(0);

$this->element('tcpdfannexes',['demande'=>$demande,'pdf'=>$pdf,'consignes'=>true]);

//Close and output PDF document
$pdf->Output('example_012.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>

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
		$this->Line(0, 283, 420, 283, array('width' => 0.2, 'color' => array(255, 127, 0)));
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


$pdf = new xtcpdf('L', 'MM', 'A3', true, 'UTF-8', false);
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

$pdf->Cell(0, 10, __('DISPOSITIF PREVISIONNEL DE SECOURS - PLANNING N° ') . $demande->id, 0, 1, 'C',1, '', 0,false,'T','M');
$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

//$this->element('tcpdfannexes',['demande'=>$demande,'pdf'=>$pdf,'consignes'=>true]);
	$this->TcpdfGantt->setConfig('datas.conditions',['where'=>['Dimensionnements.demande_id'=>$demande_id],
															'contain' => ['Dispositifs.Dimensionnements'],
															'order'=>['dispositif_id'=>'asc','horaires_convocation'=>'asc']]);
	$this->TcpdfGantt->setConfig('limits',0);
	$this->TcpdfGantt->getLimits(['demande_id'=>$demande_id]);
	
	$datas = $this->TcpdfGantt->getDatas(); 
	$datas = $this->TcpdfGantt->formatDatas($datas); 
	
	$this->TcpdfGantt->setPdf($pdf);
	
	$pas = $this->TcpdfGantt->getPas();
	$start = $this->TcpdfGantt->getStart();

	$pdf->SetFont('helvetica', '', 10);
	$pdf->SetFillColor(255, 255, 255);
	
	$limits = $this->TcpdfGantt->displayLimits();
	
	$pdf->Text(5, 40, date('d-m-Y H:i',strtotime($limits['mini'])));
	$pdf->Text(385, 40, date('d-m-Y H:i',strtotime($limits['maxi'])));
	
		$max = 410 / $pas;
		
		for($i=0;$i<=$max;$i++){
			$marge = ($i * $pas)+5;
			if(($i % 12) == 0){
				$color = array(0);
			} else{
				$color = array(224);
			}
			$pdf->Line($marge,50,$marge,280, array('width' => 0.1,'color' => $color));	
			$pdf->Text($marge-3,45,$start%24);
			if($start%24==0){
				$pdf->Line($marge,40,$marge,45, array('width' => 0.1,'color' => $color));
			}
			$start++;
		}

	$y = 40;

	//$pdf->MultiCell(100, 0, __('Manifestation'), 0, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
	//$pdf->MultiCell(100, 0, __('Organisée par'), 0, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');
	
	$pdf->SetX(0);
	
	foreach($datas as $data){
		$hauteur = 20;
		$y = $y + 10;
		$pdf->SetY($y);

		$pdf->Rect($data['positions']['absice']['aller'], $y, $data['positions']['length']['aller'], 5, 'F', array(), array(255, 0, 0));
		$pdf->Rect($data['positions']['absice']['poste'], $y, $data['positions']['length']['poste'], 5, 'F', array(), array(255, 255, 0));
		$pdf->Rect($data['positions']['absice']['retour'], $y, $data['positions']['length']['retour'], 5, 'F', array(), array(255, 0, 0));
		$pdf->Text($data['positions']['absice']['aller'], $y+5, $data['data']->indicatif);
	}
	


//Close and output PDF document
$pdf->Output('example_012.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>

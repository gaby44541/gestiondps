<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Inflector;
use Cake\Utility\Hash;
use Cake\I18n\Number;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use TCPDF;

class xtcpdf extends TCPDF {

	//Page header
	public function Header() {
		// Logo
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

/**
 * This component is used to handle automatic model data pagination. The primary way to use this
 * component is to call the paginate() method. There is a convenience wrapper on Controller as well.
 *
 * ### Configuring pagination
 *
 * You configure pagination when calling paginate(). See that method for more details.
 *
 * @link https://book.cakephp.org/3.0/en/controllers/components/pagination.html
 */
class XtcpdfComponent extends Component
{

    /**
     * Default pagination settings.
     *
     * When calling paginate() these settings will be merged with the configuration
     * you provide.
     *
     * - `maxLimit` - The maximum limit users can choose to view. Defaults to 100
     * - `limit` - The initial number of items per page. Defaults to 20.
     * - `page` - The starting page, defaults to 1.
     * - `whitelist` - A list of parameters users are allowed to set using request
     *   parameters. Modifying this list will allow users to have more influence
     *   over pagination, be careful with what you permit.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'paths' =>  [],
		'devise' => false,
		'round' => 2
    ];

    /**
     * Datasource paginator instance.
     *
     * @var \Cake\Datasource\Paginator
     */
    protected $_array = [];

	   /**
     * Constructor
     *
     * @param \Cake\Controller\ComponentRegistry $registry A ComponentRegistry for this component
     * @param array $config Array of config.
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);

    }

	public function getStart($orientation = 'P', $mesure='MM',$taille='A4',$option1=true,$encodage='UTF-8',$option2=false){

		return new xtcpdf($orientation, $mesure, $taille, $option1, $encodage, $option2);

	}

	public function getAttachement($pdf, $base64 = false) {
		ob_start();
		$pdf->Output('file.pdf', 'I');
		$pdf_data = ob_get_contents();
		ob_end_clean();

		return ($base64) ? base64_encode($pdf_data) : $pdf_data;
	}

	public function getMission($demande,$pdf=false,$out=false,$name='etude.pdf',$output='I'){

		if(!$pdf){
			$pdf = $this->getStart();
		}

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

		$this->getAnnexes($demande,$pdf,true,false);

		//Close and output PDF document
		if($out){
			$pdf->Output($name,$output);
		}

		return $pdf;
	}

	public function getEtude($demande,$pdf=false,$out=false,$name='etude.pdf',$output='I'){

		if(!$pdf){
			$pdf = $this->getStart();
		}

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
		$pdf->MultiCell(170, 0, 'Matin : '.$demande->total_repas_matin.' repas -- Midi : '.$demande->total_repas_midi.' repas (prévoir 2 sans porc) -- Soir :'.$demande->total_repas_soir.' repas (prévoir 2 sans porc)
		Vous trouverez en annexe le détails, par journée et par équipe, des repas à prévoir. Suite à quelques organisateurs indélicats nous vous informons que toute difficulté rencontrée sur site au sujet des repas entraînera une majoration de la facturation par la suite afin de couvrir les frais supplémentaires engendrés.
		' , 0, 'J', 1, 1, '', '', true,1, false, true, 40, 'T');

		$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);
		$pdf->SetTextColor(0);
		$pdf->SetFont('helvetica', '', 10);
		if($demande->total_cout != $demande->total_remise){
		$pdf->MultiCell(40, 0, 'Valeur de la prestation : '.Number::format( $demande->total_cout ) .' €', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
		} else {
		$pdf->MultiCell(40, 0, 'Facturation de la prestation : '.Number::format( $demande->total_cout ) .' €', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
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
		$pdf->MultiCell(40, 0, 'Facturation de la prestation : '.Number::format( $demande->total_remise ) .' €', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetTextColor(105);
		$pdf->MultiCell(160, 0, 'Le dispositif dimensionné vous aurait normalement coûté '. Number::format( $demande->total_cout ).' €, toutefois vous serez facturé de '.Number::format( $demande->total_remise ).' €. Cette adaptation du tarif est entre autre motivée par :
		'.nl2br($demande->remise_justification).'
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

		$this->getAnnexes($demande,$pdf,false,false);

		//Close and output PDF document
		if($out){
			$pdf->Output($name,$output);
		}

		return $pdf;
	}

	public function getConvention($demande,$convention,$flatten,$pdf=false,$out=false,$name='convention.pdf',$output='I'){

		if(!$pdf){
			$pdf = $this->getStart();
		}

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

		$pdf->MultiCell(100, 0, $demande->antenne->bloc_adresse, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
		$pdf->MultiCell(100, 0, $demande->organisateur->bloc_adresse, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

		$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

		$pdf->SetFont('helvetica', 'B', 8);
		$pdf->MultiCell(100, 0, 'Représentée par son président départemental', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
		$pdf->MultiCell(100, 0, 'Représenté par :', 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

		$pdf->SetFont('helvetica', '', 8);
		$pdf->MultiCell(100, 0, 'donnant délégation à :', 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
		$pdf->MultiCell(100, 0, $demande->organisateur->representant_fonction, 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');

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
			$horaires_debut = new Time($dimensionnements->horaires_debut);
			$horaires_fin = new Time($dimensionnements->horaires_fin);

			$pdf->SetFont('helvetica', '', 8);
			$pdf->SetTextColor(105, 105, 105);

			$txt = 'Annexe n°'.$key.' : '.$dimensionnements->intitule.', '.$dimensionnements->du_au;
			$pdf->Cell(0, 0, $txt, 0, 1, 'L', 0, '', 0);
			//$txt = 'Annexe n°'.$key.' : '.$dimensionnements->intitule.', du '.$this->Time->format($dimensionnements->horaires_debut,\IntlDateFormatter::FULL).' au '.$this->Time->format($dimensionnements->horaires_fin,\IntlDateFormatter::FULL);
			//$pdf->Cell(0, 0, $txt, 0, 1, 'L', 0, '', 0);
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

		$this->getAnnexes($demande,$pdf,false,false);

		//Close and output PDF document
		if($out){
			$pdf->Output($name,$output);
		}

		return $pdf;
	}

	public function getFacture($demande,$pdf=false,$out=false,$name='facture.pdf',$output='I'){

		if(!$pdf){
			$pdf = $this->getStart();
		}

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
		$pdf->MultiCell(100, 0, $demande->organisateur->representant_fonction, 0, 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
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
			$horaires_debut = new Time($dimensionnements->horaires_debut);
			$horaires_fin = new Time($dimensionnements->horaires_fin);
			$txt = $dimensionnements->intitule.', du '.$horaires_debut->i18nFormat(\IntlDateFormatter::FULL).' au '.$horaires_fin->i18nFormat(\IntlDateFormatter::FULL);
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
		$pdf->MultiCell(200, 0, 'Valeur de la prestation : '.Number::format( $demande->total_cout ) .' €', 0, 'L', 1, 1, '', '', true,1, true, true, 40, 'T');
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
		$pdf->MultiCell(200, 0, 'Montant facturé : '.Number::format( $demande->total_remise ) .' €', 0, 'L', 1, 1, '', '', true,1, true, true, 40, 'T');
		$pdf->SetTextColor(105);
		$pdf->SetFont('helvetica', '', 8);
		$pdf->setCellPaddings(10,0,0,0);
		$pdf->MultiCell(200, 0, 'Le dispositif dimensionné vous aurait normalement coûté '. Number::format( $demande->total_cout ).' €, toutefois nous avons choisis de vous le facturer '.Number::format( $demande->total_remise ).' €.', 0, 'L', 1, 1, '', '', true,1, false, true, 40, 'T');
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

		$pdf->Cell(0, 10, __('Total à règler : ') . Number::format( $demande->total_remise ) .' € ', 0, 1, 'R',1, '', 0,false,'T','M');
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

		// Get Annexe
		//$this->getAnnexes($demande,$pdf,false,false);

		//Close and output PDF document
		if($out){
			$pdf->Output($name,$output);
		}

		return $pdf;
	}

	public function getAnnexes($demande,$pdf=false,$consignes=false,$out=false,$name='etude.pdf',$output='I'){

		if(!$pdf){
			$pdf = $this->getStart();
		}

		if (!empty($demande->dimensionnements)):
		foreach ($demande->dimensionnements as $key => $dimensionnements):
			$pdf->AddPage();

			$pdf->setCellPaddings(0,0,0,0);
			$pdf->SetFillColor(240, 240, 240);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('helvetica', '', 11);

			$pdf->Cell(0, 7, __('Annexe n° ') . $key .' - '.$dimensionnements->intitule , 0, 1, 'C',1, '', 0,false,'T','M');

			$horaires_debut = new Time($dimensionnements->horaires_debut);
			$horaires_fin = new Time($dimensionnements->horaires_fin);

			$pdf->SetTextColor(105, 105, 105);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->Cell(0, 0, ucfirst( $dimensionnements->du_au ), 0, 1, 'C', 0, '', 0);
			$pdf->Cell(0, 0, 'Lieu de rendez-vous : '.$dimensionnements->lieu_manifestation, 0, 1, 'C', 0, '', 0);
			$pdf->Cell(0, 0, 'Contact sur place : '.$dimensionnements->contact_full, 0, 1, 'C', 0, '', 0);
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

				$horaires_convocation = new Time($equipe->horaires_convocation);
				$horaires_place = new Time($equipe->horaires_place);
				$horaires_fin = new Time($equipe->horaires_fin);

				$pdf->Cell($w[0], 6, $equipe->indicatif, 0, 0, 'L', $fill);
				$pdf->Cell($w[1], 6, $equipe->effectif, 0, 0, 'C', $fill);
			//	$pdf->Cell($w[2], 6, $equipe->vehicule_type, 0, 0, 'C', $fill);
				$pdf->Cell($w[3], 6, $equipe->lot_a, 0, 0, 'C', $fill);
				$pdf->Cell($w[4], 6, $equipe->lot_b, 0, 0, 'C', $fill);
				$pdf->Cell($w[5], 6, $equipe->lot_c, 0, 0, 'C', $fill);
				$pdf->Cell($w[6], 6, $equipe->autre, 0, 0, 'C', $fill);
				$pdf->Cell($w[7], 6, $horaires_convocation->format('d-m-Y H:i'), 0, 0, 'C', $fill);
				$pdf->Cell($w[8], 6, $horaires_place->format('d-m H:i'), 0, 0, 'C', $fill);
				$pdf->Cell($w[9], 6, $horaires_fin->format('d-m H:i'), 0, 0, 'C', $fill);
				$pdf->Cell($w[10], 6, $equipe->liste_repas, 0, 0, 'R', $fill);
				$pdf->Ln();
				$fill=!$fill;
			endforeach;

			$pdf->Cell(array_sum($w), 0, '', 0);

			if(isset($consignes)):
				if ($consignes):

					$pdf->AddPage();

					$pdf->setCellPaddings(0,0,0,0);
					$pdf->SetFillColor(240, 240, 240);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('helvetica', '', 11);

					$pdf->Cell(0, 7, __('Annexe n° ') . $key .' - Consignes aux équipes - '.$dimensionnements->intitule , 0, 1, 'C',1, '', 0,false,'T','M');

					$horaires_debut = new Time($dimensionnements->horaires_debut);
					$horaires_fin = new Time($dimensionnements->horaires_fin);

					$pdf->SetTextColor(105, 105, 105);
					$pdf->SetFont('helvetica', '', 9);
					$pdf->Cell(0, 0, 'Du '.$horaires_debut->i18nFormat(\IntlDateFormatter::FULL).' au '.$horaires_fin->i18nFormat(\IntlDateFormatter::FULL), 0, 1, 'C', 0, '', 0);
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
						$horaires_convocation = new Time($equipe->horaires_convocation);
						$horaires_place = new Time($equipe->horaires_place);
						$horaires_fin = new Time($equipe->horaires_fin);

						$pdf->Cell($w[0], 6, $equipe->indicatif, 0, 0, 'L', $fill);
						$pdf->Cell($w[1], 6, $equipe->effectif, 0, 0, 'C', $fill);
				//		$pdf->Cell($w[2], 6, $equipe->vehicule_type, 0, 0, 'C', $fill);
						$pdf->Cell($w[3], 6, $equipe->lot_a, 0, 0, 'C', $fill);
						$pdf->Cell($w[4], 6, $equipe->lot_b, 0, 0, 'C', $fill);
						$pdf->Cell($w[5], 6, $equipe->lot_c, 0, 0, 'C', $fill);
						$pdf->Cell($w[6], 6, $equipe->autre, 0, 0, 'C', $fill);
						$pdf->Cell($w[7], 6, $horaires_convocation->format('d-m-Y H:i'), 0, 0, 'C', $fill);
						$pdf->Cell($w[8], 6, $horaires_place->format('d-m H:i'), 0, 0, 'C', $fill);
						$pdf->Cell($w[9], 6, $horaires_fin->format('d-m H:i'), 0, 0, 'C', $fill);
						$pdf->Cell($w[10], 6, ($equipe->repas_charge * $equipe->repas_matin).' Mat. '.($equipe->repas_charge * $equipe->repas_midi).' Mid. '.($equipe->repas_charge * $equipe->repas_soir).' Soir', 0, 0, 'R', $fill);
						$pdf->Ln();
						$pdf->MultiCell(200, 0, __('Consignes spécifiques à l\'équipe : ').nl2br( $equipe->consignes ), 0, 'L', $fill, 1, '', '', true, 1, true, true, 40, 'T');
						$fill=!$fill;
					endforeach;
					$pdf->Cell(array_sum($w), 0, '', 0);

				endif;
			endif;
		endforeach;
		endif;

		if($out){
			$pdf->Output($name,$output);
		}

		return $pdf;
	}

	public function getEtapes($pdf=false,$out=false,$name='processus.pdf',$output='I'){

		if(!$pdf){
			$pdf = $this->getStart();
		}

		// set document information
		$pdf->SetCreator('Protection Civile');
		$pdf->SetAuthor('Protection Civile');
		$pdf->SetTitle('Processus de gestion des DPS');
		$pdf->SetSubject('Processus de gestion des DPS');
		$pdf->SetKeywords('DPS, Processus, Gestion, Etapes');

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

		$config_etats = TableRegistry::get('ConfigEtats');
		$config_etats = $config_etats->find('all')->order(['ordre'=>'asc']);

		// add a page
		$pdf->AddPage();

		$pdf->SetFont('helvetica', 'B', 12);

		$pdf->SetLineStyle(array('width' => 0.1));

		$pdf->SetFillColor(240, 240, 240);
		$pdf->SetTextColor(0, 0, 0);

		$pdf->Cell(0, 10, __('COMPRENDRE LES ETAPES DE GESTION DE MON DOSSIER '), 0, 1, 'C',1, '', 0,false,'T','M');
		$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetFillColor(255, 255, 255);

		// Lister toutes les étapes et en 3 colonne afficher les informations
		foreach($config_etats as $config_etat){

			$pdf->SetFont('helvetica', 'B', 8);

			$pdf->SetLineStyle(array('width' => 0.1));

			$pdf->MultiCell(8, 0, $config_etat->ordre.PHP_EOL, 'T', 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');
			$pdf->MultiCell(32, 0, $config_etat->designation.PHP_EOL, 'T', 'L', 1, 0, '', '', true, 1, false, true, 40, 'T');

			$pdf->SetFont('helvetica', '', 8);

			$pdf->MultiCell(160, 0, $config_etat->description.PHP_EOL.PHP_EOL, 'T', 'L', 1, 1, '', '', true, 1, false, true, 40, 'T');
			$pdf->Ln(0);
		}

		//Close and output PDF document
		if($out){
			$pdf->Output($name,$output);
		}

		return $pdf;
	}

	public function getPlanning($demande=false,$pdf=false,$type='_getPlanningPostes',$large=410,$out=false,$name='planning.pdf',$output='I'){

		if(!$pdf){
			$pdf = $this->getStart();
		}

		// set document information
		$pdf->SetCreator('Protection Civile');
		$pdf->SetAuthor('Protection Civile');
		$pdf->SetTitle('Planning de gestion DPS');
		$pdf->SetSubject('Planning de gestion DPS');
		$pdf->SetKeywords('DPS, Planning, Gestion');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 005', PDF_HEADER_STRING);

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

		$type = ($type!='_getPlanningItem') ? '_getPlanningPostes' : '_getPlanningItem';

		if($demande->dates_limits){
			$pdf = $this->{$type}($pdf,$demande);
		}

		//Close and output PDF document
		if($out){
			$pdf->Output($name,$output);
		}

		return $pdf;
	}

	protected function _getQuadrillage($pdf=false,$options=[]){

		$init = [];

		$init['max'] = 0;
		$init['large'] = 410;
		$init['title'] = 'COMPRENDRE LES ETAPES DE GESTION DE MON DOSSIER';
		$init['ytop'] = 50;
		$init['ybottom'] = 280;
		$init['ymarge'] = 5;
		$init['xmarge'] = 5;
		$init['date'] = false;
		$init['start'] = 0;

		$options = array_merge($init,(array)$options);

		extract($options);

		if($pdf){

			// add a page
			$pdf->AddPage();

			$pdf->SetFont('helvetica', 'B', 12);

			$pdf->SetLineStyle(array('width' => 0.1));

			$pdf->SetFillColor(240, 240, 240);
			$pdf->SetTextColor(0, 0, 0);

			$pdf->Cell(0, 10, $title, 0, 1, 'C',1, '', 0,false,'T','M');
			$pdf->Cell(0, 0, '', 0, 1, 'C', 0, '', 0);

			$pdf->SetFont('helvetica', '', 10);
			$pdf->SetFillColor(255, 255, 255);

			// Calcul de l'espacement des traits
			$pas = $large / $max;

			// Générer les traits
			// == en gris pour heures normales
			// == en gris foncé toutes les 6h
			// == en noir toutes les 12h
			$jour = 0;

			for($i=0;$i<=$max;$i++){
				$marge = ($i * $pas)+$xmarge;
				if(($i % 12) == 0){
					$color = array(0);
				}elseif(($i % 6) == 0){
					$color = array(200);
				}else{
					$color = array(240);
				}
				if($start%24==0){
					if($date && ($i<$max)){
						$pdf->Text($marge-3,$ytop-$ymarge-5,date('d-m-Y',($date + ($jour * 3600 *24))));
						$jour++;
					}
					$color = array(0);
				}
				$pdf->Line($marge,$ytop,$marge,$ybottom, array('width' => 0.1,'color' => $color));
				$pdf->Text($marge-3,$ytop-$ymarge,$start%24);

				$start++;
			}

			$y = 40;
		}

		return $pdf;

	}

	protected function _getPlanningItem($pdf=false,$demande=false){

		$init = [];

		$init['large'] = 410;
		$init['ystart'] = 40;
		$init['xmarge'] = 5;
		$init['hauteur'] = 5;
		$init['split'] = 15;

		$options = array_merge($init,(array) $options);

		extract($options);

		if($pdf && $demande){

			$cols = ($demande->dates_limits['round_max'] - $demande->dates_limits['round_min'])/3600;

			$start = $demande->dates_limits['round_min'];
			$end = $demande->dates_limits['round_max'];

			$diff = $end - $start;

			$equipes = Hash::extract($demande->dimensionnements,'{n}.dispositif.equipes');
			$equipes = Hash::extract($equipes,'{n}.{n}');

			$listes = array_chunk($equipes,$split);

			$pages = count($listes);

			foreach($listes as $key => $equipes){

				$init = [];

				$init['max'] = $cols;
				$init['large'] = $large;
				$init['title'] = 'PLANNIFICATION - PAGE '.($key+1).'/'.$pages.' - '.strtoupper($demande->manifestation);
				$init['date'] = $demande->dates_limits['round_min'];

				$pdf = $this->_getQuadrillage($pdf,$init);

				$pdf->SetX(0);

				$y = $ystart;

				foreach($equipes as $equipe){
					$y = $y + 15;
					$pdf->SetY($y);

					$absice = $this->_getAbsice($equipe['strtotime_convocation'],$large,$diff,$start) + $xmarge;
					$duree = $this->_getAbsice($equipe['duree_aller'],$large,$diff);

					$pdf->SetTextColor(0, 0, 0);
					$pdf->Text($absice-15, $y, $equipe['effectif'].' Pers.');
					$pdf->Rect($absice, $y, $duree, $hauteur, 'F', array(), array(0, 0, 128));
					$pdf->Text($absice, $y+5, $equipe['horaires_convocation']);

					$absice = $this->_getAbsice($equipe['strtotime_place'],$large,$diff,$start) + $xmarge;
					$duree = $this->_getAbsice($equipe['duree_poste'],$large,$diff);

					$pdf->SetTextColor(255, 255, 255);
					$pdf->Rect($absice, $y, $duree, $hauteur, 'F', array(), array(0,102,255));
					$pdf->Text($absice, $y, $equipe['indicatif']);
					$pdf->SetTextColor(0, 0, 0);

					$absice = $this->_getAbsice($equipe['strtotime_fin'],$large,$diff,$start) + $xmarge;
					$duree = $this->_getAbsice($equipe['duree_retour'],$large,$diff);

					$pdf->Rect($absice, $y, $duree, $hauteur, 'F', array(), array(0, 0, 128));

					$absice = $this->_getAbsice($equipe['strtotime_fin']+$equipe['duree_retour'],$large,$diff,$start) + $xmarge;
				//	$pdf->Text($absice, $y, $equipe['vehicule_type'].' / '.$equipe['vehicules_km'].' km');

				}
			}

		}

		return $pdf;

	}


	protected function _getPlanningPostes($pdf=false,$demande=false){

		$init = [];

		$init['large'] = 410;
		$init['ystart'] = 40;
		$init['xmarge'] = 5;
		$init['hauteur'] = 5;
		$init['split'] = 15;

		$options = array_merge($init,(array) $options);

		extract($options);

		if($pdf && $demande){

			$cols = ($demande->dates_limits['round_max'] - $demande->dates_limits['round_min'])/3600;

			$start = $demande->dates_limits['round_min'];
			$end = $demande->dates_limits['round_max'];

			$diff = $end - $start;

			foreach( $demande->dimensionnements as $dimensionnement){

				$equipes = Hash::extract($dimensionnement,'dispositif.equipes');

				$listes = array_chunk($equipes,$split);

				$pages = count($listes);

				foreach($listes as $key => $equipes){

					$init = [];

					$init['max'] = $cols;
					$init['large'] = $large;
					$init['title'] = 'PLANNIFICATION - '.strtoupper($demande->manifestation).' - '.$dimensionnement->intitule.' - PAGE '.($key+1).'/'.$pages;
					$init['date'] = $demande->dates_limits['round_min'];

					$pdf = $this->_getQuadrillage($pdf,$init);

					$pdf->Text(120, 5,$dimensionnement->contact_present);
					$pdf->Text(120, 10,$dimensionnement->contact_fonction);
					$pdf->Text(120, 20,$dimensionnement->contact_portable);
					$pdf->Text(120, 15,$dimensionnement->contact_telephone);

					$pdf->Text(160, 5,'Médecin : '.$dimensionnement->medecin.' - Tel: '.$dimensionnement->medecin_telephone);
					$pdf->Text(160, 10,'Autres médecins : '.$dimensionnement->medecin_autres);
					$pdf->Text(160, 15,'Infirmier : '.$dimensionnement->infirmier);
					$pdf->Text(160, 20,'Ambulancier : '.$dimensionnement->ambulancier.' - Tel: '.$dimensionnement->ambulancier_telephone);

					$pdf->Text(260, 5,'Pompiers : '.$dimensionnement->pompier.' '.$dimensionnement->pompier_delai.' km - Hôpital : '.$dimensionnement->hopital.' '.$dimensionnement->hopital_delai.' km');
					$pdf->Text(260, 10,'Secours publics présents : '.$dimensionnement->secours_presents);
					$pdf->Text(260, 15,'Documents officiels : '.$dimensionnement->documents_officiels);
					$pdf->Text(260, 20,'Point de rendez-vous : '.$dimensionnement->lieu_manifestation);

					$pdf->SetX(0);

					$y = $ystart;

					foreach($equipes as $equipe){
						$y = $y + 15;
						$pdf->SetY($y);

						$absice = $this->_getAbsice($equipe['strtotime_convocation'],$large,$diff,$start) + $xmarge;
						$duree = $this->_getAbsice($equipe['duree_aller'],$large,$diff);

						$pdf->SetTextColor(0, 0, 0);
						$pdf->Text($absice-15, $y, $equipe['effectif'].' Pers.');
						$pdf->Rect($absice, $y, $duree, $hauteur, 'F', array(), array(0, 0, 128));
						$pdf->Text($absice, $y+5, $equipe['horaires_convocation']);

						$absice = $this->_getAbsice($equipe['strtotime_place'],$large,$diff,$start) + $xmarge;
						$duree = $this->_getAbsice($equipe['duree_poste'],$large,$diff);

						$pdf->SetTextColor(255, 255, 255);
						$pdf->Rect($absice, $y, $duree, $hauteur, 'F', array(), array(0,102,255));
						$pdf->Text($absice, $y, $equipe['indicatif']);
						$pdf->SetTextColor(0, 0, 0);

						$absice = $this->_getAbsice($equipe['strtotime_fin'],$large,$diff,$start) + $xmarge;
						$duree = $this->_getAbsice($equipe['duree_retour'],$large,$diff);

						$pdf->Rect($absice, $y, $duree, $hauteur, 'F', array(), array(0, 0, 128));

						$absice = $this->_getAbsice($equipe['strtotime_fin']+$equipe['duree_retour'],$large,$diff,$start) + $xmarge;
				//		$pdf->Text($absice, $y, $equipe['vehicule_type'].' / '.$equipe['vehicules_km'].' km');

					}
				}

			}

		}

		return $pdf;

	}

	protected function _getLongueur($value=0,$large=0,$strtotime=0,$start=0,$plus=0){

		return ((($value + $plus) - $start) * $large) / $strtotime;

	}
	protected function _getAbsice($value=0,$large=0,$strtotime=0,$start=0,$plus=0){

		return ((($value + $plus) - $start) * $large) / $strtotime;

	}
}
?>

<?php
// Require composer autoload
require_once ROOT . '/vendor/mpdf/mpdf/src/Mpdf.php';
$mpdf = new \Mpdf\Mpdf([
	'default_font_size' => 9,
	'default_font' => 'arial'
]);

// Buffer the following html with PHP so we can store it to a variable later
ob_start();
?>

<html>
    <head>
		<?php echo $this->Html->css('bootstrap.css', ['fullBase' => true]); ?>
		<?php //echo $this->Html->css('print.css', ['fullBase' => true]); ?>
        <style>
            /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }
      			* {
      				font-size:16px;
      				font-type:'arial';
      				font-weight:lighter;
      				text-align:justify;
      			}
      			center {
      				font-size:26px;
      				text-align:center;
      			}
            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 3.5cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1.5cm;
            }

            /** Define the header rules **/
            header {
                position: fixed;
                top: 1cm;
                left: 1cm;
                right: 1cm;
                height: 2cm;

                /** Extra personal styles **/
				        background-color: transparent;
                color: #004080;
                text-align: left;
            }
            /** Define the footer rules **/
            footer {
                position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 1.5cm;

                /** Extra personal styles **/
                background-color: transparent;
                color: #004080;
                text-align: center;
                line-height: 14px;
        				font-size:12px;
        				border-top:1px solid #F08700;
            }
      			.td-logo{
        				float:left;
        				padding-right:15px;
      			}
      			.td-blue{
        				font-size:38px;
        				font-weight:bold;
        				line-height:36px;
        				margin:0px;
        				padding:0px;
        				padding-top:12px;
      			}

      			.td-orange{
        				margin:0px;
        				padding:0px;
        				color:#F08700;
      			}
      			.td-size{
        				font-size:26px;
        				font-weight:bold;
        				line-height:24px;
      			}
        </style>
    </head>
    <body>
        <!-- Define header blocks before your content -->
        <header>
    			<div class="td-logo"><?php echo $this->Html->image('logo.svg', ['width'=>'102px','fullBase' => true]); ?></div>
    			<div class="td-blue">PROTECTION CIVILE</div>
    			<div class="td-orange td-size">AIDER - FORMER - SECOURIR</div>
    			<div class="td-blue td-size">| VOSGES</div>
        </header>
        <!-- Wrap the content of your PDF inside a main tag -->
        <?php echo $this->fetch('content'); ?>
        <!-- Define footer blocks before your content -->
        <footer>
Association Départementale de Protection Civile des Vosges - ADPC 88 - Maison des associations, 6 quartier de la Madeleine, 88000 EPINAL, France<br/>
Tél : 09 63 40 08 01 - Fax: 03 29 64 24 89 - Email: vosges@protection-civile.org - Site Internet : vosges.protection-civile.org<br/>
Association régie par la loi de 1901 - Membre de la Fédération Nationale de Protection Civile - Association agréée de sécurité civile - Reconnue d’utilité publique
        </footer>

    </body>
</html>


<?php
// Now collect the output buffer into a variable
$html = ob_get_contents();
ob_end_clean();

// send the captured HTML from the output buffer to the mPDF class for processing
$mpdf->WriteHTML($html);
$mpdf->Output();


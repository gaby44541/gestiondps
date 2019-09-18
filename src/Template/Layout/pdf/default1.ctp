<html>
    <head>
		<?php echo $this->Html->css('bootstrap.min.css', ['fullBase' => true]); ?>
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
      				font-family:Arial, Helvetica, sans-serif !important;
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
				font-weight:bolder;
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
    			<div class="td-logo"><?php echo $this->Html->image('logo-simple.png', ['width'=>'102','fullBase' => true]); ?></div>
    			<div class="td-blue">PROTECTION CIVILE</div>
    			<div class="td-orange td-size">AIDER - FORMER - SECOURIR</div>
    			<div class="td-blue td-size">| LOIRE-ATLANTIQUE</div>
        </header>
        <!-- Define footer blocks before your content -->
        <footer>
Protection Civile de Loire-Atlantique - ADPC 44 - 8 Rue Paul Beaupère, 44300 Nantes, France<br/>
Tél : 02 40 47 87 34 - Email: loire-atlantique@protection-civile.org - Site Internet : protection-civile44.fr<br/>
Association régie par la loi de 1901 - Membre de la Fédération Nationale de Protection Civile - Association agréée de sécurité civile - Reconnue d’utilité publique
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
		<main>
        <?php echo $this->fetch('content'); ?>
		</main>
    </body>
</html>

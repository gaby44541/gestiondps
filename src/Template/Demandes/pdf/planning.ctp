<div style="width:40cm; margin:0px; padding:0px;">

	<center style="background-color:#f2f2f2; width:40cm;"><?= __('PLANNING DES EQUIPES') ?></center>
	<br/>
	<?php //$this->PdfGantt->setConfig('limits',[]); ?>
	<?php $this->PdfGantt->build(); ?>

</div>
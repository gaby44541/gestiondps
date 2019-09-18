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
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
Bonjour Mesdames et Messieurs de l'équipe opérationnelle,<br/>
<br/>
Ces dossiers sont encore en attente de traitement alors qu'ils terminent sous peu.<br/>
<b>Il est plus que temps de les traiter.</b><br/>
<br/>
Je vous laisse <b>jusque la fin de cette semaine</b> pour le faire et les basculer dans <b>en attente d'étude</b>, sans quoi le logiciel enverra à ces derniers un message d'annulation.<br/>
<br/>
Nous vous rappelons que créer un dossier standard prends 10 minutes, hors cela fait <b>plus d'une semaine</b> pour certains.<br/>
<br/>
Si vous avez oublié de le basculer en envoyant l'étude, alors je vous rappelle que cette bascule est obligatoire pour faire avancer votre dossier sinon l'organisateur recevra un mail d'annulation.<br/>
<br/>
<b>Vous recevrez ces mails tant que vos dossiers ne seront pas traités.</b><br/>
<br/>
<b>Vous êtes une équipe : aussi aidez vous quand vous voyez un dossier d'un copain non finalisé, terminez le.</b><br/>
<br/>
Amicalement<br/>
Jean-Christophe<br/>
<br/>
<?php
foreach($demandes as $line):
	echo '<p>'.$this->Html->link($line->manifestation,['controller'=>'demandes','action' => 'dispatch',$line->id,'_full' => true]).'<br/>'.$line->gestionnaire_nom.'</p>';
endforeach;
?>

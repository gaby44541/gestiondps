<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;
%>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
		<div class="panel panel-primary">
			<div class="panel-heading">
			<h3 class="panel-title"><% printf("<% echo __('%s %s'); %>", Inflector::humanize($action), $singularHumanName); %></h3>
			</div>
			<div class="panel-body table-responsive">

			<% echo "<% echo \$this->Form->create('{$modelClass}', array('role' => 'form')); %>\n"; %>

				<fieldset>

					<%
						foreach ($fields as $field) {
							if (strpos($action, 'add') !== false && $field == $primaryKey) {
								continue;
							} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
								echo "\t\t\t\t\t<div class=\"form-group\">\n";
								echo "\t\t\t\t\t\t<% echo \$this->Form->input('{$field}', array('class' => 'form-control')); %>\n";
								echo "\t\t\t\t\t</div><!-- .form-group -->\n";
							}
						}
						if (!empty($associations['hasAndBelongsToMany'])) {
							foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
								echo "\t\t\t\t\t<div class=\"form-group\">\n";
								echo "\t\t\t\t\t\t\t<% echo \$this->Form->input('{$assocName}');%>\n";
								echo "\t\t\t\t\t</div><!-- .form-group -->\n";
							}
						}
						echo "\n";
						echo "\t\t\t\t\t<% echo \$this->Form->submit('Submit', array('class' => 'btn btn-large btn-primary')); %>\n";
					%>

				</fieldset>

			<% echo "\t\t\t<% echo \$this->Form->end(); %>\n";%>

		</div><!-- /.form -->

	</div><!-- /#page-content .col-md-9 -->

</div><!-- /#page-container .row-fluid -->


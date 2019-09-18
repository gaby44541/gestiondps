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
use Cake\Utility\Hash;
use Cake\Database\Schema\Collection;

$inarray = ['id'];
$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];

$immediateAssociations = $associations['BelongsTo'];
$associationFields = collection($fields)
    ->map(function($field) use ($immediateAssociations) {
        foreach ($immediateAssociations as $alias => $details) {
            if ($field === $details['foreignKey']) {
                return [$field => $details];
            }
        }
    })
    ->filter()
    ->reduce(function($fields, $value) {
        return $fields + $value;
    }, []);
	
$inarray = (array) Hash::extract($associations, 'HasMany.{*}.foreignKey');
$inarray[] = 'id';

$grouped = array();

foreach( $fields as $field):

		$type = $schema->columnType($field);
		
		if( isset($associationFields[$field])) {
            $type = 'string';
        }
		
			switch( $type ){
				case 'integer':
				case 'float':
				case 'decimal':
				case 'biginteger':
					$grouped[ $field ] = 'number';
					break;
				case 'date':
				case 'time':
				case 'datetime':
				case 'timestamp':
					$grouped[ $field ] = 'date';
					break;			
					case 'date':
				case 'text':
				case 'boolean':
					$grouped[ $field ] = $type;
					break;
				default:
					$grouped[ $field ] = 'string';
			}

endforeach;

$pk = "\$$singularVar->{$primaryKey[0]}";
%>
<div class="<%= $pluralVar %> view col-lg-12 col-md-12 columns content">
	<h1>
		<?= __('<%= Inflector::humanize($singularVar) %>') ?>
		<?= $this->element('buttons',['controller'=>'<%= $pluralVar %>','action_id'=><%= $pk %>]) ?>
	</h1>
	<table class="vertical-table table table-striped">
<% foreach( $grouped as $field => $types ): %>
<% if ($types == 'string') : %>
<% if (isset($associationFields[$field])) : $details = $associationFields[$field]; %>
		<tr>
			<th><?= __('<%= Inflector::humanize($details['property']) %>') ?></th>
			<td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
		</tr>
<% else : %>
		<tr>
			<th><?= __('<%= Inflector::humanize($field) %>') ?></th>
			<td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
		</tr>
<% endif; endif; if ($types == 'number') : %>
		<tr>
			<th><?= __('<%= Inflector::humanize($field) %>') ?></th>
			<td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
		</tr>
<% endif; if ($types == 'date') : %>
		<tr>
			<th><%= "<%= __('" . Inflector::humanize($field) . "') %>" %></th>
			<td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
		</tr>
<% endif; if ($types == 'boolean') : %>
		<tr>
			<th><?= __('<%= Inflector::humanize($field) %>') ?></th>
			<td><?= $<%= $singularVar %>-><%= $field %> ? __('Yes') : __('No'); ?></td>
		</tr>
<% endif; if ($types == 'text') : %>
		<tr>
			<th><?= __('<%= Inflector::humanize($field) %>') ?></th>
			<td><?= $this->Text->autoParagraph(h($<%= $singularVar %>-><%= $field %>)); ?></td>
		</tr>
<% endif; %>
<% endforeach; %>	
<% if ($associations['HasOne']) : %>
<% foreach ($associations['HasOne'] as $alias => $details) : %>
		<tr>
			<th><?= __('<%= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) %> - Voir le détail :') ?></th>
			<td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
		</tr>
<% endforeach; %>
<% endif; %>
	</table>
<% $aliases = [];
$relations = $associations['HasMany'] + $associations['BelongsToMany'];
foreach ($relations as $alias => $details):
$otherSingularVar = Inflector::variable($alias);
$otherPluralHumanName = Inflector::humanize(Inflector::underscore($details['controller']));
$aliases[] = $alias; %>
<div class="related">
	<h3>
		<?= __('<%= $otherPluralHumanName %>') ?>
		<?= $this->element('buttons',['controller'=>'<%= $alias %>','options'=>'add']) ?>
	</h3>
<?php if (!empty($<%= $singularVar %>-><%= $details['property'] %>)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="<%= $alias %>">
		<thead>
			<tr>
<% $i = 0; foreach ($details['fields'] as $field): if( ! in_array( $field , $inarray ) ): $i++; if( $i <9 ): %>
				<th><?= __('<%= Inflector::humanize($field) %>') ?></th>
<% endif; endif; endforeach; %>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($<%= $singularVar %>-><%= $details['property'] %> as $<%= $otherSingularVar %>): ?>
			<tr>
<% $i = 0; foreach ($details['fields'] as $field): if( ! in_array( $field , $inarray ) ): $i++; if( $i <9 ): %>
				<td><?= h($<%= $otherSingularVar %>-><%= $field %>) ?></td>
<% endif; endif; endforeach; %>
<% $otherPk = "\${$otherSingularVar}->{$details['primaryKey'][0]}"; %>
				<td class="actions">
				<?= $this->element('actions',['controller'=>'<%= $alias %>','action_id'=><%= $otherPk %>]) ?>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
</div>
<% endforeach; %>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	<% foreach ($aliases as $alias): %>
    $('#<%= $alias %>').DataTable();
	<% endforeach; %>
</script>
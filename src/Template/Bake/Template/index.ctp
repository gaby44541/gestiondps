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

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return !in_array($schema->columnType($field), ['binary']); //, 'text'
    })
    ->take(7);

if (isset($modelObject) && $modelObject->behaviors()->has('Tree')) {
    $fields = $fields->reject(function ($field) {
        return $field === 'lft' || $field === 'rght';
    });
}
%>
<div class="<%= $pluralVar %> index col-lg-12 col-md-2 content">
	<h1>
		<?= __('<%= $pluralHumanName %>') ?>
		<?= $this->element('buttons',['controller'=>'<%= $pluralVar %>','options'=>'add']) ?>
	</h1>

	<table class="table table-hover table-striped" id="<%= $pluralVar %>">
	    <thead>
	        <tr>
        <% foreach ($fields as $field): if( $field != 'id' ): %>
	            <th><?= __('<%= Inflector::humanize($field) %>') ?></th>
        <% endif; endforeach; %>
	            <th class="actions"><?= __('Actions') ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php foreach ($<%= $pluralVar %> as $<%= $singularVar %>): ?>
	        <tr>
        <%        foreach ($fields as $field) { if( $field != 'id' ):
	        $isKey = false;
	        if (!empty($associations['BelongsTo'])) {
	            foreach ($associations['BelongsTo'] as $alias => $details) {
	                if ($field === $details['foreignKey']) {
	                    $isKey = true;
        %>
	            <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
        <%
	                    break;
	                }
	            }
	        }
	        if ($isKey !== true) {
	            if (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
        %>
	            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
        <%
	            } else {
        %>
	            <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
        <%
	            }
	        }
                endif; }

	    $pk = '$' . $singularVar . '->' . $primaryKey[0];
        %>
	            <td class="actions">
					<?= $this->element('actions',['controller'=>'<%= $pluralVar %>','action_id'=><%= $pk %>]) ?>
	            </td>
	        </tr>
	        <?php endforeach; ?>
	    </tbody>
	</table>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
    $('#<%= $pluralVar %>').DataTable();
</script>

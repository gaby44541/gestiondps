<div class="container-fluid">
	<h1>
		<?= __('Menu') ?>
		<?= $this->element('buttons',['controller'=>'menus','action_id'=>$menu->id]) ?>
	</h1>
	<table class="vertical-table table table-striped">
		<tr>
			<th><?= __('Id') ?></th>
			<td><?= $this->Number->format($menu->id) ?></td>
		</tr>
		<tr>
			<th><?= __('Parent Menu') ?></th>
			<td><?= $menu->has('parent_menu') ? $this->Html->link($menu->parent_menu->intitule, ['controller' => 'Menus', 'action' => 'view', $menu->parent_menu->id]) : '' ?></td>
		</tr>
		<tr>
			<th><?= __('Lft') ?></th>
			<td><?= $this->Number->format($menu->lft) ?></td>
		</tr>
		<tr>
			<th><?= __('Rght') ?></th>
			<td><?= $this->Number->format($menu->rght) ?></td>
		</tr>
		<tr>
			<th><?= __('Level') ?></th>
			<td><?= $this->Number->format($menu->level) ?></td>
		</tr>
		<tr>
			<th><?= __('Icone') ?></th>
			<td><?= h($menu->icone) ?></td>
		</tr>
		<tr>
			<th><?= __('Intitule') ?></th>
			<td><?= h($menu->intitule) ?></td>
		</tr>
		<tr>
			<th><?= __('Controller') ?></th>
			<td><?= h($menu->controller) ?></td>
		</tr>
		<tr>
			<th><?= __('Actions') ?></th>
			<td><?= h($menu->actions) ?></td>
		</tr>
		<tr>
			<th><?= __('Params') ?></th>
			<td><?= h($menu->params) ?></td>
		</tr>
		<tr>
			<th><?= __('Home') ?></th>
			<td><?= $menu->home ? __('Yes') : __('No'); ?></td>
		</tr>
	
	</table>
<div class="related">
	<h3>
		<?= __('Menus') ?>
		<?= $this->element('buttons',['controller'=>'ChildMenus','options'=>'add']) ?>
	</h3>
<?php if (!empty($menu->child_menus)): ?>
	<table cellpadding="0" cellspacing="0" class="table table-striped" id="ChildMenus">
		<thead>
			<tr>
				<th><?= __('Lft') ?></th>
				<th><?= __('Rght') ?></th>
				<th><?= __('Level') ?></th>
				<th><?= __('Icone') ?></th>
				<th><?= __('Intitule') ?></th>
				<th><?= __('Controller') ?></th>
				<th><?= __('Actions') ?></th>
				<th><?= __('Params') ?></th>
				<th class="actions"><?= __('Actions') ?></th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($menu->child_menus as $childMenus): ?>
			<tr>
				<td><?= h($childMenus->lft) ?></td>
				<td><?= h($childMenus->rght) ?></td>
				<td><?= h($childMenus->level) ?></td>
				<td><?= h($childMenus->icone) ?></td>
				<td><?= h($childMenus->intitule) ?></td>
				<td><?= h($childMenus->controller) ?></td>
				<td><?= h($childMenus->actions) ?></td>
				<td><?= h($childMenus->params) ?></td>
				<td class="actions">
				<?= $this->element('actions',['controller'=>'ChildMenus','action_id'=>$childMenus->id]) ?>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
</div>
</div>

<?= $this->Html->script('datatables.min'); ?>

<script type="text/javascript">
	    $('#ChildMenus').DataTable();
	</script>
<div class="container-fluid">
	<h1>
		<?= __('Recherche DPS') ?>
	</h1>

    <?php
    echo $this->Form->create(null, [
        'url' => ['controller' => 'rechercheDps', 'action' => 'resultats']
    ]);
    echo $this->Form->control('Nom de la manifestation', ['required' => false]);
    echo $this->Form->button('Rechercher',['class' => 'btn btn-large btn-primary']);
    echo $this->Form->end();
    ?>
</div>

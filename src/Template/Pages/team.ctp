<?php

$this->layout = 'default';
$this->assign('title', 'Our Team | mySetup.co');

echo $this->Html->meta('description', 'Who is behind mySetup.co ?', ['block' => true]);

?>

<div class="maincontainer">

	<h1><?= __('About us') ?></h1>

	<p><?= __('A project made by Samuel Forestier and Corentin Mors.') ?></p>

	<br/>

	<p> <strong>Corentin Mors </strong>: <?= ("Etudiant à l'INSA Centre Val de Loire, président de Geek Mexicain et développeur de sites webs tels que Uzzy.me et PixelSwap. J’ai commencé à toucher au monde du web très jeune et je gère maintenant de nombreux projets sur cette géante toile.") ?></p>

	<br/>

	<p><strong>Samuel Forestier</strong> : <?= __('Trésorier et rédacteur chez Geek Mexicain, je tiens un blog personnel depuis maintenant quelques années qui, de plus, recense mes activités de développement sur des projets. Étudiant INSA également !') ?></p>

</div>
<?php

$this->layout = 'default';
$this->assign('title', __('Q&A').' | mySetup.co');

echo $this->Html->meta('description', 'We answer common questions and issues about mySetup. Find some tutorials to start sharing your setup now.', ['block' => true]);

?>

<div class="maincontainer">

	<h3><?= __('Q&A - We answer common questions') ?></h3>

	<ol>
		<li><a href="#q-1"><?= __('How to register on mySetup ?') ?></a></li>
		<li><a href="#q-2"><?= __('How to register on mySetup ?') ?></a></li>
		<li><a href="#q-3"><?= __('How to register on mySetup ?') ?></a></li>
	</ol>

	<hr>

	<h4 id="q-1"><?= __('How to register on mySetup ?') ?></h4>
	<p><?= __('Something here...') ?></p>

	<h4 id="q-2"><?= __('How to register on mySetup ?') ?></h4>
	<p><?= __('Something here...') ?></p>

</div>
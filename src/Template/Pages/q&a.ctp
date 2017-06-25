<?php

$this->layout = 'default';
$this->assign('title', __('Q&A').' | mySetup.co');

echo $this->Html->meta('description', __('We answer common questions and issues about mySetup. Find some tutorials to start sharing your setup now.'), ['block' => true]);

?>
<div class="container sitecontainer">
<div class="maincontainer">

	<h3><?= __('Q&A - We answer common questions') ?></h3>

	<ol>
		<li><a href="#q-1"><?= __("I didn't receive the verification mail") ?></a></li>
		<li><a href="#q-2"><?= __("I can't find a product with the product search") ?></a></li>
		<li><a href="#q-3"><?= __("The product search is not responding") ?></a></li>
		<li><a href="#q-4"><?= __("My setup was not created due to the images I uploaded") ?></a></li>
		<li><a href="#q-5"><?= __("I can't add a Youtube / Twitch video on my setup") ?></a></li>
		<li><a href="#q-6"><?= __("How to use the Markdown") ?></a></li>
		<li><a href="#q-7"><?= __("My setup has been refused, why ?") ?></a></li>
	</ol>
	
	<hr>
	<br>

	<h4 id="q-1"><?= __("I didn't receive the verification mail") ?></h4>
	<p><?= __("Check your spam folder, your mailbox may have dropped it there. If you didn't receive it within 10 minutes please contact us on our social networks.") ?></p>

	<br>

	<h4 id="q-2"><?= __("I can't find a product with the product search") ?></h4>
	<p><?= __('Some products does not exist in our database, because it is a special edition or it is too old. Try to find a replacement available.') ?></p>

	<br>

	<h4 id="q-3"><?= __("The product search is not responding") ?></h4>
	<p><?= __('Sometimes the product search is busy with lot of request, please wait few moments and retry your search.') ?></p>

	<br>

	<h4 id="q-4"><?= __("My setup was not created due to the images I uploaded") ?></h4>
	<p><?= __('We use some rules on images to prevent abuses. Images are limited to 5Mo per image and the format should be jpg, jpeg or png.') ?></p>

	<br>

	<h4 id="q-5"><?= __("I can't add a Youtube / Twitch video on my setup") ?></h4>
	<p><?= __('Video links must be full URI links like : "https://www.youtube.com/watch?v=dQw4w9WgXcQ" or "https://player.twitch.tv/?channel=mikescops"') ?></p>

	<br>

	<h4 id="q-6"><?= __("How to use the Markdown") ?></h4>
	<p><?= __('The Markdown system is similar to Github\'s one, please read more at : ') ?> <a href="https://guides.github.com/features/mastering-markdown/">Github Markdown Guide</a></p>

	<br>

	<h4 id="q-7"><?= __("My setup has been refused, why ?") ?></h4>
	<p><?= __('If your setup has been moderated and refused, you can read why in your setup description. Please create another setup and follow the moderators guidelines.') ?></p>

	<br>



</div>
</div>
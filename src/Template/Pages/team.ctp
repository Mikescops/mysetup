<?php

$this->layout = 'default';
$this->assign('title', 'Our Team | mySetup.co');

echo $this->Html->meta('description', 'Who is behind mySetup.co ?', ['block' => true]);

?>

<div class="maincontainer team-page">

	<h1><?= __('Our Team') ?></h1>
 

	<h3>HeadStaff</h3>

	<div class="member">

		<img alt="Corentin Mors" src="https://mysetup.co/uploads/files/pics/profile_picture_994774516.png">

		<p> <strong>Corentin Mors </strong>: <?= ("Student at INSA Centre Val de Loire, president of Geek Mexicain et developer of website like Uzzy.me and Pixelswap. I started to work on web development very young and now I manage plenty of projects over the net.") ?></p>
		<a href="https://pixelswap.fr/" target="_blank"><i class="fa fa-globe"></i> PixelSwap</a> | 
		<a href="https://twitter.com/MikeScops" target="_blank"><i class="fa fa-twitter"></i> mikescops</a> | 
		<a href="https://www.linkedin.com/in/corentinmors/" target="_blank"><i class="fa fa-linkedin"></i> Hire me</a>

	</div>
	<br/>

	<div class="member">

		<img alt="Samuel Forestier" src="https://mysetup.co/uploads/files/pics/profile_picture_274832608.png">

		<p><strong>Samuel Forestier</strong> : <?= __('Treasurer and writer at Geek Mexicain, I manage a personal blog for many years now where you can find all my development activities about my projects. INSA student too !') ?></p>
		<a href="https://horlogeskynet.github.io/" target="_blank"><i class="fa fa-globe"></i> HorlogeSkynet</a>

	</div>
</div>
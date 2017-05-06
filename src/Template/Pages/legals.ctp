<?php

$this->layout = 'default';
$this->assign('title', 'Legal Mentions | mySetup.co');

echo $this->Html->meta('description', 'Legal mentions about your personal data, cookies and credits.', ['block' => true]);

?>

<div class="maincontainer">

	<h3><?= __('Site Publisher') ?></h3>
	<p>GEEK MEXICAIN<br>
	88 boulevard Lahitolle<br>
	18000 BOURGES<br>
	contact(at)mysetup.co</p>

	<h3><?= __('Head of Publication') ?></h3>
	<p>
		Corentin Mors - <?= __('Co-founder') ?><br />
		Samuel Forestier - <?= __('Co-founder') ?>
	</p>

	<h2><?= __('Personal Data') ?></h2>

	<p><?= __('Your personal data may be collected when browsing this website. Emails sent to mysetup.co and the email addresses used to send information may also be stored. Pursuant to France’s Data Protection Act modified on 6 January 1978, you have the right to access and rectify your personal data. You also have the right to object the use of this information provided you have legitimate reason. You can exercise this right and obtain information concerning your personal data by writing to the Head of Publication.') ?></p>

	<h2><?= __('Use of Cookies') ?></h2>

	<p><?= __('The user is informed that during his visits to the site, a cookie can be installed automatically on his navigator software. A cookie is a file containing data that record information relating to the navigation of the user on the site but which do not permit to identify it.') ?>
	</p>

	<h2><?= __('Credits') ?></h2>

	<p><?= __('© 2017 mysetup.co – All rights reserved. Total or partial production or imitation of said documents without the express prior written consent of mysetup.co is strictly prohibited and constitutes a punishable violation under France’s Intellectual Property Code. All brands and trademarks on mysetup.co belongs to their respective owners.') ?></p>

</div>
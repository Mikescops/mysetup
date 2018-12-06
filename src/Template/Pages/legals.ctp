<?php

$this->layout = 'default';
$this->assign('title', __('Legal Mentions | mySetup.co'));

echo $this->Html->meta('description', __('Legal mentions about your personal data, cookies and credits.'), ['block' => true]);

?>
<div class="colored-container">
    <div class="container">
        <br><h2><?= __('Legal Mentions') ?></h2><br>
    </div>
</div>
<div class="container">

<div class="maincontainer">

	<h3><?= __('Site Publisher') ?></h3>
	<p>GEEK MEXICAIN<br>
	88 Boulevard Lahitolle<br>
	18000 BOURGES<br>
	contact [at] mysetup [dot] co</p>

	<h3><?= __('Head of Publication') ?></h3>
	<p>
		Corentin Mors - <?= __('Co-founder') ?><br />
		Samuel Forestier - <?= __('Co-founder') ?>
	</p>

	<hr>

	<h3><?= __('Personal Data') ?></h3>
	<p>
		<?= __('Your personal data may be collected when browsing this website. Emails sent to mysetup.co and the email addresses used to send information may also be stored. Pursuant to France’s Data Protection Act modified on 6 January 1978, you have the right to access and rectify your personal data. You also have the right to object the use of this information provided you have legitimate reason. You can exercise this right and obtain information concerning your personal data by writing to the Head of Publication.') ?>
	</p>

	<h3><?= __('Publishing Policy') ?></h3>
	<p>
		<?= __('By registering on mysetup.co, you agree with the following rules. Content published on this website must be related with setups. Any content judged not appropriate will be moderated without warning. Users who do not respect the pre-established rules several times will see their account banned.') ?>
	</p>

	<h3><?= __('Use of Cookies') ?></h3>
	<p>
		<?= __('The user is informed that during his visits to the site, a cookie can be installed automatically on his navigator software. A cookie is a file containing data that record information relating to the navigation of the user on the site but which do not permit to identify it.') ?>
	</p>

	<h3><?= __('Partnership Disclaimer') ?></h3>
	<p>
		<?= __('mySetup.co is a participant in the Amazon Services LLC Associates Program, an affiliate advertising program. In other terms, links to Amazon Stores use an affiliate identification.') ?>
	</p>
	<p>
		<?= __('mySetup.co is in partnership with leDénicheur, a prices comparator service. In other terms, links to leDénicheur services use an affiliate identification.') ?>
	</p>

	<h3><?= __('Hosting') ?></h3>
	<p>
		<?= __('This website is hosted by') ?> <a href="https://www.ovh.com/fr/support/mentions-legales/">OVH</a>.<br />
		<?= __('Mail contact') ?> : 2 rue Kellermann - 59100 Roubaix - France<br />
		<?= __('This accommodation is provided free of charge by Mr. Mors Corentin.') ?>
	</p>

	<h3><?= __('Credits') ?></h3>
	<p>
		<?= __('© 2017 - 2018 mysetup.co – All rights reserved. Total or partial production or imitation of said documents without the express prior written consent of mysetup.co is strictly prohibited and constitutes a punishable violation under France’s Intellectual Property Code. All brands and trademarks on mysetup.co belongs to their respective owners.') ?>
	</p>

</div>

</div>

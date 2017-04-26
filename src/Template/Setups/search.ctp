<?php
/**
  * @var \App\View\AppView $this
  */
?>


<div class="maincontainer">

<div class="row">
    <div class="column column-75">

	<?php if ($setups == "noquery"){
		echo "<h3>No search query, no results :(</h3>";
	}
	elseif ($setups == "noresult"){
		echo "<h3>We didn't found any results for this query :(</h3>";
	}
	else{

	foreach ($setups as $setup): ?>

	            <div class="fullitem">
	                <a href="<?= $this->Url->build('/setups/view/', true)?><?= $setup->setup_id ?>">
	                    <img src="<?= $this->Url->build('/', true)?><?= $setup->src ?>">
	                </a>
	                <div class="fullitem-inner">

	                    <div class="row">

	                        <div class="column column-75">
	                            <a class="featured-user" href="#">
	                                <img src="<?= $this->Url->build('/'); ?>uploads/files/profile_picture_<?= $setup->setup->user_id ?>">
	                            </a>

	                            <a href="<?= $this->Url->build('/setups/view/', true)?><?= $setup->setup_id ?>"><h3><?= $setup->setup->title ?></h3></a>

	                        </div>

	                    </div>
	                </div>
	            </div>

	<?php endforeach; }?>

	</div>
</div>
</div>
<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', __('Search for "') . $_GET["q"] . '" | mySetup.co');
?>

<div class="maincontainer">
	<div class="row">
		<div class="column column-75">
			<?php
				if($setups == "noquery")
				{
					echo "<h3>No search query, no results :(</h3>";
				}

				elseif($setups == "noresult")
				{
					echo "<h3>We haven't found any results for this query :(</h3>";
				}

				else
				{
			?>

			<div class="large_search">
				<input type="text" id="keyword-search" placeholder="<?= __('Search a component... Find a cool setup !') ?>" /> 
				<?= $this->Html->scriptBlock('let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>
			</div>

			<h3><?= __('Search results for') ?> "<?= $_GET["q"] ?>" :</h3>

			<?php foreach ($setups as $setup): ?>

				<div class="fullitem">
				<a href="<?= $this->Url->build('/setups/'.$setup->setup_id.'-'.$this->Text->slug($setup->setup->title)); ?>">
					<img src="<?= $this->Url->build('/', true)?><?= $setup->src ?>">
				</a>
				<div class="fullitem-inner">
					<div class="row">
						<div class="column column-75">
							<a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->setup->user_id)?>">
								<img src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $setup->setup->user_id ?>.png">
							</a>
							<a href="<?= $this->Url->build('/setups/'.$setup->setup_id.'-'.$this->Text->slug($setup->setup->title)); ?>">
								<h3><?= $setup->setup->title ?></h3>
							</a>
						</div>
					</div>
				</div>
				</div>

			<?php endforeach; }?>
		</div>

		<div class="column column-25 sidebar">
			<div class="twitter-feed">
              <a class="twitter-timeline" data-chrome="noscrollbar nofooter noboders" data-height="781" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co"><?= __('Tweets by @mysetup_co') ?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
		</div>
	</div>
</div>

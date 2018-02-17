<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', __('Search for') . ' "' . ($this->request->getQuery('q') ? $this->request->getQuery('q') : "") . '" | mySetup.co');
?>
<div class="container sitecontainer">
<div class="maincontainer">
    <div class="row">
        <div class="column column-75">

            <div class="large_search">
                <input type="text" id="keyword-search" placeholder="<?= h(($this->request->getQuery('q') ? $this->request->getQuery('q') : "")) ?>" />
                <?= $this->Html->scriptBlock('let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>
            </div>

            <?php
                if($results == "noquery")
                {
                    echo "<h4>" . __("No search query, no results :(") . "</h4>";
                }

                elseif($results == "noresult")
                {
                    echo "<h4>" . __("We haven't found any results for this query :(") . "</h4>";
                }

                else
                {
            ?>

            <?php dump($results) ?>

            <?php if(isset($results["resources"])): $resources = $results["resources"]; ?>
                <div class="rowfeed">
                    <?php foreach ($resources as $item): ?>
                        <a href="<?= $this->Url->build('/search/?q=' . $item->title) ?>"><div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div></a>
                    <?php endforeach?>
                </div>
            <?php endif;?>

             <br clear='all'>

            <?php if(isset($results["setups"])): foreach ($results["setups"] as $setup): ?>
                <?= $this->element('List/tiles', ['setup' => $setup]) ?>
            <?php endforeach; endif;?>

             <br clear='all'>

            <?php if(isset($results["users"])): $foundUsers = $results["users"]; ?>
                <div class="activeUsers">
                    <?php foreach($foundUsers as $foundUser): ?>

                        <a class="featured-user" href="<?=$this->Url->build('/users/'.$foundUser->id)?>">
                            <img alt="<?= __('Profile picture of') ?> <?= $foundUser->name ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $foundUser->id . '.png?' . $this->Time->format($foundUser->modificationDate, 'mmss', null, null)); ?>">
                        </a>

                    <?php endforeach ?>
                </div>
            <?php endif;?>

            <?php }?>
        </div>

        <div class="column column-25 sidebar sidebar-search">
            <div class="blog-advert">
                <a href="<?=$this->Url->build('/blog/')?>">
                  <h5><i class="fa fa-newspaper-o"></i><br><?= __('Read our latest news') ?></h5>
                </a>
            </div>

            <div class="twitter-feed">
              <a class="twitter-timeline" data-chrome="noscrollbar nofooter noboders" data-height="781" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co"><?= __('Tweets by @mysetup_co') ?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
        </div>
    </div>
</div>
</div>

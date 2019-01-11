<?php
/**
  * @var \App\View\AppView $this
  */

$this->assign('title', __('Search for') . ' "' . h($this->request->getQuery('q') ? $this->request->getQuery('q') : "") . '" | mySetup.co');

echo $this->Html->meta('description', __('Find all setups, components or users related to ') . h($this->request->getQuery('q') ? $this->request->getQuery('q') : ""), ['block' => true]);
?>

<div class="colored-container">
    <div class="container">
        <h2><?= __('Search for') ?> :</h2>

        <div class="large_search">
            <i class="fa fa-search"></i>
            <input type="text" id="keyword-search" value="<?= h(($this->request->getQuery('q') ? $this->request->getQuery('q') : "")) ?>" placeholder="<?= __('Search a component... Find a cool setup !') ?>" />
            <?= $this->Html->scriptBlock('let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>
        </div>

        <br clear="all">
    </div>
</div>

<div class="search-tabs">
    <ul>
        <span class="pushli"></span>
        <?php
            $queries = '';
            foreach($this->request->getQuery() as $id => $query)
            {
                if($queries)
                {
                    $queries .= '&';
                }

                $queries .= $id . '=' . $query;
            }

            if($queries)
            {
                $queries = '?' . $queries;
            }
        ?>
        <li <?php if($this->request->getPath() == '/search/'): ?>class="active"<?php endif; ?>>
            <a href="<?=$this->Url->build('/search/' . h($queries))?>">
                <?= __('All') ?>
            </a>
        </li>
        <li <?php if($this->request->getPath() == '/search/setups'): ?>class="active"<?php endif; ?>>
            <a href="<?=$this->Url->build('/search/setups' . h($queries))?>">
                <?= __('Setups') ?>
            </a>
        </li>
        <li <?php if($this->request->getPath() == '/search/resources'): ?>class="active"<?php endif; ?>>
            <a href="<?=$this->Url->build('/search/resources' . h($queries))?>">
                <?= __('Components') ?>
            </a>
        </li>
        <li <?php if($this->request->getPath() == '/search/users'): ?>class="active"<?php endif; ?>>
            <a href="<?=$this->Url->build('/search/users' . h($queries))?>">
                <?= __('Users') ?>
            </a>
        </li>
        <span class="pushli"></span>
    </ul>
</div>

<div class="container">
    <div class="maincontainer">
        <div class="row">
            <div class="column column-75 search-container">

                <?php
                    if(isset($results["error"]))
                    {
                        if($results["error"] == "noquery")
                        {
                            echo "<h4>" . __("No search query, no results :(") . "</h4>";
                        }

                        elseif($results["error"] == "noresult")
                        {
                            echo "<h4>" . __("We haven't found any results for this query :(") . "</h4>";
                        }
                    }
                    else
                    {
                ?>

                <?php if(isset($results["resources"]) && count($results["resources"]) > 0): $resources = $results["resources"]; ?>

                        <?php if(count($resources, COUNT_RECURSIVE) == 1): ?>
                            <a href="<?= urldecode($resources[0]->href) ?>" target="_blank"><div class="item_box float-left" style="background-image: url(<?= urldecode($resources[0]->src) ?>)"></div></a>
                            <span><?= __('All setups related to') ?> :</span>
                            <h4><?= urldecode(h($resources[0]->title)) ?> <a href="<?= urldecode($resources[0]->href) ?>" target="_blank"> <i class="fa fa-shopping-bag"></i></a></h4>
                        <?php else:?>
                            <h3><?= __('Found components') ?></h3>
                            <div class="config-items">
                                <?php foreach ($resources as $k => $item): ?>
                                    <a href="<?= $this->Url->build('/search/?q=' . h($item->title)) ?>"><div class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></div></a>
                                <?php if($this->request->getPath() == '/search/' && $k > 6){break;} endforeach;?>
                            </div>
                        <?php endif;?>
                        <br clear="all">

                    <br clear="all">
                <?php endif;?>

                <?php if(isset($results["setups"]) && count($results["setups"]) > 0): ?>
                    <h3><?= __('Found setups') ?></h3>
                    <?php foreach ($results["setups"] as $k => $setup): ?>
                        <?= $this->element('List/tiles', ['setup' => $setup]) ?>
                    <?php if($this->request->getPath() == '/search/' && $k > 8){break;} endforeach;?>
                    <br clear='all'>
                <?php endif;?>

                <?php if(isset($results["users"]) && count($results["users"]) > 0): ?>
                    <h3><?= __('Found users') ?></h3>
                    <div class="activeUsers">
                        <?php foreach($results["users"] as $foundUser): ?>
                            <div class="featured-user">
                                <a href="<?=$this->Url->build('/users/'.$foundUser->id)?>">
                                    <img alt="<?= __('Profile picture of') ?> <?= h($foundUser->name) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $foundUser->id . '.png?' . $this->Time->format($foundUser->modificationDate, 'mmss', null, null)); ?>">
                                <span>
                                    <strong><?= h($foundUser->name) ?> <?php if($foundUser->verified): echo '<i class="fa fa-check-circle verified_account"></i>'; endif ?></strong>
                                    <span></span>
                                </span>
                                </a>
                            </div>

                        <?php endforeach ?>
                    </div>
                <?php endif;?>

                <?php }?>
            </div>
        </div>
    </div>
</div>

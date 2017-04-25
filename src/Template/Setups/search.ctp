<?php
/**
  * @var \App\View\AppView $this
  */
?>

<?php foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/view/', true)?><?= $setup->setup_id ?>">
                    <img src="<?= $this->Url->build('/', true)?><?= $setup->src ?>">
                </a>
                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="#">
                                <img src="https://avatars1.githubusercontent.com/u/4266283?v=3&s=460">
                            </a>

                            <a href="<?= $this->Url->build('/setups/view/', true)?><?= $setup->setup_id ?>"><h3><?= $setup->setup->title ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

<?php endforeach ?>
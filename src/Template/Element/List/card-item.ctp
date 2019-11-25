<article class="item-grid">

    <header class="header">

        <a class="user-avatar" href="<?= $this->Url->build('/users/' . $setup->user_id . '-' . $this->Text->slug($setup->user ? $setup->user->name : $user->name)) ?>">
            <img alt="<?= __('Profile picture of') ?> <?= h($setup->user ? $setup->user->name : $user->name) ?>" width="40" height="40" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user ? $setup->user->modificationDate : $user->modificationDate, 'mmss', null, null)); ?>">
        </a>

        <div class="setup-meta">
            <span class="setup-title">
                <a href="<?= $this->Url->build('/setups/' . $setup->id . '-' . $this->Text->slug($setup->title)) ?>">
                    <?php if ($setup->status == 'DRAFT') : ?>
                        <i title="<?= __('Only you can see this setup') ?>" class="fa fa-eye-slash setup-unpublished"></i>
                    <?php elseif ($setup->status == 'REJECTED') : ?>
                        <i title="<?= __('Your setup has been rejected. You will find the reason within its description.') ?>" class="fa fa-ban setup-rejected"></i>
                    <?php endif ?>
                    <?= h($setup->title) ?>
                </a>
            </span>
            <span class="setup-author"><?= h($setup->user ? $setup->user->name : $user->name) ?></span>
        </div>

        <div class="setup-like-box">
            <i class="fa fa-thumbs-up"></i> <?= $setup->like_count ?>
        </div>

    </header>

    <a href="<?= $this->Url->build('/setups/' . $setup->id . '-' . $this->Text->slug($setup->title)) ?>">
        <img class="setup-image" alt="<?= h($setup->title) ?>" src="<?= $this->Url->build('/' . (!empty($setup->resources[0]) ? $setup->resources[0]->src : 'img/not_found.jpg')) ?>">
    </a>

</article>
<div>
	<div class="container">

		<div class="setup-showcase">
			<h4><a href="<?= $this->Url->build('/setups/' . $setup->id . '-' . $this->Text->slug($setup->title)); ?>"><?= h($setup->title) ?></a></h4>
			<div class="showcase-meta">
				<div class="showcase-avatar">
					<a class="author-avatar" href="<?= $this->Url->build('/users/' . $setup->user_id . '-' . $this->Text->slug($setup->user->name)) ?>">
						<img alt="<?= __('Profile picture of') ?> <?= h($setup->user->name) ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user->modificationDate, 'mmss', null, null)); ?>">
					</a>
				</div>
				<div class="showcase-author">
					<div class="author-name-prefix"><?= __('Creator') ?></div>
					<a class="author-name" href="<?= $this->Url->build('/users/' . $setup->user_id . '-' . $this->Text->slug($setup->user->name)) ?>"><?= h($setup->user->name) ?></a>
				</div>
				<div class="showcase-likes"><?= $setup->like_count ?> <?= __n('like', 'likes', $setup->like_count) ?></div>
			</div>
			<div class="showcase-content">
				<div class="showcase-description"></div>
				<div class="showcase-products">
					<div class="config-items">
						<?php foreach ($setup['products'] as $item) : ?>

							<a target="_blank" href="<?= $this->Url->build('/setups/' . $setup->id, true) ?>" class="item_box" style="background-image: url(<?= urldecode($item->src) ?>)"></a>

						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
		<div class="setup-showcase-background" style="background-image: radial-gradient(ellipse closest-side, rgba(0, 0, 0, 0.60), #151515), url(<?= $this->Url->build('/' . (!empty($setup->resources[0]) ? $setup->resources[0]->src : 'img/not_found.jpg')) ?>"></div>

	</div>
</div>
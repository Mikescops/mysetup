<div id="embed_twitch_modal" class="lity-hide">
    <h4><?= __('How to embed your setup in Twitch ?') ?></h4>
    <p><?= __('The simpliest way to add your setup under your Twitch stream is to use our official extension.') ?></p>

    <a class="button" href="https://www.twitch.tv/ext/nx4cn1zq653a759wfy4aku0fgmql8l-1.0.1" target="_blank"><i class="fa fa-shopping-bag"></i>  <?= __('Install via Twitch MarketPlace') ?></a>

    <br clear="all"><hr>

    <h5><?= __('Or, use an image instead of the extension') ?></h5>
    <p><?= __('Go to your Twitch channel and toggle panel edition.') ?></p>
    <?= $this->Html->image('howto_twitch.png', array('alt' => 'Twitch Panel Edition')) ?> <br>
    <p><?= __('Copy the following url in the link field') ?> :</p>
    <pre><code><span><?= $this->Url->build('/setups/'.$setup->id."-".$this->Text->slug($setup->title).'?ref='.urlencode(h($setup->user['name'])), true)?></span></code></pre>
    <p><?= __('And add your personal mySetup.co banner image !') ?></p>
    <p style="text-align: center;"><img alt="<?= ('Advert - Setup by') ?> <?= h($setup->user['name']) ?>" src="<?= $this->Url->build(['controller' => 'api', 'action' => 'twitchPromote', '?' => ['id' => $setup->id]]) ?>"></p>

    <p><?= __('You can even configure your Twitch Chat bot to display your link or image.') ?></p>
</div>
<?php

$this->layout = 'default';
$this->assign('title', __('Latest Setups | mySetup.co'));

echo $this->Html->meta('description', __('See the most recent setups published on mySetup.co'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'Latest Setups | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'See the most recent setups published on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'See the most recent setups published on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/recent', true)], null ,['block' => true]);

?>
<div class="colored-container">
    <div class="container">
        <br><h2><?= __('Latest setups') ?></h2><br>
    </div>
</div>
<div class="container">
    <div class="maincontainer">

    <div class="row">
        <div class="column column-75">

<?php

  $options = [
    CURLOPT_URL => $this->Url->build('/', true) . 'app/getsetups?o=DESC&n=6',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
    CURLOPT_FAILONERROR => true,
    CURLOPT_HTTPHEADER => [
      'Content-Type: application/json',
      'Accept: application/json'
    ]
  ];

  $curl = curl_init();
  if(empty($curl))
  {
    die("ERROR (curl_init) : It looks like cURL is not available yet.");
  }

  curl_setopt_array($curl, $options);
  $setups = curl_exec($curl);

  if(curl_errno($curl))
  {
    die("ERROR (curl_exec) : " . curl_error($curl));
  }

  else
  {
    $setups = json_decode($setups);
  }

  curl_close($curl);
?>

            <div class="fullitem_holder">

            <?php foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= h($setup->title) ?>" src="<?= $this->Url->build('/', true)?><?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> <?= $setup->user->name ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_' . $setup->user_id . '.png?' . $this->Time->format($setup->user->modificationDate, 'mmss', null, null)); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach ?>

            </div>

            <p class="no_more_setups"></p>

            <?= $this->Html->scriptBlock('infiniteScroll(6);', array('block' => 'scriptBottom')); ?>


        </div>
        <div class="column column-25 sidebar sidebar-feed">

            <div class="blog-advert">
              <a href="<?=$this->Url->build('/blog/')?>">
                <h5><i class="fa fa-newspaper-o"></i><br><?= __('Read our latest news') ?></h5>
              </a>
            </div>

            <div class="social-networks">
                <a href="https://www.facebook.com/mysetup.co" target="_blank" style="background-color: #3b5998"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://twitter.com/mysetup_co" target="_blank" style="background-color: #55acee"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="https://geeks.one/@mysetup_co" title="Mastodon" target="_blank" style="background-color: #45668e"><img style="height:70px;margin-top:35px" src="/img/mastodon_logo.svg"></a>
            </div>
        </div>
    </div>

</div>
</div>

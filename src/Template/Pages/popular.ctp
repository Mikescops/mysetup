<?php

$this->layout = 'default';
$this->assign('title', __('Popular this week | mySetup.co'));

echo $this->Html->meta('description', __('The most popular setups of the week on mySetup.co'), ['block' => true]);


echo $this->Html->meta(['property' => 'og:title', 'content' => 'Popular this week | mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'The most popular setups of the week on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'The most popular setups of the week on mySetup.co'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/popular', true)], null ,['block' => true]);


?>
    <div class="maincontainer">

    <div class="row">
        <div class="column column-75">

<?php
 
  $options = [
    CURLOPT_URL => $this->Url->build('/', true) . 'app/getsetups?o=DESC&t=like&w=1',
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


            <h3><?= __('Popular this week') ?></h3>

            <div class="fullitem_holder">

            <?php foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= $setup->title ?>" src="<?= $this->Url->build('/', true)?><?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> #<?= $setup->user_id ?>" src="<?= $this->Url->build('/'); ?>uploads/files/pics/profile_picture_<?= $setup->user_id ?>.png">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= $setup->title ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php endforeach ?>

            </div>

        </div>
        <div class="column column-25 sidebar sidebar-feed">

            <div class="blog-advert">
              <a href="<?=$this->Url->build('/blog/')?>">
                <h5><i class="fa fa-newspaper-o"></i><br><?= __('Read our latest news') ?></h5>
              </a>
            </div>

            <div class="twitter-feed">
              <a class="twitter-timeline" data-chrome="noscrollbar nofooter noboders" data-height="781" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co"><?= __('Tweets by @mysetup_co') ?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>

            <div class="social-networks">
                <a href="https://www.facebook.com/mysetup.co" target="_blank"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://twitter.com/mysetup_co" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="mailto:support@mysetup.co" title="Report a bug !"><i class="fa fa-bug fa-2x"></i></a>
            </div>

        </div>
    </div>

</div>
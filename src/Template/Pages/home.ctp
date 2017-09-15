<?php

$this->layout = 'default';
$this->assign('title', __('mySetup | Share your own setup'));

echo $this->Html->meta('description', __('The best place to share your computer setup with your community ! Inspire others or get inspired with gaming setups, battlestations...'), ['block' => true]);

echo $this->Html->meta(['property' => 'og:title', 'content' => 'mySetup | Share your own setup'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'The best place to share your "my setup" with your community ! Inspire others or get inspired with gaming setups, battlestations...'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'The best place to share your "my setup" with your community ! Inspire others or get inspired with gaming setups, battlestations...'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/', true)], null ,['block' => true]);

?>

<?php
 
  $options = [
    CURLOPT_URL => $this->Url->build('/', true) . 'app/getsetups?f=1&n=5',
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
  $fsetups = curl_exec($curl);

  if(curl_errno($curl))
  {
    die("ERROR (curl_exec) : " . curl_error($curl));
  }

  else
  {
    $fsetups = json_decode($fsetups);
  }

  curl_close($curl);
?>

<div class="home_slider_container sitecontainer">

  <div class="container home_slider">

    <?php foreach ($fsetups as $fsetup): ?>
                
        <div class="slider-item">
            <a href="<?= $this->Url->build('/setups/'.$fsetup->id.'-'.$this->Text->slug($fsetup->title)); ?>"><img alt="<?= $fsetup->title ?>" src="<?= $fsetup->resources[0]->src ?>"></a>
            <a class="slider-item-inner featured-user" href="<?=$this->Url->build('/users/'.$fsetup->user_id)?>">
                <img alt="<?= __('Profile picture of') ?> #<?= $fsetup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$fsetup->user_id.'.png'); ?>">
            </a>
            <div class="red_like"><i class="fa fa-heart"></i> <?php if(!empty($fsetup->likes[0])){echo $fsetup->likes[0]->total;}else{echo 0;} ?></div>
        </div>

    <?php endforeach ?>

  </div>

</div>


<div class="container">

    <div class="maincontainer">

      <div class="large_search" style="margin-top: -60px"> <i class="fa fa-search"></i>
        
        <input type="text" id="keyword-search" placeholder="<?= __('Search a component... Find a cool setup !') ?>" /> 
        <?= $this->Html->scriptBlock(' let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`setups/search?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>

      </div>

    <div class="rowfeed">
        <div class="feeditem">
        <?php
         
          $options = [
            CURLOPT_URL => $this->Url->build('/', true) . 'app/getsetups?t=like&n=20',
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

            <?php $i=0; foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= h($setup->title) ?>" src="<?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> #<?= $setup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$setup->user_id.'.png'); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php if (++$i == 3) break; endforeach ?>
        </div>
        <a class="home_more float-right" href="<?= $this->Url->build('/pages/popular'); ?>"><?= __('More popular setups') ?> <i class="fa fa-chevron-right"></i></a>
    </div>

    <br clear='all'>

    <div class="rowsocial">
      <?php
          $lang = ($authUser && $authUser['preferredStore'] !== "US" && $authUser['preferredStore'] !== "UK" ? strtolower($authUser['preferredStore']) : "en")
      ?>

      <?php if(!$authUser): ?>
        <div class="twitch-advert" onclick="logTwitch('<?= $lang ?>')">
          <h4><i class="fa fa-twitch"></i> <?= __('Login with Twitch and create my Setup !') ?></h4>
        </div>
      <?php else: ?>
          <div class="blog-advert">
            <a href="<?=$this->Url->build('/blog/')?>">
              <h5><i class="fa fa-newspaper-o"></i> <?= __('Read our latest news') ?></h5>
            </a>
          </div>
      <?php endif ?>

      <div class="social-networks">
          <a href="https://www.facebook.com/mysetup.co" target="_blank" style="background-color: #3b5998"><i class="fa fa-facebook fa-2x"></i></a>
          <a href="https://twitter.com/mysetup_co" target="_blank" style="background-color: #55acee"><i class="fa fa-twitter fa-2x"></i></a>
          <a href="https://geeks.one/@mysetup_co" title="Mastodon" target="_blank" style="background-color: #45668e"><img style="height:70px;margin-top:35px" src="/img/mastodon_logo.svg"></a>
      </div>
    </div>

    <div class="rowfeed">
        <h4 class="fancy"><span><?= __('Latest setups') ?></span></h4>
        <div class="feeditem">

        <?php
         
          $options = [
            CURLOPT_URL => $this->Url->build('/', true) . 'app/getsetups?n=3',
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

            <?php $i=0; foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= h($setup->title) ?>" src="<?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> #<?= $setup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$setup->user_id.'.png'); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php if (++$i == 8) break; endforeach ?>
        </div>
        <a class="home_more float-right" href="<?= $this->Url->build('/pages/recent'); ?>"><?= __('More recent setups') ?> <i class="fa fa-chevron-right"></i></a>
    </div>

    <br clear='all'>

    <div class="rowfeed">
        <h4 class="fancy"><span>AMD</span></h4>
        <div class="feeditem">

        <?php $options=[ CURLOPT_URL=>$this->Url->build('/',true).'app/getsetups?t=like&n=10&q=amd',CURLOPT_RETURNTRANSFER=>true,CURLOPT_HEADER=>false,CURLOPT_FAILONERROR=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json','Accept: application/json'] ];$curl=curl_init();if(empty($curl)){die("ERROR (curl_init) : It looks like cURL is not available yet.");}curl_setopt_array($curl,$options);$setups=curl_exec($curl);if(curl_errno($curl)){die("ERROR (curl_exec) : ".curl_error($curl));}else{$setups=json_decode($setups);}curl_close($curl);?>

            <?php $i = 0; foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= h($setup->title) ?>" src="<?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> #<?= $setup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$setup->user_id.'.png'); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php if (++$i == 3) break; endforeach ?>
        </div>
        <a class="home_more float-right" href="<?= $this->Url->build('/setups/search?q=amd'); ?>"><?= __('More AMD setups') ?> <i class="fa fa-chevron-right"></i></a>
    </div>

    <br clear='all'>

    <div class="rowfeed">
        <h4 class="fancy"><span>Nvidia</span></h4>
        <div class="feeditem">

        <?php $options=[ CURLOPT_URL=>$this->Url->build('/',true).'app/getsetups?t=like&n=10&q=nvidia',CURLOPT_RETURNTRANSFER=>true,CURLOPT_HEADER=>false,CURLOPT_FAILONERROR=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json','Accept: application/json'] ];$curl=curl_init();if(empty($curl)){die("ERROR (curl_init) : It looks like cURL is not available yet.");}curl_setopt_array($curl,$options);$setups=curl_exec($curl);if(curl_errno($curl)){die("ERROR (curl_exec) : ".curl_error($curl));}else{$setups=json_decode($setups);}curl_close($curl);?>

            <?php $i = 0; foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= h($setup->title) ?>" src="<?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="<?= __('Profile picture of') ?> #<?= $setup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$setup->user_id.'.png'); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= h($setup->title) ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php if (++$i == 3) break; endforeach ?>
        </div>
        <a class="home_more float-right" href="<?= $this->Url->build('/setups/search?q=nvidia'); ?>"><?= __('More Nvidia setups') ?> <i class="fa fa-chevron-right"></i></a>
    </div>

    <br clear='all'>

    <div class="rowfeed">
        <h4 class="fancy"><span><?= __('Suggested Users') ?></span></h4>
        <div class="activeUsers">

        <?php $options=[ CURLOPT_URL=>$this->Url->build('/',true).'app/getActiveUsers?n=12',CURLOPT_RETURNTRANSFER=>true,CURLOPT_HEADER=>false,CURLOPT_FAILONERROR=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json','Accept: application/json'] ];$curl=curl_init();if(empty($curl)){die("ERROR (curl_init) : It looks like cURL is not available yet.");}curl_setopt_array($curl,$options);$activeUsers=curl_exec($curl);if(curl_errno($curl)){die("ERROR (curl_exec) : ".curl_error($curl));}else{$activeUsers=json_decode($activeUsers);}curl_close($curl);?>

            <?php foreach ($activeUsers as $activeUser): ?>

            <a class="featured-user" href="<?=$this->Url->build('/users/'.$activeUser->user_id)?>">
                <img alt="<?= __('Profile picture of') ?> #<?= $activeUser->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$activeUser->user_id.'.png'); ?>">
            </a>

            <?php endforeach ?>
        </div>
    </div>

    <br clear='all'>


</div>
</div>
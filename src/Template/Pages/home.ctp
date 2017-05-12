<?php

$this->layout = 'default';
$this->assign('title', 'mySetup.co | Share your own setup');

echo $this->Html->meta('description', 'The best place to share your setup with your community !', ['block' => true]);


echo $this->Html->meta(['property' => 'og:title', 'content' => 'mySetup.co | Share your own setup'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:description', 'content' => 'The best place to share your setup with your community !'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:description', 'content' => 'The best place to share your setup with your community !'], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'twitter:image', 'content' => $this->Url->build('/img/mysetup_header.jpg', true)], null ,['block' => true]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $this->Url->build('/', true)], null ,['block' => true]);

?>

<?php
$url=$this->Url->build('/', true) . "app/getsetups?f=1&n=5"; 
$options=array(
      CURLOPT_URL            => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HEADER         => false,
      CURLOPT_FAILONERROR    => true, 
      CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json')
);
$CURL=curl_init();
if(empty($CURL)){die("ERREUR curl_init : Il semble que cURL ne soit pas disponible.");}
      curl_setopt_array($CURL,$options);
      $fsetups=curl_exec($CURL);
      if(curl_errno($CURL)){
            echo "ERREUR curl_exec : ".curl_error($CURL);
      }
      $fsetups = json_decode($fsetups);
      //var_dump($fsetups);
curl_close($CURL);
 
?>

<div class="home_slider">

<?php foreach ($fsetups as $fsetup): ?>
            
    <div class="slider-item">
        <a href="<?= $this->Url->build('/setups/'.$fsetup->id.'-'.$this->Text->slug($fsetup->title)); ?>"><img alt="<?= $fsetup->title ?>" src="<?= $fsetup->resources[0]->src ?>"></a>
        <a class="slider-item-inner featured-user" href="<?=$this->Url->build('/users/'.$fsetup->user_id)?>">
            <img alt="Profile picture of #<?= $fsetup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$fsetup->user_id.'.png'); ?>">
        </a>
        <div class="red_like"><i class="fa fa-heart"></i> <?php if(!empty($fsetup->likes[0])){echo $fsetup->likes[0]->total;}else{echo 0;} ?></div>
    </div>

<?php endforeach ?>

</div>

    <div class="maincontainer">

      <div class="large_search">
        
        <input type="text" id="keyword-search" placeholder="<?= __('Search a component... Find a cool setup !') ?>" /> 
        <?= $this->Html->scriptBlock(' let searchInput = new AmazonAutocomplete("#keyword-search");searchInput.onSelectedWord(word => window.open(`setups/search?q=${word}`, "_self"));', array('block' => 'scriptBottom')); ?>

      </div>


    <div class="row">
        <div class="column column-75">

<?php
$url= $this->Url->build('/', true) . "app/getsetups?t=like&n=20"; 
 
// Tableau contenant les options de téléchargement
$options=array(
      CURLOPT_URL            => $url,  // Url cible (l'url la page que vous voulez télécharger)
      CURLOPT_RETURNTRANSFER => true,  // Retourner le contenu téléchargé dans une chaine (au lieu de l'afficher directement)
      CURLOPT_HEADER         => false, // Ne pas inclure l'entête de réponse du serveur dans la chaine retournée
      CURLOPT_FAILONERROR    => true,   // Gestion des codes d'erreur HTTP supérieurs ou égaux à 400
      CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json'
) // Gestion du json
);
 
// Création d'un nouvelle ressource cURL
$CURL=curl_init();
// Erreur suffisante pour justifier un die()
if(empty($CURL)){die("ERREUR curl_init : Il semble que cURL ne soit pas disponible.");}
 
      // Configuration des options de téléchargement
      curl_setopt_array($CURL,$options);
 
      // Exécution de la requête
      $setups=curl_exec($CURL);       // Le contenu téléchargé est enregistré dans la variable $content.
 
      // Si il s'est produit une erreur lors du téléchargement
      if(curl_errno($CURL)){
            // Le message d'erreur correspondant est affiché
            echo "ERREUR curl_exec : ".curl_error($CURL);
      }

      $setups = json_decode($setups);

      //var_dump($setups);
 
// Fermeture de la session cURL
curl_close($CURL);
 
?>
            <?php $i=0; foreach ($setups as $setup): ?>

            <div class="fullitem">
                <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>">
                    <img alt="<?= $setup->title ?>" src="<?= $setup->resources[0]->src ?>">
                </a>
                <div class="red_like"><i class="fa fa-heart"></i>  <?php if(!empty($setup->likes[0])){echo $setup->likes[0]->total;}else{echo 0;} ?></div>

                <div class="fullitem-inner">

                    <div class="row">

                        <div class="column column-75">
                            <a class="featured-user" href="<?=$this->Url->build('/users/'.$setup->user_id)?>">
                                <img alt="Profile picture of #<?= $setup->user_id ?>" src="<?= $this->Url->build('/uploads/files/pics/profile_picture_'.$setup->user_id.'.png'); ?>">
                            </a>

                            <a href="<?= $this->Url->build('/setups/'.$setup->id.'-'.$this->Text->slug($setup->title)); ?>"><h3><?= $setup->title ?></h3></a>

                        </div>

                    </div>
                </div>
            </div>

            <?php if (++$i == 8) break; endforeach ?>

          <a class="button home_more float-right" href="<?= $this->Url->build('/pages/recent'); ?>"><?= __('Need more ? Click to see the latest !') ?></a>
        </div>
        <div class="column column-25 sidebar">

            <div class="social-networks">
                <a href="https://www.facebook.com/mysetup.co" target="_blank"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="https://twitter.com/mysetup_co" target="_blank"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="https://medium.com/mysetup-co" target="_blank"><i class="fa fa-medium fa-2x"></i></a>
                <a href="mailto:support@mysetup.co" title="Report a bug !"><i class="fa fa-bug fa-2x"></i></a>
            </div>

            <a class="twitter-timeline" data-chrome="noscrollbar nofooter noboders" data-height="766" data-dnt="true" data-theme="dark" href="https://twitter.com/mysetup_co"><?= __('Tweets by @mysetup_co') ?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

        </div>
    </div>

</div>
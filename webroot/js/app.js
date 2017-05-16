/*!
 * mySetup v1.0.0
 * https://mysetup.co
 *
 * Copyright (c) 2017 Corentin Mors / Samuel Forestier
 * All rights reserved
 */

/** SLIDERS **/

$('.home_slider').slick({
  centerMode: true,
  infinite: true,
  arrows: false,
  autoplay: true,
  autoplaySpeed: 4000,
  centerPadding: '200px',
  slidesToShow: 1,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        centerPadding: '40px',
        slidesToShow: 1
      }
    }
  ]
});


$('.post_slider').slick({
  lazyLoad: 'ondemand',
  arrows: true,
  infinite: false,
  slidesToShow: 2,
  responsive: [
    {
      breakpoint: 768,
      settings: {
        arrows: false,
        slidesToShow: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        arrows: false,
        centerMode: true,
        slidesToShow: 1
      }
    }
  ]
});

/***** Login area tabs *****/
$(function() {
  // constants
  var SHOW_CLASS = 'show',
      HIDE_CLASS = 'hide',
      ACTIVE_CLASS = 'active';
  
  $( '.tabs' ).on( 'click', 'li a', function(e){
    e.preventDefault();
    var $tab = $( this ),
         href = $tab.attr( 'href' );
  
     $( '.active' ).removeClass( ACTIVE_CLASS );
     $tab.addClass( ACTIVE_CLASS );
  
     $( '.show' )
        .removeClass( SHOW_CLASS )
        .addClass( HIDE_CLASS )
        .hide();
    
      $(href)
        .removeClass( HIDE_CLASS )
        .addClass( SHOW_CLASS )
        .hide()
        .fadeIn( 550 );
  });
  $( '.form-action' ).on( 'click', '.next', function(e){
    e.preventDefault(); 
    var $next = $( this ),
         href = $next.attr( 'href' ),
         $tab = $( href + '-tab' );
  
     $( '.active' ).removeClass( ACTIVE_CLASS );
     $tab.addClass( ACTIVE_CLASS );
  
     $( '.show' )
        .removeClass( SHOW_CLASS )
        .addClass( HIDE_CLASS )
        .hide();
    
      $(href)
        .removeClass( HIDE_CLASS )
        .addClass( SHOW_CLASS )
        .hide()
        .fadeIn( 550 );
  });


});

/***** On load functions *****/
$(function(){
   $('.is_author').click(function(){
      $(this).hide();
      $('.setup_author').show('fast');
      return false;
   });
    $('.reset_pwd').click(function(){
      $(this).hide();
      $('.pwd_field').show('fast');
      return false;
   });
    $("#social-networks").jsSocials({
      shareIn: "popup",
      showCount: false,
      showLabel: false,
      shares: ["twitter", "facebook", "googleplus", "pinterest", "whatsapp"]
    });

    $("#profileImage").click(function(e) {
      $("#profileUpload").click();
    });
    $("#profileUpload").change(function(){
      fasterPreview( this );
    });
    $("#featuredimage").change(function(){
      featuredPreview( this );
    });
    $("#featuredImage_edit").change(function(){
      featuredPreview_edit( this );
    });

    $("#featuredimage_preview").click(function(e) {
      $(".label_fimage").click();
    });

    /***** Preview galery edit *****/
    $("#gallery0image_preview_edit").click(function(e) {
      $("#gallery0").click();
    });
    $("#gallery1image_preview_edit").click(function(e) {
      $("#gallery1").click();
    });
    $("#gallery2image_preview_edit").click(function(e) {
      $("#gallery2").click();
    });
    $("#gallery3image_preview_edit").click(function(e) {
      $("#gallery3").click();
    });
    $("#gallery4image_preview_edit").click(function(e) {
      $("#gallery4").click();
    });

    $("#gallery0").change(function(){
      galleryPreview_edit( this, 0 );
    });
    $("#gallery1").change(function(){
      galleryPreview_edit( this, 1 );
    });
    $("#gallery2").change(function(){
      galleryPreview_edit( this, 2 );
    });
    $("#gallery3").change(function(){
      galleryPreview_edit( this, 3 );
    });
    $("#gallery4").change(function(){
      galleryPreview_edit( this, 4 );
    });

    /* Same for add */
    $("#gallery0image_preview_add").click(function(e) {
      $("#gallery0add").click();
    });
    $("#gallery1image_preview_add").click(function(e) {
      $("#gallery1add").click();
    });
    $("#gallery2image_preview_add").click(function(e) {
      $("#gallery2add").click();
    });
    $("#gallery3image_preview_add").click(function(e) {
      $("#gallery3add").click();
    });
    $("#gallery4image_preview_add").click(function(e) {
      $("#gallery4add").click();
    });

    $("#gallery0add").change(function(){
      galleryPreview_add( this, 0 );
    });
    $("#gallery1add").change(function(){
      galleryPreview_add( this, 1 );
    });
    $("#gallery2add").change(function(){
      galleryPreview_add( this, 2 );
    });
    $("#gallery3add").change(function(){
      galleryPreview_add( this, 3 );
    });
    $("#gallery4add").change(function(){
      galleryPreview_add( this, 4 );
    });


    $(".edit-comment").click(function(e) {
      var $comment = $( this ),
        $place = $comment.attr( 'source' );
      var $oldvalue = $('#' + $place + '> p').text(),
        $id = $place.replace(/[^0-9]/g, '');

        var $code = '<form enctype="multipart/form-data" method="post" accept-charset="utf-8" action="/mysetup/comments/edit/'+ $id +'"><div class="input textarea required"><textarea name="content" maxlengh="500" required="required" rows="5">'+ $oldvalue +'</textarea></div></form>';
        console.log($code);

        $('#' + $place).replaceWith($code);


    });
});

/********* Update Profile Picture on Upload *********/

function fasterPreview( uploader ) {
    if ( uploader.files && uploader.files[0] ){
          $('#profileImage').attr('src', 
             window.URL.createObjectURL(uploader.files[0]) );
    }
}

/***************** Image preview on modal ****************/

function featuredPreview( uploader ) {
    if ( uploader.files && uploader.files[0] ){
          $('#featuredimage_preview').attr('src', 
             window.URL.createObjectURL(uploader.files[0]) );
    }
    $(".label_fimage_add").hide();
}

function featuredPreview_edit( uploader ) {
    if ( uploader.files && uploader.files[0] ){
          $('#featuredimage_preview_edit').attr('src', 
             window.URL.createObjectURL(uploader.files[0]) );
    }
}

function galleryPreview_edit( uploader, number ) {
    if ( uploader.files && uploader.files[0] ){
          $('#gallery'+number+'image_preview_edit').attr('src', 
             window.URL.createObjectURL(uploader.files[0]) );
    }
}

function galleryPreview_add( uploader, number ) {
    if ( uploader.files && uploader.files[0] ){
          $('#gallery'+number+'image_preview_add').attr('src', 
             window.URL.createObjectURL(uploader.files[0]) );
    }
}


/* AMAZON ADD ITEM */

var timer;

function searchItem(query, region, action) {
  clearTimeout(timer);
    timer=setTimeout(function validate(){

  $.ajax({
    url: webRootJs +'amazon/index.php',
    type: 'get',
    data: { "q": query, "lang": region},
    success: function(response) { 

      $( ".search_results."+action ).html("");

      var el = $( '<div></div>' );
      el.html(response);

      var items = $('item', el);

      if(items[0] == null){
        $( ".search_results."+action ).append("No items found...");
      }

      $.each(items,function(key, value) {
        var list = $('<li></li>');

        var mediumimage = $('mediumimage', value);
        var attributes = $('itemattributes', value);
        var img = $('<img>');
        var src = $('url', mediumimage).html();

        img.attr('src', src);

      var title = $('title', attributes).html();
      img.attr('title', title);
      
      if(title.length > 48){
          title = title.substring(0,48) + '..';
      }
      var url = $('detailpageurl', value).html();
      var encodedUrl = encodeURIComponent(url);
      var encodedTitle = encodeURIComponent(title);
      var encodedSrc = encodeURIComponent(src);


      list.html('<p>' + title + '</p><a onclick="addToBasket(\`' +encodedTitle+ '\`, \'' +encodedUrl+ '\', \'' +encodedSrc+ '\', \'' +action+ '\')"><i class="fa fa-square-o" aria-hidden="true"></i></a>');
      list.prepend(img);
      $( ".search_results."+action ).append(list);
    });

      var image = $('mediumimage')

    }
});}, 500);};

function addToBasket(title, url, src, action) {

  $('.hiddenInput.'+action).val($('.hiddenInput.'+action).val() + title + ';'+ url + ';' + src + ',');

  $( ".search_results."+action ).html("");
  $( ".liveInput."+action ).val("");

  decodedTitle = decodeURIComponent(title);
  decodedSrc = decodeURIComponent(src);

  var list = $('<li></li>');
  var img = $('<img>');
  img.attr('src', decodedSrc);
  list.html('<p>' + decodedTitle + '</p><a onclick="deleteFromBasket(\`'+title+'\`,this,\''+action+'\')"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>');
  list.prepend(img);0
  $( ".basket_items."+action ).append(list);
}


function deleteFromBasket(title, parent, action) {


  var ResearchArea = $('.hiddenInput.'+action).val();

  var splitTextInput = ResearchArea.split(",");

  new_arr = $.grep(splitTextInput, function(n, i){ // just use arr
    return n.split(";")[0] != title;
  });

  $('.hiddenInput.'+action).val(new_arr);
  
  parent.closest('li').remove();

}

function likeSetup(id){

  if ($( ".red_button" ).hasClass( "active" )){
    //console.log("des likes");
    $.ajax({
      url: webRootJs + 'app/dislike',
      type: 'get',
      data: { "setup_id": id},
      success: answer_dislike,
      error: answer_error 
    });

}

  else{
    //console.log("Pas de like");
    $.ajax({
      url: webRootJs + 'app/like',
      type: 'get',
      data: { "setup_id": id},
      success: answer_like,
      error: answer_error 

    });}

function answer_like(response){
  $( ".red_button" ).addClass( "active" );
  //console.log(response);
  printLikes(id);
}

function answer_dislike(response){
  $( ".red_button" ).removeClass( "active" );
  //console.log(response);
  printLikes(id);
}

function answer_error(response){
  console.log(response);
}

}

function printLikes(id) {
    $.ajax({
        url: webRootJs + "app/getlikes",
        data: {
            setup_id: id
        },
        dataType: 'html',
        type: 'get',
        success: function (json) {
          //console.log(json);
          $(".pointing_label").html(json);
        }
            
    });

}

function doesLike(setup) {
    $.ajax({
        url: webRootJs + "app/doesLike",
        data: {
            setup_id: setup
        },
        dataType: 'html',
        type: 'get',
        success: function (json) {
          //console.log(json);
          if(json == 'true')
            $(".red_button").addClass("active");
        }
            
    });
  }

/** SIMPLE TOAST **/

class siiimpleToast {
  constructor(settings) {
    // default settings
    if (!settings) {
      settings = {
        vertical: 'bottom',
        horizontal: 'right'
      }
    }
    // throw Parameter Error    
    if (!settings.vertical) throw new Error('Please set parameter "vertical" ex) bottom, top ');
    if (!settings.horizontal) throw new Error('Please set parameter "horizontal" ex) left, center, right ');
    // data binding
    this._settings = settings;
    // default Class (DOM)
    this.defaultClass = 'siiimpleToast';
    // default Style
    this.defaultStyle = {
      position: 'fixed',
      padding: '1rem 1.2rem',
      minWidth: '17rem',
      zIndex: '30',
      borderRadius: '2px',
      color: 'white',
      fontWeight: 300,
      whiteSpace: 'nowrap',
      pointerEvents: 'none',
      opacity: 0,
      boxShadow: '0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)',
      transform: 'scale(0.5)',
      transition: 'all 0.4s ease-out'
    }
    // set vertical direction
    this.verticalStyle = this.setVerticalStyle()[this._settings.vertical];
    // set horizontal direction
    this.horizontalStyle = this.setHorizontalStyle()[this._settings.horizontal];
  }
  setVerticalStyle() {
    return {
      top: {
        top: '-100px'
      },
      bottom: {
        bottom: '-100px'
      }
    }
  }
  setHorizontalStyle() {
    return {
      left: {
        left: '1rem'
      },
      center: {
        left: '50%',
        transform: 'translateX(-50%) scale(0.5)'
      },
      right: {
        right: '1rem'
      }
    }
  }
  setMessageStyle() {
    return {
      default: '#323232',
      success: '#005f84',
      alert: '#db2828',
    }
  }
  init(state, message) {
    const root = document.querySelector('body');
    const newToast = document.createElement('div');

    // set Common class
    newToast.className = this.defaultClass;
    // set message
    newToast.innerHTML = message;
    // set style
    Object.assign(
      newToast.style,
      this.defaultStyle,
      this.verticalStyle,
      this.horizontalStyle
    );
    // set Message mode (Color)
    newToast.style.backgroundColor = this.setMessageStyle()[state];
    // insert Toast DOM
    root.insertBefore(newToast, root.firstChild);

    // Actions...
    let time = 0;
    // setTimeout - instead Of jQuery.queue();
    setTimeout(() => {
      this.addAction(newToast);
    }, time += 100);
    setTimeout(() => {
      this.removeAction(newToast);
    }, time += 5000);
    setTimeout(() => {
      this.removeDOM(newToast);
    }, time += 500);
  }
  addAction(obj) {
    // All toast objects
    const toast = document.getElementsByClassName(this.defaultClass);
    let pushStack = 15;

    // *CSS* transform - scale, opacity 
    if (this._settings.horizontal == 'center') {
      obj.style.transform = 'translateX(-50%) scale(1)';
    } else {
      obj.style.transform = 'scale(1)';
    }
    obj.style.opacity = 1;

    // push effect (Down or Top)
    for (let i = 0; i < toast.length; i += 1) {
      const height = toast[i].offsetHeight;
      const objMargin = 15; // interval between objects

      // *CSS* bottom, top 
      if (this._settings.vertical == 'bottom') {
        toast[i].style.bottom = `${pushStack}px`;
      } else {
        toast[i].style.top = `${pushStack}px`;
      }

      pushStack += height + objMargin;
    }
  }
  removeAction(obj) {
    const width = obj.offsetWidth;
    const objCoordinate = obj.getBoundingClientRect();

    // remove effect
    // *CSS*  direction: right, opacity: 0
    if (this._settings.horizontal == 'right') {
      obj.style.right = `-${width}px`;
    } else {
      obj.style.left = `${objCoordinate.left + width}px`;
    }
    obj.style.opacity = 0;
  }
  removeDOM(obj) {
    const parent = obj.parentNode;
    parent.removeChild(obj);
  }
  message(message) {
    this.init('default', message);
  }
  success(message) {
    this.init('success', message);
  }
  alert(message) {
    this.init('alert', message);
  }
}

/**** Convert string to url slug ****/
function convertToSlug(str)
{
    var rExps=[
   {re:/[\xC0-\xC6]/g, ch:'A'},
   {re:/[\xE0-\xE6]/g, ch:'a'},
   {re:/[\xC8-\xCB]/g, ch:'E'},
   {re:/[\xE8-\xEB]/g, ch:'e'},
   {re:/[\xCC-\xCF]/g, ch:'I'},
   {re:/[\xEC-\xEF]/g, ch:'i'},
   {re:/[\xD2-\xD6]/g, ch:'O'},
   {re:/[\xF2-\xF6]/g, ch:'o'},
   {re:/[\xD9-\xDC]/g, ch:'U'},
   {re:/[\xF9-\xFC]/g, ch:'u'},
   {re:/[\xC7-\xE7]/g, ch:'c'},
   {re:/[\xD1]/g, ch:'N'},
   {re:/[\xF1]/g, ch:'n'} ];
    var $slug = '';
    var trimmed = $.trim(str);
    for(var i=0, len=rExps.length; i<len; i++)
    trimmed=trimmed.replace(rExps[i].re, rExps[i].ch);
    $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
    replace(/-+/g, '-').
    replace(/^-|-$/g, '');
    return $slug;
}

/**** Infinite Scroll Maison ****/

function infiniteScroll(nbtodisplay) { 
  var offset = nbtodisplay;
  $(window).data('ajaxready', true);
  // on déclence une fonction lorsque l'utilisateur utilise sa molette 
  $(window).scroll(function() {
    if ($(window).data('ajaxready') == false) return; //permet de couper les trigger parallèles  
    if(($(window).scrollTop() + $(window).height()) + 250 > $(document).height()) {
      $(window).data('ajaxready', false);
      //console.log(offset);
      $.ajax({
        url: webRootJs + "app/getSetups",
        data: {
            p: offset,
            n: nbtodisplay
        },
        dataType: 'html',
        type: 'get',
        success: function (json) {
          setups = $.parseJSON(json);
          if(setups[0]){
            //console.log(json[0]['title']);
            $.each(setups ,function(key, value) {
              console.log(value['likes']);
              var nblikes;
              if(value['likes'][0]){
                nblikes = value['likes'][0]['total'];
              }
              else{
                nblikes = 0;
              }
              $('.fullitem_holder').append('<div class="fullitem"><a href="'+webRootJs+'setups/'+value['id']+'-'+convertToSlug(value['title'])+'"><img src="'+webRootJs+value['resources'][0]['src'] +'"><\/a><div class="red_like"><i class="fa fa-heart"><\/i> '+ nblikes +'<\/div><div class="fullitem-inner"><div class="row"><div class="column column-75"><a class="featured-user" href="'+webRootJs+'users/'+value['user_id']+'"><img src="'+webRootJs+'uploads/files/pics/profile_picture_'+value['user_id']+'.png"><\/a><a href="'+webRootJs+'setups/'+value['id']+'-'+convertToSlug(value['title'])+'"><h3>'+value['title']+'<\/h3><\/a><\/div><\/div><\/div><\/div>');
            });
            $(window).data('ajaxready', true);
          }
          else{
            $('.no_more_setups').html("No more setups to display...");
          }
          
        }
      });
      offset+= nbtodisplay;
    }
  });
}
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
(function() {
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
      showCount: true,
          showLabel: function(screenWidth) {
        return (screenWidth > 640);
      },
      shares: ["twitter", "facebook", "googleplus", "pinterest", "stumbleupon", "whatsapp"]
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
}

  /***************** DRAG AND DROP ****************/

  // getElementById
  function $id(id) {
    return document.getElementById(id);
  }

  // output information
  function Output(msg) {
    var m = $id("messages");
    m.innerHTML = msg + m.innerHTML;
  }

  // file drag hover
  function FileDragHover(e) {
    e.stopPropagation();
    e.preventDefault();
    e.target.className = (e.type == "dragover" ? "hover" : "");
  }

  // file selection
  function FileSelectHandler(e) {
    // cancel event and hover styling
    FileDragHover(e);
    // fetch FileList object
    var files = e.target.files || e.dataTransfer.files;

    // process all File objects
    for (var i = 0, f; f = files[i]; i++) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('<img />').attr('src',  e.target.result).appendTo(images_holder);
        }
        reader.readAsDataURL(files[i]);
      //ParseFile(f);
    }
  }

  // output file information
  function ParseFile(file) {
    Output(
      "<p>File information: <strong>" + file.name +
      "</strong> type: <strong>" + file.type +
      "</strong> size: <strong>" + file.size +
      "</strong> bytes</p>"
    );
  }

  // initialize
  function Init() {
    var fileselect = $id("fileselect");
        //var filedrag = $id("filedrag");
    // file select
    fileselect.addEventListener("change", FileSelectHandler, false);
    // is XHR2 available?
    var xhr = new XMLHttpRequest();
    // if (xhr.upload) {
    //   // file drop
    //   filedrag.addEventListener("dragover", FileDragHover, false);
    //   filedrag.addEventListener("dragleave", FileDragHover, false);
    //   filedrag.addEventListener("drop", FileSelectHandler, false);
    //   filedrag.style.display = "block";
    // }
  }
  // call initialization file
  if (window.File && window.FileList && window.FileReader) {
    Init();
  }
})();


/* AMAZON ADD ITEM */

var timer;

function searchItem(query) {
  clearTimeout(timer);
    timer=setTimeout(function validate(){

  $.ajax({
    url: '/mysetup/amazon/index.php',
    type: 'get',
    data: { "q": query},
    success: function(response) { 

      $( ".search_results" ).html("");

      var el = $( '<div></div>' );
      el.html(response);

      var items = $('item', el);

      $.each(items,function(key, value) {
        var list = $('<li></li>');

        var mediumimage = $('mediumimage', value);
        var attributes = $('itemattributes', value);
        var img = $('<img>');
        var src = $('url', mediumimage).html();
      img.attr('src', src);

      var title = $('title', attributes).html();
      if(title.length > 48){
          title = title.substring(0,48) + '..';
      }
      var url = $('detailpageurl', value).html();
      var encodedUrl = encodeURIComponent(url);
      var encodedTitle = encodeURIComponent(title);
      var encodedSrc = encodeURIComponent(src);


      list.html('<p>' + title + '</p><a onclick="addToBasket(\'' +encodedTitle+ '\', \'' +encodedUrl+ '\', \'' +encodedSrc+ '\')"><i class="fa fa-square-o" aria-hidden="true"></i></a>');
      list.prepend(img);
      $( ".search_results" ).append(list);
    });

      var image = $('mediumimage')

    }
});}, 500);};

function addToBasket(title, url, src) {

  $('.hiddenInput').val($('.hiddenInput').val() + title + ';'+ url + ';' + src + ',');

  $( ".search_results" ).html("");
  $( ".liveInput" ).val("");

  decodedTitle = decodeURIComponent(title);
  decodedSrc = decodeURIComponent(src);

  var list = $('<li></li>');
  var img = $('<img>');
  img.attr('src', decodedSrc);
  list.html('<p>' + decodedTitle + '</p><a onclick="deleteFromBasket(\''+title+'\',this)"><i class="fa fa-check-square-o" aria-hidden="true"></i></a>');
  list.prepend(img);0
  $( ".basket_items" ).append(list);
}


function deleteFromBasket(title, parent) {


  var ResearchArea = $('.hiddenInput').val();

  var splitTextInput = ResearchArea.split(",");

  new_arr = $.grep(splitTextInput, function(n, i){ // just use arr
    return n.split(";")[0] != title;
  });

  $('.hiddenInput').val(new_arr);
  
  parent.closest('li').remove();

}

function likeSetup(id){

  if ($( ".red_button" ).hasClass( "active" )){
    //console.log("des likes");
    $.ajax({
      url: '/mysetup/app/dislike',
      type: 'get',
      data: { "setup_id": id},
      success: answer_dislike,
      error: answer_error 
    });

}

  else{
    //console.log("Pas de like");
    $.ajax({
      url: '/mysetup/app/like',
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
        url: "/mysetup/app/getlikes",
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
        url: "/mysetup/app/doesLike",
        data: {
            setup_id: setup
        },
        dataType: 'html',
        type: 'get',
        success: function (json) {
          //console.log(json);
          if(json == "1")
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
        url: "/mysetup/app/getSetups",
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
              $('.fullitem_holder').append('<div class="fullitem"><a href="/mysetup/setups/'+value['id']+'-'+convertToSlug(value['title'])+'"><img src="/mysetup/'+value['resources'][0]['src'] +'"><\/a><div class="red_like"><i class="fa fa-heart"><\/i> '+ nblikes +'<\/div><div class="fullitem-inner"><div class="row"><div class="column column-75"><a class="featured-user" href="/mysetup/users/'+value['user_id']+'"><img src="/mysetup/uploads/files/profile_picture_'+value['user_id']+'.png"><\/a><a href="/mysetup/setups/'+value['id']+'-'+convertToSlug(value['title'])+'"><h3>'+value['title']+'<\/h3><\/a><\/div><\/div><\/div><\/div>');
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
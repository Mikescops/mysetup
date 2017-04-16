/*!
 * mySetup v1.0.0
 * https://mysetup.co
 *
 * Copyright (c) 2017 Corentin Mors / Samuel Forestier
 * All rights reserved
 */

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


$(function(){
   $('.is_author').click(function(){
      $(this).hide();
      $('.setup_author').show('fast');
      return false;
   });
});

  /***************** Image preview on modal ****************/

document.getElementById("featuredimage").onchange = function () {
    var reader = new FileReader();

    reader.onload = function (e) {
        // get loaded data and render thumbnail.
        document.getElementById("featuredimage_preview").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
};


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
            $('<img />').attr('src', e.target.result).appendTo(images_holder);
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
    console.log("des likes");
    $.ajax({
      url: '/mysetup/app/dislike',
      type: 'get',
      data: { "setup_id": id},
      success: answer_dislike,
      error: answer_error 

    });

}

  else{
    console.log("Pas de like");

  $.ajax({
    url: '/mysetup/app/like',
    type: 'get',
    data: { "setup_id": id},
    success: answer_like,
    error: answer_error 

  });}

     function answer_like(texte_recu){

  $( ".red_button" ).addClass( "active" );

  console.log(texte_recu);

  printLikes(id);

    // Du code pour gérer le retour de l'appel AJAX.

}

    function answer_dislike(texte_recu){

  $( ".red_button" ).removeClass( "active" );

  console.log(texte_recu);

  printLikes(id);

  }

  function answer_error(texte_recu){
    console.log(texte_recu);
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
          console.log(json);
          $(".pointing_label").html(json);
        }
            
    });

}
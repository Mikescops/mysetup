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
  arrows: false,
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





  /***************** DRAG AND DROP *****/

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
      ParseFile(f);
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

    var fileselect = $id("fileselect"),
      filedrag = $id("filedrag"),
      submitbutton = $id("submitbutton");

    // file select
    fileselect.addEventListener("change", FileSelectHandler, false);

    // is XHR2 available?
    var xhr = new XMLHttpRequest();
    if (xhr.upload) {

      // file drop
      filedrag.addEventListener("dragover", FileDragHover, false);
      filedrag.addEventListener("dragleave", FileDragHover, false);
      filedrag.addEventListener("drop", FileSelectHandler, false);
      filedrag.style.display = "block";

      // remove submit button
      //submitbutton.style.display = "none";
    }

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
});}, 500);}

function addToBasket(title, url, src) {

$('.hiddenInput').val($('.hiddenInput').val() + title + ','+ url + ',' + src + ';');

$( ".search_results" ).html("");
$( ".liveInput" ).val("");

}
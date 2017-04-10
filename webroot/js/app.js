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
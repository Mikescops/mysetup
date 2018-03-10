/**** EMBED API mySetup.co v2.0.1 ****/
function r(f) {
    /in/.test(document.readyState) ? setTimeout('r(' + f + ')', 9) : f()
}
r(function() {
    var frame = document.getElementById('mysetup-embed');
    var iframe = document.createElement('iframe');
    iframe.frameBorder = 0;
    iframe.scrolling = "no";
    iframe.setAttribute("style", "display:block;")
    iframe.height = 0;
    if (frame.getAttribute('ms-width')) {
        iframe.width = frame.getAttribute('ms-width')
    } else {
        iframe.width = 400
    }
    iframe.height = (iframe.width * 75) / 100;
    if (frame.getAttribute('dev') == 'on') {
        iframe.src = document.location.origin + '/api/embed/' + frame.getAttribute('ms-setup');
    } else {
        iframe.src = 'https://mysetup.co/api/embed/' + frame.getAttribute('ms-setup');
    }
    frame.innerHTML = "";
    frame.prepend(iframe)
})
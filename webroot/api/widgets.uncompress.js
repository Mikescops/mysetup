/**** EMBED API mySetup.co v3.1.0 ****/
function r(f) {
    /in/.test(document.readyState) ? setTimeout('r(' + f + ')', 9) : f();
}
r(function() {
    var frame = document.getElementById('mysetup-embed');
    var iframe = document.createElement('iframe');
    iframe.frameBorder = 0;
    iframe.scrolling = "no";
    iframe.height = 0;
    if (frame.getAttribute('ms-width') && frame.getAttribute('ms-width') != 'responsive') {
        iframe.width = frame.getAttribute('ms-width');
        iframe.height = (iframe.width * 75) / 100;
    } else {
        iframe.width = '100%';
        iframe.height = '100%';
        iframe.setAttribute("style", "display:block;position:absolute;top:0;left:0");
        frame.setAttribute("style", "position:relative;padding-bottom: 75%");
    }
    if (frame.getAttribute('dev') == 'on') {
        iframe.src = document.location.origin + '/api/embed/' + frame.getAttribute('ms-setup');
    } else {
        iframe.src = 'https://mysetup.co/api/embed/' + frame.getAttribute('ms-setup');
    }
    frame.innerHTML = "";
    frame.prepend(iframe);
});
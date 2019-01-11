/** @cc_on EMBED API mySetup.co v3.2.0 */

function r(f) {
    /in/.test(document.readyState) ? setTimeout('r(' + f + ')', 9) : f();
}
r(function() {
    var frames = document.getElementsByClassName('mysetup-embed');

    for (i = 0; i < frames.length; i++) {
        let item = frames[i];
        let iframe = document.createElement('iframe');
        iframe.frameBorder = 0;
        iframe.scrolling = "no";
        iframe.height = 0;
        if (item.getAttribute('ms-width') && item.getAttribute('ms-width') != 'responsive') {
            iframe.width = item.getAttribute('ms-width');
            iframe.height = (iframe.width * 75) / 100;
        } else {
            iframe.width = '100%';
            iframe.height = '100%';
            iframe.setAttribute("style", "display:block;position:absolute;top:0;left:0");
            item.setAttribute("style", "position:relative;padding-bottom: 75%");
        }
        if (item.getAttribute('dev') == 'on') {
            iframe.src = document.location.origin + '/api/embed/' + item.getAttribute('ms-setup');
        } else {
            iframe.src = 'https://mysetup.co/api/embed/' + item.getAttribute('ms-setup');
        }
        item.innerHTML = "";
        item.prepend(iframe);
    }
    
});
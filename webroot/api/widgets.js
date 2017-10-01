function r(f){/in/.test(document.readyState)?setTimeout('r('+f+')',9):f()}
r(function(){var frame=document.getElementById('mysetup-embed');var iframe=document.createElement('iframe');iframe.frameBorder=0;iframe.scrolling="no";iframe.setAttribute("onload","resizeIframe(this)");iframe.setAttribute("style","display:block;")
iframe.height=0;iframe.width=frame.getAttribute('ms-width');iframe.src='https://mysetup.co/embed/'+frame.getAttribute('ms-setup');frame.innerHTML="";frame.prepend(iframe)});function resizeIframe(obj){obj.style.height=obj.contentWindow.document.body.scrollHeight+'px'}

function r(t){/in/.test(document.readyState)?setTimeout("r("+t+")",9):t()}r(function(){var t=document.getElementById("mysetup-embed"),e=document.createElement("iframe");e.frameBorder=0,e.scrolling="no",e.height=0,t.getAttribute("ms-width")&&"responsive"!=t.getAttribute("ms-width")?(e.width=t.getAttribute("ms-width"),e.height=75*e.width/100):(e.width="100%",e.height="100%",e.setAttribute("style","display:block;position:absolute;top:0;left:0"),t.setAttribute("style","position:relative;padding-bottom: 75%")),"on"==t.getAttribute("dev")?e.src=document.location.origin+"/api/embed/"+t.getAttribute("ms-setup"):e.src="https://mysetup.co/api/embed/"+t.getAttribute("ms-setup"),t.innerHTML="",t.prepend(e)});
/** @cc_on EMBED API mySetup.co v3.2.0 */
function r(t){/in/.test(document.readyState)?setTimeout("r("+t+")",9):t()}r(function(){function t(t){let e=document.createElement("iframe");e.frameBorder=0,e.scrolling="no",e.height=0,t.getAttribute("ms-width")&&"responsive"!=t.getAttribute("ms-width")?(e.width=t.getAttribute("ms-width"),e.height=75*e.width/100):(e.width="100%",e.height="100%",e.setAttribute("style","display:block;position:absolute;top:0;left:0"),t.setAttribute("style","position:relative;padding-bottom: 75%")),t.getAttribute("dev")?e.src="http://"+t.getAttribute("dev")+"/api/embed/"+t.getAttribute("ms-setup"):e.src="https://mysetup.co/api/embed/"+t.getAttribute("ms-setup"),t.innerHTML="",t.prepend(e)}var e=document.getElementsByClassName("mysetup-embed");if(e.length>0)for(i=0;i<e.length;i++)t(e[i]);else null==(e=document.getElementById("mysetup-embed"))?console.warn("[MYSETUP EMBED API] No mysetup.co frame found, please use our documentation to handle errors."):(console.info("[MYSETUP EMBED API] Fallback to 'id' attribute, please consider using 'class' instead."),t(e))});
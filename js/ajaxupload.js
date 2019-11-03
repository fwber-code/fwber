function $m(theVar)
{
	return document.getElementById(theVar);
}
function remove(theVar)
{
	var theParent = theVar.parentNode;
	theParent.removeChild(theVar);
}

function addEvent(obj, evType, fn)
{
	if(obj.addEventListener)obj.addEventListener(evType, fn, true);
	if(obj.attachEvent) obj.attachEvent("on"+evType, fn);
}

function removeEvent(obj, type, fn)
{
	if(obj.detachEvent)
	{
		obj.detachEvent('on'+type, fn);
	}
	else
	{
		obj.removeEventListener(type, fn, false);
	}
}

function isWebKit()
{
	return RegExp(" AppleWebKit/").test(navigator.userAgent);
}

function ajaxUpload(form, type)
{
	var url_action = '_imageUpload?type='+type;
	var id_element = 'picsPublic';
	if(type=="private")id_element = 'picsPrivate';

	var html_show_loading = "File uploading, please wait...<br><img src='images/loader_light_blue.gif' width='128' height='15' border='0' />";
	var html_error_http = "<img src='images/error.gif' width='16' height='16' border='0' />; There was an error during the picture upload. Please try again.";

	var detectWebKit = isWebKit();
	
	form = typeof(form)=="string"?$m(form):form;
	
	var errorString="";
	
	if(form==null || typeof(form)=="undefined")
	{
		errorString += "The form does not exist.\n";
	}
	else 
	if(form.nodeName.toLowerCase()!="form")
	{
		errorString += "The form is not a form.\n";
	}
	
	if($m(id_element)==null)
	{
		errorString += "The element does not exist.\n";
	}
	
	if(errorString.length>0)
	{
		alert("Error in _imageUpload:\n" + errorString);
		return;
	}
	
	var iframe = document.createElement("iframe");
	iframe.setAttribute("id","ajax-temp");
	iframe.setAttribute("name","ajax-temp");
	iframe.setAttribute("width","0");
	iframe.setAttribute("height","0");
	iframe.setAttribute("border","0");
	iframe.setAttribute("style","width: 0; height: 0; border: none;");
	form.parentNode.appendChild(iframe);
	window.frames['ajax-temp'].name="ajax-temp";
	
	var doUpload = function()
	{
		removeEvent($m('ajax-temp'),"load", doUpload);
		var cross = "javascript: ";
		cross += "window.parent.$m('"+id_element+"').innerHTML = document.body.innerHTML; void(0);";
		$m(id_element).innerHTML = html_error_http;
		$m('ajax-temp').src = cross;
		
		if(detectWebKit)
		{
        	remove($m('ajax-temp'));
        }
		else
		{
        	setTimeout
			(
				function()
				{ 
					remove($m('ajax-temp'))
				}, 250
			);
        }
    }
	
	addEvent($m('ajax-temp'),"load", doUpload);
	form.setAttribute("target","ajax-temp");
	form.setAttribute("action",url_action);
	form.setAttribute("method","post");
	form.setAttribute("enctype","multipart/form-data");
	form.setAttribute("encoding","multipart/form-data");
	
	if(html_show_loading.length > 0)
	{
		$m(id_element).innerHTML = html_show_loading;
	}
	
	form.submit();
}
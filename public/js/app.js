
function AjaxObjeto()
{
	var a = null;
	var d = ["Msxml2.XMLHTTP","Microsoft.XMLHTTP"];
	if(window.ActiveXObject)
	{
		for(var e = 0; e < d.length; e++)
		{
			try{ a = new ActiveXObject(d[e])}
			catch(f){}
		}
	}
	else 
	{
		a = new XMLHttpRequest;
	}
	return a;
}

function AjaxRequest(_exito,_fallo)
{
	var b = new AjaxObjeto();

	if(b != null)
	{
		b.onreadystatechange=function()
		{
			if(this.readyState == 4 && this.status == 200 && typeof _exito == "function")
			{
				_exito(this);
			}
			else if(this.readyState == 4 && this.status != 200)
			{
				if(typeof _fallo == "function")
				{
					_fallo(this);
				}
				else
				{
					console.info("Error en la respuesta ajax.");
					console.info(this.responseText);
				}
			}
		}
	}
	return b;
}
function obtenerCSRF()
{
	var a=document.getElementById("csrf");
	void 0!=a&&""!=a.value||console.info("error: no hay csrf");
	return a.value
}
function login(a)
{
	if(a != undefined)
	{
		var j = JSON.parse(a.responseText);
		if(j['estado'] == 'exito')
		{
			ocultar('login');
		}
		else
		{
			document.getElementById('login-msg').innerHTML = j['msg'];
		}
	}
	else
	{
		var nombre = document.getElementById('login-nombre').value.trim();
		var clave = document.getElementById('login-clave').value.trim();
		var csrf = obtenerCSRF();
		var p = "nombre="+encodeURIComponent(nombre)+"&clave="+encodeURIComponent(clave)+"&csrf="+encodeURIComponent(csrf);
		var r = new ajaxRequest(login, login);
		r.open("POST", "/usuarios/login", true);
		r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		r.send(p);
	}
}


window.addEventListener('load', function()
{
	
}, false);


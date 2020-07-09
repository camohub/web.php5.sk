/**
 * Aby prihlasovanie fungovalo, musí sa v sekcii developers na Facebooku
 * nastaviť v settings url buď na localhost, alebo na https://web.php5.sk
 * podľa toho kde to treba spustiť. Share a like fungujú aj bez url.
 *
 * https://developers.facebook.com/docs/facebook-login/web/
 */

//FB.login() examples
// https://developers.facebook.com/docs/javascript/reference/FB.api/#examples
// https://developers.facebook.com/docs/facebook-login/web/#example
window.fbAsyncInit = function() {
	FB.init({
		appId      : '1452969908332494',
		cookie     : true,
		xfbml      : true,
		version    : 'v7.0'
	});
};

(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) { return; }
	js = d.createElement(s); js.id = id;
	js.src = "https://connect.facebook.net/sk_SK/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function fbLogin()
{
	FB.login(
		function( response ) {
			checkLoginState();
		},
		{
			scope: 'email,public_profile'
		}
	)
}


// This function is called after fb login.
function checkLoginState()
{
	FB.getLoginStatus(function(response)
	{
		statusChangeCallback(response);
	});
}


function statusChangeCallback(response)
{
	//console.log(response);
	if (response.status === 'connected')
	{
		// Logged into your app and Facebook.
		fbApiCalback(response.authResponse.accessToken);
	}
	else if (response.status === 'not_authorized')
	{
		// The person is logged into Facebook, but not to your app.
		document.getElementById('loginStatus').innerHTML = 'Prihlásenie nebolo úspešné.';
	}
	else
	{
		// The person is not logged into Facebook, so we're not sure if
		// they are logged into this app or not.
		document.getElementById('loginStatus').innerHTML = 'Prihlásenie nebolo úspešné.';
	}
}

function fbApiCalback(accessToken)
{
	// FB.api examples
	FB.api('/me', {'fields': 'id,email,first_name,last_name,name'}, function(response)
	{
		if(!response.email || response.email == '')
		{
			alert('Aplikácia potrebuje Váš email kôli prihláseniu.');
		}
		else
		{
			$.nette.ajax({
				url : "{link :Signfb:in}",
				accepts : 'json',
				type : 'post',
				data : {
					'accessToken': accessToken
				},
				success : function(data)
				{
					console.log('Odpoveď zo servera:'); console.log(data); //return;

					if(data.error)
					{
						// Be carefull what is displayed!!!
						document.getElementById('loginStatus').innerHTML = '<b class="error">' + data.error + '</b>';
					}
					else
					{
						var url = window.location.href;

						url += (url.indexOf("?") === -1)  ?  '?_fid=' + data._fid  :  '&_fid=' + data._fid;
						console.log(url);
						window.location = url;
					}
				},
				error : function(data)
				{
					document.getElementById('loginStatus').innerHTML = '<b class="error">Pri prihlasovaní došlo k chybe.</b>';
				}
			});  // end of $.ajax()
		}
	});
}

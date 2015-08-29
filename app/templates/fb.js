/**
 * Aby prihlasovanie fungovalo, musí sa v sekcii developers na Facebooku
 * nastaviť v settings url buď na localhost, alebo na http://web.php5.sk
 * podľa toho kde to treba spustiť. Share a like fungujú aj bez url.
 */

window.fbAsyncInit = function()
{
    FB.init({
        appId      : '1452969908332494',
        xfbml      : true,  // parse social plugins on this page
        version    : 'v2.3',  // use version 2.2
        cookie     : true  // enable cookies to allow the server to access the session
    });

};

// js () operator executes anonymous fcion immediately
(function(d, s, id)
{
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


// This function is called when someone finishes with the Login button.
function checkLoginState()
{
    FB.getLoginStatus(function(response)
    {
        statusChangeCallback(response);
    });
}


// This fc is called by checkLoginState function above.
function statusChangeCallback(response)
{
    // console.log( response );
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected')
    {
        // Logged into your app and Facebook.
        fbApiCalback();
    }
    else if (response.status === 'not_authorized')
    {
        // The person is logged into Facebook, but not your app.
        document.getElementById('loginStatus').innerHTML = 'Please log ' + 'into this app.';
    }
    else
    {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        document.getElementById('loginStatus').innerHTML = 'Please log ' + 'into Facebook.';
    }
}

// This fc is called if statusChangeCallback above is successfull
function fbApiCalback()
{
    FB.api('/me', function(response)
    {
        if(!response.email || response.email == '')
        {
            alert('Aplikácia potrebuje Váš email kôli autentizácii.');
            FB.login(
                function( response ) {
                    checkLoginState();
                },
                {
                    scope: 'email',
                    auth_type: 'rerequest'
                }
            );
        }
        else
        {
            $.nette.ajax({
                url : "{link :Signfb:in}",
                accepts : 'json',
                type : 'post',
                data : {
                    'email' : response.email,
                    'user_name' : response.name,
                    'id' : response.id
                },
                success : function(data)
                {
                    //console.log('Odpoveď zo servera:'); console.log(data); return;

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

/**
 * This is an example of triggering the share dialog via FB.ui
 * For more info go to the developers->application->getstarted and read it.
 *
        $('#test').on('click', function(){
                FB.ui({
                    method: 'share',
                    href: 'https://developers.facebook.com/docs/',
                }, function(response){});
        });
 *
 */



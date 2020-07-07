function googleStart()
{
    //console.log('googleStart');
    /*gapi.load('auth2', function()
    {
        auth2 = gapi.auth2.init({
            client_id: '811214467813-v3fmui55m0kmohsf6dbg1jjl11ori3tg.apps.googleusercontent.com',
            // Scopes to request in addition to 'profile' and 'email'
            //fetch_basic_profile: true,
            scope: 'https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email'
        });
    });*/
	gapi.load('auth2', function() {
		auth2 = gapi.auth2.init({
			client_id: '811214467813-v3fmui55m0kmohsf6dbg1jjl11ori3tg.apps.googleusercontent.com',
			scope: 'https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email'
			// Scopes to request in addition to 'profile' and 'email'
			//fetch_basic_profile: true,
		});
	});
}

function googleSignIn(authResult)
{
    //console.log(authResult);
    if (authResult['code'])
    {
        // Hide the sign-in button now that the user is authorized, for example:
        //$('#signinButton').attr('style', 'display: none');

        // Send the code to the server
        $.ajax({
            type: 'POST',
            url : "{link :Signgoogle:in}",
            accepts : 'json',
            // Spýtať sa na DJPW čo to tu je zakomentované
            //contentType: 'application/octet-stream; charset=utf-8',
            //processData: false,
            data : {
                'code' : authResult['code']
            },
            success : function(data)
            {
                //console.log('Odpoveď so servera:');	console.log(data); return;

                if(data.error)
                {
                    // Be carefull what is displayed!!!
                    document.getElementById('loginStatus').innerHTML = '<b class="error">' + data.error + '</b>';
                }
                else
                {
                    var url = window.location.href;
                    //console.log(url);
                    // _fid is flash messages id
                    url += (url.indexOf("?") === -1)  ?  '?_fid=' + data._fid  :  '&_fid=' + data._fid;
                    window.location = url;
                }
            },
            error : function(data)
            {
                document.getElementById('loginStatus').innerHTML = '<b class="error">Pri prihlasovaní došlo k chybe.</b>';
            }
        });
    }
    else
    {
        console.log('There was an error.');
    }
}

function onFailure()
{
    alert('G+ gapi fails.');
}

/*function onSignIn(googleUser)
{
    var gAuthResponse = googleUser.getAuthResponse(),
        id_token = googleUser.getAuthResponse().id_token,
        profile = googleUser.getBasicProfile();

    console.log(gAuthResponse);

    $.ajax({
        url : "{link :Signgoogle:in}",
        accepts : 'json',
        type : 'post',
        data : {
            'id_token' : id_token
            //'gAuthResponse' : JSON.stringify(gAuthResponse)
        },
        success : function(data)
        {
            console.log('Odpoveď so servera:');	console.log(data);
            if(data.error)
            {
                // Be carefull what is displayed!!!
                document.getElementById('loginStatus').innerHTML = '<b class="error">' + data.error + '</b>';
            }
            else
            {
                var url = window.location.href;
                //console.log(url);
                // _fid is flash messages id
                url += (url.indexOf("?") === -1)  ?  '?_fid=' + data._fid  :  '&_fid=' + data._fid;
                window.location = url;
            }
        },
        error : function(data)
        {
            document.getElementById('loginStatus').innerHTML = '<b class="error">Pri prihlasovaní došlo k chybe.</b>';
        }
    });  // end of $.ajax()


}


function googleSignOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        console.log('User signed out.');
    });
}*/


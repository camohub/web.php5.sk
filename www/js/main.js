$(function(){
    $.nette.init();

    $(document).on('click', '.x', function()
    {
        $(this).closest('div').css('display', 'none');
    });

    $('#sideMenu').find('a').each(function()
    {
        if($(this).attr('href') == window.location.pathname) $(this).addClass('current');
    });

    prettyPrint();

});

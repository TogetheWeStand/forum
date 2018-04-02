$(document).ready(function() {
    $('.themes-item-name').click(function()
    {
        var tempLoc = location.href.substring(0, location.href.lastIndexOf('%2F') + '%2F'.length);
        location.assign(tempLoc + 'theme&id=' + $(this).closest('li').attr('id'));
    });

    $('.img-close').click(function()
    {
        var tempLoc = location.href.substring(0, location.href.lastIndexOf('%2F') + '%2F'.length);
        location.assign(tempLoc + 'close-theme&id=' + $(this).closest('li').attr('id'));
    });

    $('.img-open').click(function()
    {
        var tempLoc = location.href.substring(0, location.href.lastIndexOf('%2F') + '%2F'.length);
        location.assign(tempLoc + 'open-theme&id=' + $(this).closest('li').attr('id'));
    });
});
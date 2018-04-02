$(document).ready(function() {
    $('.group-item').click(function()
    {
        var tempLoc = location.href.substring(0, location.href.lastIndexOf('%2F') + '%2F'.length);
        location.assign(tempLoc + 'themes-list&g_id=' + $(this).attr('id'));
    });
});
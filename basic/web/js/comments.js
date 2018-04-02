$(document).ready(function() {
    $('.edit-img').click(function()
    {
        var tempLoc = location.href.substring(0, location.href.lastIndexOf('%2F') + '%2F'.length);
        location.assign(tempLoc + 'edit-comment&c_id=' + $(this).closest('li').attr('id'));
    });

    $('.delete-img').click(function()
    {
        var tempLoc = location.href.substring(0, location.href.lastIndexOf('%2F') + '%2F'.length);
        location.assign(tempLoc + 'delete-comment&c_id=' + $(this).closest('li').attr('id'));
    });

    $('.like').click(function()
    {
        var tempLoc = location.href.substring(0, location.href.lastIndexOf('%2F') + '%2F'.length);
        location.assign(tempLoc + 'change-like-status&c_id=' + $(this).closest('li').attr('id'));
    });
});
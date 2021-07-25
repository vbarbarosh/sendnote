jQuery(document).on('click', '.js-file-remove', function (event) {
    var elem = $(this);
    elem.closest('p').remove();
    event.preventDefault();
});

jQuery(document).on('click', '.js-file-add', function (event) {

    var p = $('<p class="container-space-between"><input type="file" name="attachments[]" multiple="multiple" /><button class="js-file-remove btn btn-xs btn-danger flat">remove</button></p>'),
        input = p.find('input').change(appendToFileList).click();

    event.preventDefault();

    function appendToFileList()
    {
        var fileList = $('#file-list');
        fileList.append(p);
    }

});

jQuery(function ($) {
    $('textarea').autosize({append: '', resizeDelay: 0});
});

// vim: set ts=4 sw=4 et :

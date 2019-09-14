preview = {
    selector: '.preview',
    trigger: '.preview__upload',
    initial: '',
    default: '/img/cover'
}

$(function () {
    // get the current image source
    preview.initial = $(preview.selector).attr('src');
    
    // update filename on change
    $('.custom-file input').on('change', function (event) {
        var inputFile = event.currentTarget;
        if (inputFile.files[0] != undefined)
            $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
        else
            $(inputFile).parent()
            .find('.custom-file-label')
            .html('');
    });


    $('#book_cover_filename').on('change', function () {
        // update image source on the page
        isChanged = readURL(this, preview.selector);

        // if not changed, then load the initial image
        if(!isChanged)
            $(preview.selector).attr('src', preview.initial);
    });

    // attach image load button from input to img element
    $(preview.trigger).click(function (e) {
        e.preventDefault();
        $('#book_cover_filename').click()
    })
})

// set source of the given output image according to the given input file
// return true if file is found
function readURL(input, output) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(output).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        return true;
    }
    return false;
}
function convertToSlug(Text) {
    return Text
        .toLowerCase()
        .replace(/ +/g, '-')
        .replace(/[^\w-]+/g, '');
}



$(document).ready(function () {
    $('[name=title], [name=name]').on('keyup', function () {
        var title = $(this).val();
        var slug = convertToSlug(title);
        $('[name=slug]').val(slug);
    });


    $('[name=title], [name=name]').on('blur', function () {
        var title = $(this).val();
        var slug = convertToSlug(title);
        $('[name=slug]').val(slug);
    });


    $('[name=slug]').on('keyup', function () {
        var title = $(this).val();
        var slug = convertToSlug(title);
        $('[name=slug]').val(slug);

    });

    $('[name=slug]').on('blur', function () {
        var title = $(this).val();
        var slug = convertToSlug(title);
        $('[name=slug]').val(slug);
    });

    $(document).on('click', '.clickDeleteFunction', function() { 
        var action = $(this).data('action');
        var modal = $(this).data('modal');
        $('#' + modal + ' form').attr('action', action);
        $('#' + modal).modal('show');
    });

   

})
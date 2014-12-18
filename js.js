(function($){
    $('.RBAC :checkbox:checked').parents('tr').find('select:first').addClass('active');

    $('.RBAC :checkbox').click(function(){
        var self=$(this);

        if($(this).is(':checked'))
            $(this).parents('tr').find('select:first').addClass('active');
        else
            $(this).parents('tr').find('select:first').removeClass('active');
    });
    $('.logged :checkbox').click(function(){
        if($(this).is(':checked'))
            $('.not-logged').find('input,select').attr('disabled','disabled');
        else
            $('.not-logged').find('input,select').attr('disabled',false);
    });
    $('.not-logged :checkbox').click(function(){
        if($(this).is(':checked'))
            $('.logged').find('input,select').attr('disabled','disabled');
        else
            $('.logged').find('input,select').attr('disabled',false);
    });
    $('.RBAC select').change(function(){
        if($(this).val()=='redirect')
            $(this).parents('td').next().find('select').addClass('active');
        else
            $(this).parents('td').next().find('select').removeClass('active');
    });
})(jQuery);



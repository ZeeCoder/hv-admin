
$(function(){

    $('#select_date').change(function(){
        location.href=BASE_URL+'admin/szavazatok/'+$(this).val();
    });

});

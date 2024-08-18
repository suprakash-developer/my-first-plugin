jQuery(document).ready(function($){
    let searchForm=$('#empForm');
    let searchInput=$('#EmpSearch');
    searchInput.keyup(function(e){
        //e.preventDefault();
        let searchTerm=$('#EmpSearch').val();
        let formData = new FormData();
        formData.append('action', 'myAjaxSearh');
        formData.append('searchTerm',searchTerm);

        $.ajax({
            url:ajaxurl,
            type:'post',
            data:formData,
            processData:false,
            contentType:false,
            success:function(responce){
                $('#myTableBody').html(responce);
            },
            error:function(){
                console.log('error');
            }
        })
    })

})
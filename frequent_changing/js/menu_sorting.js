
$(document).ready(function() {
    "use strict";
    let base_url = $("#base_url_").val();
    //counter class count like as 1,2,3,4
    function counter() {
        var i = 1;
        $(".counters").each(function(){
            $(this).html(i);
            i++;
        });
    }

    //call dragsort function
    $('#sortProdctCat').dragsort({
        cursor:'move',
        dragEnd: function() {
            counter();
            console.log('Drag End');
            var data = $("form#sorting_form").serialize();
            $.ajax({
                url     : base_url+'Authentication/sortingMainMenu',
                method  : 'get',
                dataType: 'json',
                data    : data,
                success:function(data){

                }
            });

        },
    });
});

//как-то бредово тут все выглядит если не понравится я могу подумать и переделать есть разные варианты
$("#products-products_id").change(function () {
    if($("#productstoinvoice-0-quantity").val() !== ''){
        var id = $(this).val();
        var count = $('#productstoinvoice-0-quantity').val();
        getPrice(id, count )
    }
});
$("#productstoinvoice-0-quantity").change(function () {
    if($("#products-products_id").val() !== ''){
        var id = $("#products-products_id").val();
        var count = $(this).val();
        getPrice(id, count )
    }
});
//если добавил товар пройтись и получить значения
$(".dynamicform_wrapper").on("afterInsert", function(item) {
    $(".dynamicform_wrapper .product-item").each(function(index) {

        $("#products-"+index+"-"+index+"-products_id").change(function () {
            if($("#productstoinvoice-"+index+"-quantity").val() !== ''){
                var id = $(this).val();
                var count = $('#productstoinvoice-'+index+'-quantity').val();
                 getPrice(id, count, index)
            }
        });
        $("#productstoinvoice-"+index+"-quantity").change(function () {
            if($("#products-"+index+"-"+index+"-products_id").val() !== ''){
               var id = $("#products-"+index+"-"+index+"-products_id").val();
               var count = $(this).val();
                getPrice(id, count, index)
            }
        });
    });
});
//если удалил товар
//пересчитать цены
$(".dynamicform_wrapper").on("afterDelete", function(e) {
    Call_total()
});



function getPrice(id, count, index) {
    $.ajax({
        url: '/invoice/get_price',
        type:'POST',
        dataType: 'json',
        data: {'id': id, 'count': count},
        success: function (data) {
            if ($("#products-products_id").length){ //проверяем если есть такой елемент
                $("#productstoinvoice-0-total_price").empty();
                $("#productstoinvoice-0-total_price").val(data);
            }else{
                $("#productstoinvoice-"+index+"-total_price").empty();
                $("#productstoinvoice-"+index+"-total_price").val(data);
            }
            Call_total() //вызвать для пересчета
        }
    });
}

//просчет цен
function Call_total() {
    var summ = 0;
    var elem = $(".total-sum input");
    $(elem).each(function(){
        summ += parseInt($(this).val());
    });
    $('#invoice-total_summ').val(summ);

}


//ну как-то так, готов учится и развиваться!) 


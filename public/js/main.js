
$(function () {
    $("#datestart").datepicker({
        minDate: "-2Y",
        maxDate: "-1D",
        defaultDate: "-7d",
        dateFormat: 'yy-mm-dd'});
    $("#datestart").datepicker("setDate", "-2Y");

    $("#dateend").datepicker({
        minDate: "-2Y",
        maxDate: "-0D",
        defaultDate: null,
        dateFormat: 'yy-mm-dd'});
    $("#dateend").datepicker("setDate", "-0D");


    $("#contract")
            .selectmenu()
            .selectmenu("menuWidget")
            .addClass("overflow");
});

function getProducts(docId, tag, debCred, declar) {

    $.ajax({
        type: "POST",
        url: "/info/index/products",
        data: 'DocumentID=' + docId + '&Debit=' + debCred + '&Declar='+declar ,
        dataType: "text",
        success: function (data) {
            tag.html("");
            tag.html(data);
        }
    });
}


function ajx1C(mode)
{
    if ($("#datestart").datepicker("getDate") == null || $("#datestart").datepicker("getDate") == null) {
        alert('выберите период для выборки');

    } else {

        var dateStart = $.datepicker.formatDate("yy-mm-dd", $("#datestart").datepicker("getDate"));
        var dateEnd = $.datepicker.formatDate("yy-mm-dd", $("#dateend").datepicker("getDate"));
        var contractId = $('#contract').val();


        switch (mode)
        {
            case "trade":
                $.ajax({
                    type: "POST",
                    url: "/info/index/trade",
                    data: 'DateBegin=' + dateStart + '&DataEnd=' + dateEnd + '&ContractId=' + contractId,
                    dataType: "text",
                    success: function (data) {

                        $("#main_info").html("");
                        $("#main_info").html(data);
                    }
                });
                break;
            case "returns":
                $(".btn_choose a img").css("cursor", "no-drop");

                if (value == 0) {
                    $("#cssmenu").html("");
                    $("#carType").empty();
                    $("#carType").append($('<option value="0">Выберите модификацию</option>'));
                    $("#carType").prev().remove();
                    $("#carType").selectbox();
                    $("#cssmenu").css("visibility", "hidden");
                }

                $.ajax({
                    type: "POST",
                    url: "/td_trans/index.php",
                    data: 'ajax=2&modelId=' + value,
                    dataType: "json",
                    success: function (data) {

                        $("#carType").empty();
                        $("#carType").append($('<option value="0">Выберите модификацию</option>'));
                        for (var i = 0; i < data.length; i++)
                        {
                            $("#carType").append($('<option value="' + data[i][0] + '">' + data[i][1] + '</option>'));
                        }
                        $("#carType").prev().remove();
                        $("#carType").selectbox();
                        $("#cssmenu").html("");
                        $("#cssmenu").css("visibility", "hidden");
                    }
                });
                break;

            case "trade":
                if (value != 0) {
                    $(".btn_choose a img").css("cursor", "pointer");
                } else {
                    $(".btn_choose a img").css("cursor", "no-drop");
                }
                $("#cssmenu").html("");
                $("#cssmenu").css("visibility", "hidden");
                break;

            case "product":
                if (value != 0) {
                    $(".btn_choose a img").css("cursor", "pointer");
                } else {
                    $(".btn_choose a img").css("cursor", "no-drop");
                }
                $("#cssmenu").html("");
                $("#cssmenu").css("visibility", "hidden");
                break;
        }
    }


}








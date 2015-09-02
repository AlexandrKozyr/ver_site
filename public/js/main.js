
$(function () {
    $("#datestart").datepicker({
        minDate: "-3M",
        maxDate: "-1D",
        defaultDate: "-3M",
        dateFormat: 'yy-mm-dd'});
    $("#datestart").datepicker("setDate", "-3M");

    $("#dateend").datepicker({
        minDate: "-3M",
        maxDate: "-0D",
        defaultDate: null,
        dateFormat: 'yy-mm-dd'});
    $("#dateend").datepicker("setDate", "-0D");


    $("#contract")
            .selectmenu()
            .selectmenu("menuWidget")
            .addClass("overflow");


});

function spinToggle() {
    $('.cload_show').toggle();
}

function getProducts(docId, tag, debCred, declar) {
    spinToggle();
    $.ajax({
        type: "POST",
        url: "/info/index/products",
        data: 'DocumentID=' + docId + '&Debit=' + debCred + '&Declar=' + declar,
        dataType: "text",
        success: function (data) {
            tag.html("");
            tag.html(data);
            spinToggle();
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
                spinToggle();
                $.ajax({
                    type: "POST",
                    url: "/info/index/trade",
                    data: 'DateBegin=' + dateStart + '&DataEnd=' + dateEnd + '&ContractId=' + contractId,
                    dataType: "text",
                    success: function (data) {

                        $("#main_info").html("");
                        $("#main_info").html(data);
                        spinToggle();
                    }
                });
                break;

            case "receipts":
                spinToggle();
                $.ajax({
                    type: "POST",
                    url: "/info/index/receipts",
                    data: 'DateBegin=' + dateStart + '&DataEnd=' + dateEnd + '&ContractId=' + contractId,
                    dataType: "text",
                    success: function (data) {

                        $("#main_info").html("");
                        $("#main_info").html(data);
                        spinToggle();
                    }
                });
                break;

            case "returns":
                spinToggle();
                $.ajax({
                    type: "POST",
                    url: "/info/index/returns",
                    data: 'DateBegin=' + dateStart + '&DataEnd=' + dateEnd + '&ContractId=' + contractId,
                    dataType: "text",
                    success: function (data) {

                        $("#main_info").html("");
                        $("#main_info").html(data);
                        spinToggle();
                    }
                });
                break;


        }


    }


}








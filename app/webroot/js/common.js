$("#from-hours").change(function () {
    var selectedVal = $(this).val();
    $("#to-hours").val(selectedVal);
    $("#to-hours").prop("disabled", false);

    $.each($("#to-hours option"), function (i, v) {
        if ($(v).val() < selectedVal) {
            $(v).prop("disabled", true);
        } else {
            $(v).prop("disabled", false);
        }
    });
});

$(function () {
    $("#dela").datepicker({
//        defaultDate: null,
        autoSize: true,
        dateFormat: "dd.mm.yy",
        minDate: 0,
//      changeMonth: true,
//      numberOfMonths: 3,
        onClose: function (selectedDate) {
            $("#panala").datepicker("option", "minDate", selectedDate);
            
            if (!editView) {
                $("#panala").attr("disabled", false);
            }
        }
    });
    $("#panala").datepicker({
//        defaultDate: "+3d",
        autoSize: true,
        dateFormat: "dd.mm.yy",
        minDate: $("#dela").val(),
//      changeMonth: true,
//      numberOfMonths: 3,
        onClose: function (selectedDate) {
            $("#dela").datepicker("option", "maxDate", selectedDate);
        }
    });
});

$("#friendsList button").click(function () {
    var id = $(this).val();
    var checkArray = $.inArray(parseInt(id), ids);
    
    if(ids.length > 0) {
        
    }

    if (checkArray > -1) {
        ids.splice(checkArray, 1);
        $(this).removeClass("active");
        $("#friend_" + id).remove();
        if (ids.length == 0) {
            $("#users-place #info").show();
        }
    } else {
        ids.push(parseInt(id));
        $(this).addClass("active");
        html = '<span class="label label-primary custom-text-label" id="friend_' + id + '">';
        html += $(this).attr("title");
        html += '</span>';
        $("#users-place #info").hide();
        $("#users-place").append(html);
    }
    
//    $("#friends_hidden").val(ids);
    console.log(ids);

    checkCheckboxes();
});
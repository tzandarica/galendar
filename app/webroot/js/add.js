$(document).ready(function () {
    users = {};
    editView = true;
    if (window.location.href.search('edit') == -1) {
        editView = false;
        $("#panala").prop("disabled", true);
//        $("#to-hours").prop("disabled", true);
    }
    checkCheckboxes();
});

$("#isPrivate").click(function () {
    checkCheckboxes();
});

function checkCheckboxes() {
    var chkPrivate = $("#isPrivate").prop("checked");
    var chkMail = $("#isAlertMail").prop("checked");

    if (ids.length > 0) {
        $("#isAlertMail").prop("checked", true);
        $("#isAlertMail").prop("disabled", true);
    } else {
        if (chkPrivate) {
            $("#isAlertMail").prop("checked", false);
            $("#isAlertMail").prop("disabled", true);
        } else {
            $("#isAlertMail").prop("checked", chkMail);
            $("#isAlertMail").prop("disabled", false);
        }
    }
}

$("#save").click(function () {
    var data = {};
    data.friends = '';

    data.from_date = $("#dela").val();
    data.to_date = $("#panala").val();
    data.from_hour = $("#from-hours").val();
    data.to_hour = $("#to-hours").val();
    data.description = $("#description").val();
    data.is_alert_mail = $("#isAlertMail").prop('checked') ? 1 : 0;
    data.is_private = $("#isPrivate").prop('checked') ? 1 : 0;

    for (var i = 0; i < ids.length; i++) {
        data.friends += ids[i] + ",";
    }

    data.friends = data.friends.substring(0, data.friends.length - 1);

    $.ajax({
        type: "post",
        url: "add", // + getToken(true),
        data: data,
        success: function (data) {
            window.location.href = '../events/all';
        }
    });
});

$("#update").click(function () {
    var data = {};
    data.friends = '';

    data.from_date = $("#dela").val();
    data.to_date = $("#panala").val();
    data.from_hour = $("#from-hours").val();
    data.to_hour = $("#to-hours").val();
    data.description = $("#description").val();
    data.is_alert_mail = $("#isAlertMail").prop('checked') ? 1 : 0;
    data.is_private = $("#isPrivate").prop('checked') ? 1 : 0;

    for (var i = 0; i < ids.length; i++) {
        data.friends += ids[i] + ",";
    }

    data.friends = data.friends.substring(0, data.friends.length - 1);

    $.ajax({
        type: "post",
        url: window.location.href, // + getToken(true),
        data: data,
        success: function (data) {
            window.location.href = '../../events/all';
        }
    });
});

$("#delete").click(function() {
    var r = window.location.href.split('/');
    eventId = parseInt(r[r.length - 1]);
    $.ajax({
        type: "post",
        url: '../delete/'+eventId, // + getToken(true),
//        data: {"id": eventId},
        success: function (data) {
            window.location.href = '../all';
        }
    });
});

/*
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
        defaultDate: "+3d",
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
        defaultDate: "+3d",
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
    checkCheckboxes();
});
*/
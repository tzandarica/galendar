$(document).ready(function () {
    toggleList();
    editView = false;
});

function toggleList() {
    if ($(".covered") !== undefined && !$(".covered").is(":visible")) {
        $(".desc", $(".covered").parent()).css("max-width", "74%");
    }
    var this_bkp = null;
    var op_bkp = null;
    $(".toggle").click(function () {
        var op = $(".date", this).css("opacity");
        if(this_bkp != null && op_bkp != null) {
            if(op_bkp < 1) { console.log("mic");
                $(".date", this_bkp).css("opacity", "0.1");
            }
        }
        if ($(".details", this).is(":visible")) { console.log("visible");
            $(".details", this).hide();
            $(".bottom-buttons", this).hide();
            if(op < 1) {
                $(".date", this).css("opacity", "0.1");
            }
        } else {
            $(".details").hide();
            $(".details", this).toggle();
            $(".bottom-buttons", this).toggle();
            if(op < 1) {
                $(".date", this).css("opacity", "0.8");
            }
        }
        this_bkp = this;
        op_bkp = op;
    });
}
$("#doSearch").click(function() {
    $("#searchForm").submit();
});
$("#search").click(function() {
    $("#searchBox").slideToggle();
});

function checkCheckboxes() {
    // empty function - quick workaround :)
}
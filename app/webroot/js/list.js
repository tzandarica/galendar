$(document).ready(function () {
    toggleList();
});

function toggleList() {
    if ($(".covered") !== undefined && !$(".covered").is(":visible")) {
        $(".desc", $(".covered").parent()).css("max-width", "74%");
    }

    $(".toggle").click(function () {
        if ($(".details", this).is(":visible")) {
            $(".details", this).hide();
            $(".bottom-buttons", this).hide();
        } else {
            $(".details").hide();
            $(".details", this).toggle();
            $(".bottom-buttons", this).toggle();
        }
    });
}
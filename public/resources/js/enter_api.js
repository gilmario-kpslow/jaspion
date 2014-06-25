$('.enterapi').keypress(function(event) {
    if (event.keyCode == 13) {
//        var j = jQuery(this).serialize().replace("=", "").split("&");
//        $(this).find("input[name='" + j[5] + "']").focus();
        return false;
    }
});
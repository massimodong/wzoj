$(document).ready(function () {
    $(".openpop").click(function (e) {
        e.preventDefault();
        $("#popup-iframe").attr("src", $(this).attr('href'));
        $("#popup").fadeIn('slow');
    });

    $("#close-popup").click(function () {
        $(this).parent().fadeOut("slow");
    });
});

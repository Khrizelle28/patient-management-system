$(document).ready(function () {
    $(".kebab-icon").on("click", function (e) {
        e.stopPropagation(); // Prevent click from bubbling up
        const menu = $(this).next(".menu-options");
        $(".menu-options").not(menu).removeClass("show"); // Close others
        menu.toggleClass("show");
    });

    $(document).on("click", function () {
        $(".menu-options").removeClass("show");
    });
});
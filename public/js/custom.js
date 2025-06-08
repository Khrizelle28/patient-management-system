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

    $('#imageInput').on('change', function(event) {
        let file = event.target.files[0];

        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide();
        }
    });

    document.querySelectorAll('.day-toggle').forEach(toggle => {
        toggle.addEventListener('change', function () {
        const day = this.dataset.day;
        const fields = document.querySelectorAll(`.${day}-field`);
        fields.forEach(field => {
            field.disabled = !this.checked;
        });
        });
    });

 /*sidebar click change color*/
    document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.nav-link');

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove 'active' from all
            menuItems.forEach(i => i.classList.remove('active'));

            // Add 'active' to clicked item
            item.classList.add('active');
        });
    });
});
});

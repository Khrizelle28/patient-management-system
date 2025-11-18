window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple);
    }

    const datatablesMedicine = document.getElementById('datatablesMedicine');
    if (datatablesMedicine) {
        new simpleDatatables.DataTable(datatablesMedicine);
    }

    $('.tableAdmin tbody tr').each(function () {
        $(this).find('td:eq(6)').addClass('table-options');
    });

      $('.tablePatient tbody tr').each(function () {
        $(this).find('td:eq(8)').addClass('table-options');
    });
});

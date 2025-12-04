// Helper function to initialize tooltips
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        const dataTable = new simpleDatatables.DataTable(datatablesSimple);

        // Reinitialize tooltips after DataTable renders
        dataTable.on('datatable.init', function() {
            initializeTooltips();
        });

        // Reinitialize tooltips on page change, search, sort
        dataTable.on('datatable.page', function() {
            initializeTooltips();
        });

        dataTable.on('datatable.search', function() {
            initializeTooltips();
        });

        dataTable.on('datatable.sort', function() {
            initializeTooltips();
        });
    }

    const datatablesMedicine = document.getElementById('datatablesMedicine');
    if (datatablesMedicine) {
        const medicineDT = new simpleDatatables.DataTable(datatablesMedicine);
        medicineDT.on('datatable.init', initializeTooltips);
        medicineDT.on('datatable.page', initializeTooltips);
        medicineDT.on('datatable.search', initializeTooltips);
        medicineDT.on('datatable.sort', initializeTooltips);
    }

    const datatablesMedicineIncome = document.getElementById('datatablesMedicineIncome');
    if (datatablesMedicineIncome) {
        const medicineIncomeDT = new simpleDatatables.DataTable(datatablesMedicineIncome);
        medicineIncomeDT.on('datatable.init', initializeTooltips);
        medicineIncomeDT.on('datatable.page', initializeTooltips);
        medicineIncomeDT.on('datatable.search', initializeTooltips);
        medicineIncomeDT.on('datatable.sort', initializeTooltips);
    }

    const datatablesDoctorOwn = document.getElementById('datatablesDoctorOwn');
    if (datatablesDoctorOwn) {
        const doctorOwnDT = new simpleDatatables.DataTable(datatablesDoctorOwn);
        doctorOwnDT.on('datatable.init', initializeTooltips);
        doctorOwnDT.on('datatable.page', initializeTooltips);
        doctorOwnDT.on('datatable.search', initializeTooltips);
        doctorOwnDT.on('datatable.sort', initializeTooltips);
    }

    $('.tableAdmin tbody tr').each(function () {
        $(this).find('td:eq(6)').addClass('table-options');
    });

      $('.tablePatient tbody tr').each(function () {
        $(this).find('td:eq(8)').addClass('table-options');
    });
});

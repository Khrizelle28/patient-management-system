let PatientSystem = (function () {
    let ui = {};

    function bindUi() {
        this._ui = {
            type: "[name=type]",
            chkboxRemarks: "input[name='remarks[]']",
            chkboxRemarksOther: "input[name='remarks[]'][value='others']",
            selectRole: "select[name='role']"
        };
        return this._ui;
    }
    function bindEvents() {
        $(document).on("change", _ui.selectRole, selectRoleFunc);
    }

    function selectRoleFunc()
    {
        var selectedRole = $(_ui.selectRole).val();  // Get selected value
        if (selectedRole === 'Doctor') {
            $(".schedule--container").show();
        } else {
            $(".schedule--container").hide();
        }
    }

    function onLoad()
    {
        // Toggle the side navigation
        const sidebarToggle = document.body.querySelector('#sidebarToggle');
        if (sidebarToggle) {
            // Uncomment Below to persist sidebar toggle between refreshes
            // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            //     document.body.classList.toggle('sb-sidenav-toggled');
            // }
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                document.body.classList.toggle('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            });
        }
    }

    function init() {
        ui = bindUi();
        onLoad();
        bindEvents();
        selectRoleFunc();
    }

    return {
        init: init,
        _ui: ui,
    };
})();

$(document).ready(function () {
    PatientSystem.init();
});

let Diagnosis = (function () {
    let ui = {};

    function bindUi() {
        this._ui = {
            type: "[name=type]",
            chkboxRemarks: "input[name='remarks[]']",
            chkboxRemarksOther: "input[name='remarks[]'][value='others']"
        };
        return this._ui;
    }
    function bindEvents() {
        $(document).on("click", _ui.type, checkUpType);
        $(document).on("click", _ui.chkboxRemarks, updateRemarks);
    }

    function checkUpType() {
        if($(this).val() == 'pregnant')
        {
            $(".pregnant-details").show();
            $("[name='txtarea_remarks']").closest('.row').hide();
            if ($(".chkbox_others").is(':checked')) {
                $("[name='txtarea_remarks']").closest('.row').show();
            } 
        } else
        {
            $(".pregnant-details").hide();
        }
    }

    function updateRemarks()
    {
        if($(_ui.chkboxRemarksOther).is(':checked'))
        {

        }
    }

    function init() {
        ui = bindUi();
        bindEvents();
    }

    return {
        init: init,
        _ui: ui,
    };
})();

$(document).ready(function () {
    Diagnosis.init();

});

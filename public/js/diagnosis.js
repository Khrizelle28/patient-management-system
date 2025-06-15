let Diagnosis = (function () {
    let ui = {};

    function bindUi() {
        this._ui = {
            type: "[name=type]",
            chkboxRemarks: "input[name='remarks[]']",
            chkboxRemarksOther: "input[name='remarks[]'][value='others']",
            toComeBack: "#toComeBack"
        };
        return this._ui;
    }
    function bindEvents() {
        $(document).on("click", _ui.type, checkUpType);
        $(document).on("click", _ui.chkboxRemarks, updateRemarks);
        $(document).on("change", _ui.toComeBack, toggleFields);
    }

    function checkUpType() {
        // console.log($(this), $('input[name="type"]:checked').val());
        if($('input[name="type"]:checked').val() == 'pregnant')
        {
            $(".pregnant-details").show();
            $("[name='txtarea_remarks']").closest('.row').hide();
            if ($(".chkbox_others").is(':checked')) {
                $("[name='txtarea_remarks']").closest('.row').show();
            }
        } else
        {
            $(".pregnant-details").hide();
            $("[name='txtarea_remarks']").closest('.row').show();
        }
    }

    function toggleFields() {
        if ($('#toComeBack').is(':checked')) {
            $('#returnDateField').show();
            $('#reasonField').hide();
        } else {
            $('#returnDateField').hide();
            $('#reasonField').show();
        }
    }

    function updateRemarks()
    {
        if($(_ui.chkboxRemarksOther).is(':checked'))
        {
            $("[name='family_histories']").show();
            checkUpType();
        } else
        {
            $("[name='family_histories']").hide();
        }
    }

    function onLoad()
    {
        console.log($(_ui.type).val());
        if($(_ui.type).val() && $(_ui.type).is(':checked'))
        {
            checkUpType();
        }
    }

    function init() {
        ui = bindUi();
        onLoad();
        bindEvents();
        toggleFields();
    }

    return {
        init: init,
        _ui: ui,
    };
})();

$(document).ready(function () {
    Diagnosis.init();

});

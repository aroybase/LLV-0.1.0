$(document).ready(function () {
    Shadowbox.init({
        handleOversize:"drag",
        modal:false,
        overlayOpacity:0.75
    });

    $('.jq-tabs').tabs({ spinner: "Retrieving data..." });

    if (i18n != undefined) {
        $('input.jq-datepicker').datepicker({
            closeText:i18n.datepicker.closeText,
            prevText:i18n.datepicker.prevText,
            nextText:i18n.datepicker.nextText,
            currentText:i18n.datepicker.currentText,
            monthNames:i18n.datepicker.monthNames,
            dayNamesMin:i18n.datepicker.dayNamesMin,
            weekHeader:i18n.datepicker.weekHeader,
            dateFormat:i18n.datepicker.dateFormat,
            firstDay:i18n.datepicker.firstDay
        });
    }
});
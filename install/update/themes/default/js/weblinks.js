/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_del_rows(url, id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(url + '&nocache=' + new Date().getTime(), 'id=' + id, function(res) {
            var r_split = res.split('_');
            if (r_split[0] == 'OK') {
                alert(r_split[1]);
                location.reload()
            } else if (r_split[0] == 'NO') {
                alert(r_split[1])
            } else {
                alert(nv_is_del_confirm[2])
            }
        })
    }
    return false
}

function reportSubmit(event, form) {
    event.preventDefault();
    $('#report-success, #report-error').hide();

    var report = $('input[type=radio][name=report]:checked', form).val(),
        note = $('[name=report_note]', form).val();
    note = trim(strip_tags(note));
    $('[name=report_note]', form).val(note);
    if ('0' == report && 10 >= note.length) {
        $('#report-error').show();
        $('[name=report_note]', form).focus();
        return !1
    }

    $.ajax({
        type: $(form).prop("method"),
        cache: !1,
        url: $(form).prop("action"),
        data: $(form).serialize() + '&submit=1',
        dataType: "json",
        success: function(e) {
            if ("error" == e.status) {
                alert(e.mess)
            } else {
                $('#reportForm').hide();
                $('#report-success').text(e.mess).show();
                setTimeout(function() {
                    $('[data-dismiss=modal]', form).trigger('click')
                }, 5000)
            }
        }
    })
}

function reportChange(form) {
    '0' == $('input[type=radio][name=report]:checked', form).val() ?
        $('#other_show').slideDown('slow') :
        $('#other_show').hide()
}

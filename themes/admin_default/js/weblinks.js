/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_chang_cat(object, catid, mod) {
    var new_vid = $(object).val();
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
        location.reload();
    });
    return;
}

function nv_del_cat(catid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_cat&nocache=' + new Date().getTime(), 'catid=' + catid, function(res) {
            var r_split = res.split("_");
            if (r_split[0] == 'OK') {
                location.reload();
            } else if (r_split[0] == 'ERR') {
                alert(r_split[1]);
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_del_rows(id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_link&nocache=' + new Date().getTime(), 'id=' + id, function(res) {
            var r_split = res.split('_');
            if (r_split[0] == 'OK') {
                alert(r_split[1]);
                location.reload();
            } else if (r_split[0] == 'NO') {
                alert(r_split[1]);
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_del_select_rows(oForm, msgnocheck) {

    var fa = oForm['idcheck[]'];
    var listid = '';
    if (fa.length) {
        for (var i = 0; i < fa.length; i++) {
            if (fa[i].checked) {
                listid = listid + ',' + fa[i].value;
            }
        }
    } else {
        if (fa.checked) {
            listid = listid + ',' + fa.value;
        }
    }
    if (listid != '') {
        if (confirm(nv_is_del_confirm[0])) {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_link&nocache=' + new Date().getTime(), 'listid=' + listid, function(res) {
                var r_split = res.split('_');
                if (r_split[0] == 'OK') {
                    alert(r_split[1]);
                    location.reload();
                } else if (r_split[0] == 'NO') {
                    alert(r_split[1]);
                } else {
                    alert(nv_is_del_confirm[2]);
                }
            });
        }
    } else {
        alert(msgnocheck);
    }
    return false;
}

function get_alias() {
    var title = strip_tags(document.getElementById('idtitle').value);
    if (title != '') {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title), function(res) {
            if (res != "") {
                document.getElementById('idalias').value = res;
            } else {
                document.getElementById('idalias').value = '';
            }
        });
    }
    return false;
}

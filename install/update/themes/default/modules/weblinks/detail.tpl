<!-- BEGIN: main -->
<div id="weblinks">
    <div class="margin-top margin-bottom">
        <div class="pull-right">
            <em class="fa fa-location-arrow">&nbsp;</em><a title="{LANG.report}" href="javascript:void(0);" onclick="reportModal(event,'{DETAIL.report}');">{LANG.report}</a>
        </div>
        <h2>{DETAIL.title}</h2>
    </div>
    <div class="flex-bl">
        <div class="main">
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td class="tableleft"><strong>{LANG.name}:</strong> <span class="mobile-inline-block"><a href="{DETAIL.visit}" target="_blank" title="{DETAIL.title}"><em class="fa fa-link margin-right-sm"></em>{DETAIL.url}</a></span></td>
                        <td class="tableright"><a title="{DETAIL.title}" href="{DETAIL.visit}" target="_blank"><em class="fa fa-link margin-right-sm"></em><strong>{DETAIL.url}</strong></a></td>
                    </tr>
                    <tr>
                        <td class="tableleft">{LANG.regiter}: <span class="mobile-inline-block">{DETAIL.add_time}</span></td>
                        <td class="tableright">{DETAIL.add_time}</td>
                    </tr>
                    <tr>
                        <td class="tableleft">{LANG.edit_time}: <span class="mobile-inline-block">{DETAIL.edit_time}</span></td>
                        <td class="tableright">{DETAIL.edit_time}</td>
                    </tr>
                    <tr>
                        <td class="tableleft">{LANG.cat}: <span class="mobile-inline-block">
                                <ul class="cat-list">{DETAIL.cat}</ul>
                            </span></td>
                        <td class="tableright">
                            <ul class="cat-list">{DETAIL.cat}</ul>
                        </td>
                    </tr>
                    <tr>
                        <td class="tableleft">{LANG.visit}: <span class="mobile-inline-block">{DETAIL.hits_total}</span></td>
                        <td class="tableright">{DETAIL.hits_total}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- BEGIN: img -->
        <div class="aside">
            <div class="imgw image-4-3">
                <img src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif" alt="" style="background-image: url({IMG});" />
            </div>
        </div>
        <!-- END: img -->
    </div>
    <!-- BEGIN: des -->
    <div class="margin-bottom-sm">
        <strong>{LANG.description}: </strong>
    </div>
    <div>
        {DETAIL.description}
    </div>
    <!-- END: des -->
    <div class="margin-top-lg">
        {ADMIN_LINK}
    </div>
</div>
<script>
    function reportModal(event, url) {
        event.preventDefault();
        $.ajax({
            type: 'GET',
            cache: !1,
            url: url,
            success: function(data) {
                modalShow('{LANG.report}',data)
            }
        })
    }
</script>
<!-- END: main -->
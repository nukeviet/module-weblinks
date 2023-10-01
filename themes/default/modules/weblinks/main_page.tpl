<!-- BEGIN: main -->
<div id="weblinks">
    <!-- BEGIN: cat_loop -->
    <div class="cat-item">
        <div class="panel panel-primary margin-bottom">
            <div class="panel-heading">
                <a href="{CAT.link}"><strong>{CAT.title}</strong></a>
                <!-- BEGIN: sub -->
                <ul class="cat-list childs">
                    <!-- BEGIN: loop --><li><a href="{SUBCAT.link}">{SUBCAT.title}</a></li><!-- END: loop -->
                </ul>
                <!-- BEGIN: next -->
                <span class="pull-right"><a class="btn btn-xs btn-primary next-btn" href="{CAT.link}">{LANG.next_title}<em class="fa fa-angle-right margin-left-sm"></em></a></span>
                <!-- END: next -->
                <!-- END: sub -->
            </div>
        </div>

        <!-- BEGIN: item -->
        <div class="item">
            <a class="pull-right btn btn-default btn-xs more-btn" href="{ITEM.link}">{LANG.more}<em class="fa fa-angle-right margin-left-sm"></em></a>
            <h3>
                <a href="{ITEM.link}"><strong>{ITEM.title}</strong></a>
                <!-- BEGIN: new_icon -->
                <img src="{NV_STATIC_URL}themes/default/images/icons/new.gif" alt="" />
                <!-- END: new_icon -->
            </h3>
            <div class="flex-bl">
                <!-- BEGIN: img -->
                <div class="aside">
                    <div class="imgw image-4-3">
                        <a href="{ITEM.link}" title="{ITEM.title}"><img src="{NV_STATIC_URL}{NV_ASSETS_DIR}/images/pix.gif" alt="" style="background-image: url({SRC_IMG});" /></a>
                    </div>
                </div>
                <!-- END: img -->
                <div class="main">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <td class="tableleft">{LANG.name_link}: <span class="mobile-inline-block"><a href="{ITEM.linkvi}" target="_blank" title="{ITEM.title}"><em class="fa fa-link margin-right-sm"></em>{ITEM.url}</a></span></td>
                                <td class="tableright"><a href="{ITEM.linkvi}" target="_blank" title="{ITEM.title}"><em class="fa fa-link margin-right-sm"></em>{ITEM.url}</a></td>
                            </tr>
                            <tr>
                                <td class="tableleft">{LANG.regiter}: <span class="mobile-inline-block">{ITEM.add_time}</span></td>
                                <td class="tableright">{ITEM.add_time}</td>
                            </tr>
                            <tr>
                                <td class="tableleft">{LANG.cat}: <span class="mobile-inline-block">
                                        <ul class="cat-list">{ITEM.cat}</ul>
                                    </span></td>
                                <td class="tableright">
                                    <ul class="cat-list">{ITEM.cat}</ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="tableleft">{LANG.view_title}: <span class="mobile-inline-block">{ITEM.hits_total}</span></td>
                                <td class="tableright">{ITEM.hits_total}</td>
                            </tr>
                            <!-- BEGIN: admin_links -->
                            <tr>
                                <td colspan="2">
                                    {ADMIN_LINK}
                                </td>
                            </tr>
                            <!-- END: admin_links -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END: item -->
    </div>
    <!-- END: cat_loop -->
</div>
<span class="edit_icon"></span>
<span class="delete_icon"></span>
<!-- END: main -->

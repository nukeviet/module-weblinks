<!-- BEGIN: main -->
<div id="list_mods">
	<!-- BEGIN: data -->
	<form class="form-inline" method="post" action="{FORM_ACTION}" onsubmit="delBrokenSubmit(event, this);">
		<table class="table table-striped table-bordered table-hover">
			<caption>{LANG.weblink_link_recent}</caption>
			<thead>
				<tr>
					<th class="text-center w50"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th>{LANG.weblink_add_title}</th>
					<th>{LANG.weblink_add_url}</th>
					<th>{LANG.weblink_link_broken_status}</th>
					<th class="w100"></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8">
						<input type="hidden" name="delBroken" value="1"/>
						<input class="btn btn-primary" type="submit" value="{LANG.link_broken_out}">
					</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" name="idcheck[]" value="{ROW.id}"></td>
					<td>{ROW.title}</td>
					<td><a href="{ROW.url}" target="_blank">{ROW.url}</a></td>
					<td>{ROW.type}</td>
					<td class="text-center text-nowrap">
						<em class="fa fa-search fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="reportView('#v-{ROW.tt}', '{ROW.report_title}')">{LANG.view_broken_info}</a>
						<em class="fa fa-edit fa-lg">&nbsp;</em><a href="{ROW.url_edit}">{LANG.weblink_method_edit}</a>
						<div id="v-{ROW.tt}" style="display:none">
							<table class="table table-striped table-bordered" style="margin-bottom:0">
								<colgroup>
									<col style="width: 30%"/>
								</colgroup>
								<tbody>
									<tr>
										<td class="text-nowrap"><strong>{LANG.weblink_link_broken_status}:</strong></td>
										<td>{ROW.type}</td>
									</tr>
									<tr>
										<td class="text-nowrap"><strong>{LANG.report_note}:</strong></td>
										<td>{ROW.report_note}</td>
									</tr>
									<tr>
										<td class="text-nowrap"><strong>{LANG.report_time}:</strong></td>
										<td>{ROW.report_time_format}</td>
									</tr>
									<tr>
										<td class="text-nowrap"><strong>{LANG.report_ip}:</strong></td>
										<td>{ROW.report_ip}</td>
									</tr>
									<tr>
										<td class="text-nowrap"><strong>{LANG.report_browse_name}:</strong></td>
										<td>{ROW.report_browse_name}</td>
									</tr>
									<tr>
										<td class="text-nowrap"><strong>{LANG.report_os_name}:</strong></td>
										<td>{ROW.report_os_name}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</form>
	<div id="rView" class="modal fade" tabindex="-1" role="dialog">
	    <div class="modal-dialog" role="document">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h3 class="modal-title"></h3>
	            </div>
	            <div class="modal-body"></div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">{LANG.modal_close}</button>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- END: data -->
	<!-- BEGIN: empty -->
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center">{LANG.weblink_link_broken}</td>
			</tr>
		</tbody>
	</table>
	<!-- END: empty -->
</div>
<!-- BEGIN: generate_page -->
<p class="text-center">{GENERATE_PAGE}</p>
<!-- END: generate_page -->
<!-- END: main -->
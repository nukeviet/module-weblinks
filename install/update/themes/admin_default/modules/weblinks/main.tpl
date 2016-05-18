<!-- BEGIN: main -->
<div id="list_mods">
	<form class="form-inline" name="listlink" method="post" action="{FORM_ACTION}">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<caption>{LANG.weblink_link_recent}</caption>
				<thead>
					<tr>
						<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
						<th class="text-center">{LANG.weblink_add_title}</th>
						<th class="text-center">{LANG.weblink_add_url}</th>
						<th class="text-center">{LANG.weblink_add_click}</th>
						<th class="text-center">{LANG.weblink_inhome}</th>
						<th class="text-center">{LANG.weblink_method}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="6">
							<input type="button" class="btn btn-primary" onclick="nv_del_select_rows(this.form, '{LANG.msgnocheck}')" value="{LANG.weblink_method_del}" /> 
						</td>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN: loop -->
					<tr>
						<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]"></td>
						<td>{ROW.title}</td>
						<td>{ROW.url}</td>
						<td class="text-center">{ROW.hits_total}</td>
						<td class="text-center">{ROW.status}</td>
						<td class="text-center">
							<em class="fa fa-edit fa-lg">&nbsp;</em>
							<a class="edit_icon" href="{ROW.url_edit}">{LANG.weblink_method_edit}</a>&nbsp;-&nbsp;
							<em class="fa fa-trash-o fa-lg">&nbsp;</em>
							<a href="javascript:void(0);" onclick="nv_del_rows('{ROW.id}')" >{LANG.weblink_method_del}</a>
						</td>
					</tr>
					<!-- END: loop -->
				</tbody>
			</table>
		</div>
	</form>
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
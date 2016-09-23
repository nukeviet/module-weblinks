<!-- BEGIN: main -->
<!-- BEGIN: check -->
<table class="table table-striped table-bordered table-hover">
	<!-- BEGIN: generate_page -->
	<tfoot>
		<tr>
			<td>{GENERATE_PAGE}</td>
		</tr>
	</tfoot>
	<!-- END: generate_page -->
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>
			<!-- BEGIN: ok -->
			{URL} - OK
			<!-- END: ok -->
			<!-- BEGIN: error -->
			<span style="text-decoration:line-through">{URL}</span> {LANG.weblink_check_error}
			<!-- END: error -->
			</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: check -->
<!-- BEGIN: form -->
<div class="alert alert-warning">{LANG.weblink_check_notice}</div>
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td>
			<form class="form-inline" name="confirm" action="{FORM_ACTION}" method="post">
				<input type="hidden" name="ok" value="1">
				<input class="btn btn-primary" type="submit" value="{LANG.weblink_check_confirm}" name="submit">
			</form></td>
		</tr>
	</tbody>
</table>
<!-- END: form -->
<!-- END: main -->
<!-- BEGIN: main -->
<!-- BEGIN: close -->
<script type="text/javascript">
var howLong = 3000;
setTimeout("self.close()", howLong);
</script>
<!-- END: close -->
<script type="text/javascript">
$(document).ready(function(){
	$('#othersRe').click(function() {
		if ($(this).is(':checked')) {
			$('#other_show').show();
		} else {
			$('#other_show').hide();
		}
	});	
});
</script>
<div class="panel-body">
	<h3>{LANG.report_notice}</h3>
	<form class="form-inline" method="post" action="{ROW.action}">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<td><input type="radio" name="report" value="1" id="report_0" checked="checked" />{LANG.report_linkdie} <input type="radio" name="report" value="2" id="report_1" />{LANG.report_badlink} </td>
					</tr>
					<tr>
						<td><input type="checkbox" value="1" id="othersRe"/><strong>{LANG.report_note}</strong></td>
					</tr>
					<tr id="other_show" style="display:none">
						<td><textarea class="form-control" rows="3" name="report_note" id="report_3">{ROW.report_note}</textarea></td>
					</tr>
					<tr>
						<td>
							<input type="hidden" name="report_id" value="{ROW.id}" />
							<input type="hidden" name="link" value="{ROW.link}" />
							<input class="btn btn-primary" type="submit" name="submit" value="{LANG.report_confirm}"/>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>
<!-- BEGIN: success -->
<p class="alert alert-success">
	{LANG.report_success}
</p>
<!-- END: success -->
<!-- BEGIN: error -->
<p class="alert alert-danger">
	{ROW.error}
</p>
<!-- END: error -->
<!-- END: main -->
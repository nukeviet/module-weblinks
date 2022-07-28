<!-- BEGIN: main -->
<div id="reportForm" class="panel">
	<div class="panel-body">
		<h3>{LANG.report_notice}</h3>
		<form id="report-Submit" method="post" action="{ROW.action}" onsubmit="reportSubmit(event,this)">
			<ul class="list-group">
				<li class="list-group-item radio">
					<label><input type="radio" name="report" value="1" checked="checked" style="margin-top:2px" onchange="reportChange(this.form);"/> {LANG.report_linkdie}</label>
				</li>
				<li class="list-group-item radio">
					<label><input type="radio" name="report" value="2" style="margin-top:2px" onchange="reportChange(this.form);"/>{LANG.report_badlink}</label>
				</li>
				<li class="list-group-item radio">
					<label><input type="radio" name="report" value="0" style="margin-top:2px" onchange="reportChange(this.form);"/>{LANG.report_note}</label>
					<div id="other_show" class="margin-top" style="display:none">
						<textarea class="form-control" rows="3" name="report_note" maxLength="255">{ROW.report_note}</textarea>
						<small id="report-error" class="text-danger margin-top" style="display:none">{LANG.error_word_min}</small>
					</div>
				</li>
			</ul>
			<div>
				<input type="hidden" name="report_id" value="{ROW.id}" />
				<input type="hidden" name="link" value="{ROW.link}" />
				<input class="btn btn-primary" type="submit" value="{LANG.report_confirm}"/>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</form>
	</div>
</div>
<p id="report-success" class="alert alert-success" style="display:none"></p>
<!-- END: main -->
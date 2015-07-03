<!-- BEGIN: main -->
<div id="weblink">
	<h2>{DETAIL.title}</h2>
	<div class="pull-right">
		<em class="fa fa-location-arrow">&nbsp;</em><a title="{LANG.report}" href="javascript:void(0);" onclick="window.open('{DETAIL.report}','','menubar=no,location=no,resizable=no,scrollbars=no,status=no,width=600,height=400');return false">{LANG.report}</a>
	</div>
	<div class="thumb_imgweb">
		<p>
			<!-- BEGIN: img -->
			<img src="{IMG}" alt="" />
			<!-- END: img -->
			<strong>{LANG.name}: </strong>
			<a title="{DETAIL.title}" href="{DETAIL.visit}" target="_blank"><strong>{DETAIL.url}</strong></a>
			<br />
			{LANG.visit}: <span style="color:#F90">{DETAIL.hits_total}</span>
			<br />
			{LANG.regiter}: {DETAIL.add_time}
			<br />
			{LANG.edit_time}: {DETAIL.edit_time}
		</p>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<!-- BEGIN: des -->
	<div>
		<strong>{LANG.description}: </strong>
	</div>
	<div>
		<div class="padding">
			{DETAIL.description}
		</div>
	</div>
	<div class="clear"></div>
	<!-- END: des -->
	<div align="right">
		{ADMIN_LINK}
	</div>
</div>
<!-- END: main -->
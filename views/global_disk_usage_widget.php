<div class="col-lg-4 col-md-6">
	<div class="card" id="global-disk-usage-widget">
		<div class="card-header" data-container="body">
			<i class="fa fa-hdd-o"></i>
			    <span data-i18n="disk_report.global_disk_usage"></span>
			    <a href="/show/listing/disk_report/disk" class="pull-right"><i class="fa fa-list"></i></a>
			
		</div>
		<div class="card-body text-center"></div>
	</div><!-- /panel -->
</div><!-- /col -->

<script>
$(document).on('appUpdate', function(e, lang) {

    $.getJSON( appUrl + '/module/disk_report/get_global_used_free', function( data ) {

    	if(data.error){
    		//alert(data.error);
    		return;
    	}

		var panel = $('#global-disk-usage-widget div.card-body'),
		baseUrl = appUrl + '/show/listing/disk_report/disk';
		panel.empty();

		// Set statuses
        if(data.free != "0" && data.free >= 10000000000){
        // Set free box to yellow if under 10GB free
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.free, 2)+'</span><br>'+i18n.t('free_disk_space')+'</a>');
		} else if(data.free != "0" ){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-warning"><span class="bigger-150">'+fileSize(data.free, 2)+'</span><br>'+i18n.t('free_disk_space')+'</a>');
		} else if(data.free) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.free, 2)+'</span><br>'+i18n.t('free_disk_space')+'</a>');
        }
        
		if(data.used != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.used, 2)+'</span><br>'+i18n.t('disk_report.used_disk_space')+'</a>');
		} else if(data.used) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.used, 2)+'</span><br>'+i18n.t('disk_report.used_disk_space')+'</a>');
        }
        
		if(data.total != "0"){
			panel.append(' <a href="'+baseUrl+'" class="btn btn-info"><span class="bigger-150">'+fileSize(data.total, 2)+'</span><br>'+i18n.t('disk_report.total_disk_space')+'</a>');
		} else if(data.total) {
            panel.append(' <a href="'+baseUrl+'" class="btn btn-info disabled"><span class="bigger-150">'+fileSize(data.total, 2)+'</span><br>'+i18n.t('disk_report.total_disk_space')+'</a>');
        }

    });
});
</script>

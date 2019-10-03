<div class="col-lg-4">
    <h4 data-i18n="disk_report.storage"></h4>
    <table id="disk_report_detail" class="table"></table>
</div>

<script>
$(document).on('appReady', function(){
        // Get disk_report data
        $.getJSON( appUrl + '/module/disk_report/get_data/' + serialNumber, function( data ) {
                $.each(data, function(index, item){
            $('#disk_report_detail')
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('disk_report.size')))
                    .append($('<td>')
                        .html(fileSize(item.totalsize, 1))))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('disk_report.used')))
                    .append($('<td>')
                        .html(fileSize(item.totalsize - item.freespace, 1))))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('disk_report.free')))
                    .append($('<td>')
                        .html(fileSize(item.freespace, 1))))
                .append($('<tr>')
                    .append($('<th>')
                        .text(i18n.t('disk_report.smartstatus')))
                    .append($('<td>')
                        .html(item.smartstatus)))

                if (item.encrypted == "0") {
                    $('#disk_report_detail')
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('disk_report.encryption_status')))
                        .append($('<td>')
                            .text(item.encrypted.replace("0", i18n.t('disk_report.not_encrypted'))))
                    )
                } else if (item.encrypted == "1") {
                    $('#disk_report_detail')
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('disk_report.encryption_status')))
                        .append($('<td>')
                            .text(item.encrypted.replace("1", i18n.t('disk_report.encrypted'))))
                    )
                } else {
                    $('#disk_report_detail')
                    .append($('<tr>')
                        .append($('<th>')
                            .text(i18n.t('disk_report.encryption_status')))
                        .append($('<td>')
                            .text(item.encrypted)))
                }

                ;
                });
    });
});
</script>

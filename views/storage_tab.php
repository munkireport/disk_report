<div id="storage-tab"></div>
<h2 data-i18n="disk_report.storage"></h2>

<div id="storage-plot"></div>

<script>
$(document).on('appReady', function(e, lang) {
    drawStoragePlots(serialNumber, 'storage-plot');
});
</script>

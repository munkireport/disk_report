// Formatters
var formatVolumeType = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        volumeType = col.text();
        volumeType = volumeType == 'bootcamp' ? i18n.t('disk_report.bootcamp') :
        (volumeType === 'fusion' ? i18n.t('disk_report.Fusion') : volumeType)
    col.text(volumeType)
}

var smartStatus = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
    smartstatus = col.text();
    smartstatus = smartstatus == 'Failing' ? '<span class="label label-danger">'+i18n.t('failing')+'</span>' : (smartstatus)
    col.html(smartstatus)
}

var encryptionStatus = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
    encryptionstatus = col.text();
    encryptionstatus = encryptionstatus == '1' ? i18n.t('disk_report.encrypted') :
    (encryptionstatus === '0' ? i18n.t('disk_report.not_encrypted') : '')
    col.text(encryptionstatus)
}

// Filters
var freeSpaceFilter = function(colNumber, d){
    
    // Look for 'between' statement todo: make generic
    if(d.search.value.match(/^\d+GB freespace \d+GB$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/(\d+GB) freespace (\d+GB)/, function(m, from, to){return ' BETWEEN ' + humansizeToBytes(from) + ' AND ' + humansizeToBytes(to)});
        // Clear global search
        d.search.value = '';
    }

    // Look for a bigger/smaller/equal statement
    if(d.search.value.match(/^freespace [<>=] \d+GB$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = d.search.value.replace(/.*([<>=] )(\d+GB)$/, function(m, o, content){return o + humansizeToBytes(content)});
        // Clear global search
        d.search.value = '';
    }
}

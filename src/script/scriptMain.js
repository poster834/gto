function showArchiveFailures(uid,count,pageNumber)
{
    if ( $('#archiveFailures'+uid).hasClass('visible')) {
        $('#archiveFailures'+uid).removeClass('visible');    
        $('#archiveFailures'+uid).addClass('noVisible');
        $('#btnTxt'+uid).html('<i class="fa fa-caret-square-o-up" aria-hidden="true"></i> Показать архив поломок ('+count+')');
        $('#btnTxt'+uid).removeClass('btn-secondary');
        $('#btnTxt'+uid).addClass('btn-warning');
    } 
    else {
        $('#archiveFailures'+uid).addClass('visible');
        $('#archiveFailures'+uid).removeClass('noVisible');    
        $('#btnTxt'+uid).html('<i class="fa fa-window-close-o" aria-hidden="true"></i> Скрыть архив поломок ('+count+')');
        $('#btnTxt'+uid).removeClass('btn-warning');
        $('#btnTxt'+uid).addClass('btn-secondary');
        

    }

    // if ($('#archiveFailures').hasClass('visible')) {
    //     $('#archiveFailures').addClass('noVisible');
    //     $('#archiveFailures').removeClass('visible');
    //     $(['#btnTxt']).html('Скрыть архив поломок ('+count+')');
    // }else {
    //     $('#archiveFailures').removeClass('noVisible');
    //     $('#archiveFailures').addClass('visible');
    //     $(['#btnTxt']).html('Показать- архив поломок ('+count+')');
    // }
    showDoneFailures(uid,pageNumber);
}

function showDoneFailures(uid,pageNumber)
{
    $.ajax({  
        method: "POST",  
        url:"system/showFailures/"+uid+"/"+pageNumber,
        success: function(htmlData) {
            $('#archiveFailures'+uid).html(htmlData);
        }                   
    });
}

function showSolvBlock(id)
{
    // $('.solvTask').hide();
    $('#solvTask'+id).show();
    $('#resultTask'+id).html('');
    showFailuresPhoto(id);
    $('#failureBtnBlock'+id).show();
    $('#btnDetailedShow'+id).hide();
    $('#btnDetailedHide'+id).show();
    $('#uploadPhotoForm'+id).show();

}

function hideSolvBlock(id, uid)
{
    $('#btnDetailedShow'+id).show();
    $('#btnDetailedHide'+id).hide();
    $('#failureBtnBlock'+id).hide();
    $('#solvTask'+id).hide();
    $('#resultTask'+id).html('');
    $('#uploadPhotoForm'+id).hide();
}

function deleteTask(id,uid)
{
    sendData='';
    $.ajax({  
        method: "POST",  
        data: sendData,
        url:"system/deleteTask/"+id,
        success: function(htmlData) {
            showMachine('m_'+uid);
        }                   
    });
    
    showMachine('m_'+uid);
}


function saveTask(id,uid)
{
    solvUser = $('#solvUser'+id).val();
    comment = $('#comment'+id).val();
    let sendData = "id="+id+"&solvUser="+solvUser+"&comment="+comment;

    $.ajax({  
        method: "POST",  
        data: sendData,
        url:"system/saveTask/"+id,
        success: function(htmlData) {
            $('#resultTask'+id).html(htmlData);

            if ( $('#resultTask'+id).html()=='') {
                showMachine('m_'+uid);
            } else {

            }
        }                   
    });
    
}

function onSelectFailuresPhoto(id,uid)
{
    $('#resultTask'+id).html('');
    $('#uploadPhotoForm'+id).ajaxSubmit({
        type: 'POST',
        url: 'system/newFailuresPhoto/'+id,
        success: function(htmlData) {
            if (htmlData.includes('Ошибка:')) {
                $('#resultTask'+id).html(htmlData);
            } else {
                $('#photoFiles'+id).html(htmlData);
                showFailuresPhoto(id);
            }
            
        }
    });
}

function showFailuresPhoto(id)
{
    $.ajax({  
        method: "POST",  
        url:"system/failures/showPhoto/"+id,
        success: function(htmlData) {
            $('#photoFiles'+id).html(htmlData);
        }                   
    });
}

function onChangeSolvUser(id)
{
    $('#resultTask'+id).html('');
}

function deletePhoto(photoId, type){
    _URL = 'system/'+type+'/delete/'+photoId;
    $.ajax({  
        method: "POST",  
        url: _URL,
        success: function(htmlData) {
            $('#photoId'+photoId).html('');
            // console.log(htmlData);
        }                   
    });
    
}

function addFailures(uid)
{    
    $.ajax({  
        method: "POST",  
        url: 'system/addFailures/'+uid,
        success: function(htmlData) {
           $('#addFailures').html(htmlData);
        }                   
    });
}

function showAddFailureForm(uid, failId)
{
    // console.log(uid, failId);
    $.ajax({  
        method: "POST",  
        url: 'system/showAddFailureForm/'+uid+'/'+failId,
        success: function(htmlData) {
           $('#addFailuresForm').html(htmlData);
        }                   
    });
    $('.failBtn').css('background-color','');
    $('#failBtn'+failId).css('background-color','#640707');
    $('#selectFailureBtn').hide();
}

function changeMechanic(uid)
{
    newMechanic = $('#fixedMechanic'+uid).val();
    $.ajax({  
        method: "POST",  
        url: 'system/changeMechanic/'+uid+'/'+newMechanic,
        success: function(htmlData) {
            console.log(htmlData);
        }                   
    });
    showMachine('m_'+uid);
}

function changeRegion(uid)
{
    newRegion = $('#fixedRegion'+uid).val();
    $.ajax({  
        method: "POST",  
        url: 'system/changeRegion/'+uid+'/'+newRegion,
        success: function(htmlData) {
            console.log(htmlData);
        }                   
    });
    showMachine('m_'+uid);
}


function saveFailure(uid, failuresTypeId, mechanicId, regionId)
{
    description = $('#description'+uid).val();
    if (description.length < 1) {
        description = '-';    
    }
    
    $.ajax({  
        method: "POST",  
        url: 'system/saveFailure/'+uid+'/'+failuresTypeId+'/'+mechanicId+'/'+regionId+'/'+description,
        success: function(htmlData) {
            showMachine('m_'+uid);
        },
    });
    showMachine('m_'+uid);
}

function showProperties(uid)
{
    if ( $('#properties'+uid).hasClass('showProp')) {
        $('#properties'+uid).removeClass('showProp');    
        $('#properties'+uid).addClass('hideProp');
        $('#btn'+uid).html('<i class="fa fa-address-card" aria-hidden="true"></i> Показать свойства');
        $('#btn'+uid).removeClass('btn-secondary');
        $('#btn'+uid).addClass('btn-info');
    } else {
        $('#properties'+uid).addClass('showProp');
        $('#properties'+uid).removeClass('hideProp');    
        $('#btn'+uid).html('<i class="fa fa-window-close-o" aria-hidden="true"></i> Скрыть свойства');
        $('#btn'+uid).removeClass('btn-info');
        $('#btn'+uid).addClass('btn-secondary');
    }

}

function selectDirection(id)
{
    $.ajax({  
        method: "POST",  
        url: 'system/selectDirection/'+id,
        success: function(htmlData) {
            $('#rightCol').html(htmlData);
            $('.subMenu li').removeClass('activePoint');
            $('#direction'+id).addClass('activePoint');
        }                   
    });
}

function selectFailuresType(id, pageNumber)
{
    $.ajax({  
        method: "POST",  
        url: 'system/selectFailuresType/'+id+'/'+pageNumber,
        success: function(htmlData) {
            $('#rightCol').html(htmlData);
            $('.subMenu li').removeClass('activePoint');
            $('#failuresType'+id).addClass('activePoint');
        }                   
    });
}



function showRegionInfo(id, directionId, pageNumber)
{
    if ($('#regionBtn'+id).hasClass('activeBtn')) {
        $('#regionInfo').html('');
        $('.regionBtn').removeClass('activeBtn');
        selectDirection(directionId);
    } else {
        $.ajax({  
            method: "POST",  
            url: 'system/showRegionInfo/'+id+'/'+pageNumber,
            success: function(htmlData) {
                $('#regionInfo').html(htmlData);
                $('.regionBtn').removeClass('activeBtn');
                $('#regionBtn'+id).addClass('activeBtn');            
            }                   
        });
    }
}


function showRegionInfoPage(id, pageNumber)
{
    $.ajax({  
        method: "POST",  
        url: 'system/showRegionInfo/'+id+'/'+pageNumber,
        success: function(htmlData) {
            $('#regionInfo').html(htmlData);          
        }                   
    });
}

function sendFailuresAlerts(directionId)
{
    $.ajax({  
        method: "POST",  
        url: 'system/sendFailuresAlerts/'+directionId,
        success: function(htmlData) {
            $('#sendResult').html(htmlData);
        }                   
    });
}

function inParking(uid)
{
   
    $.ajax({  
        method: "POST",  
        url: 'system/inParking/'+uid,
        success: function(htmlData) {
            showMachine('m_'+uid);
            // $('#parkingReasonBtn'+uid).removeClass('noVisible');
        }                   
    });
}


function outParking(uid)
{
    $.ajax({  
        method: "POST",  
        url: 'system/outParking/'+uid,
        success: function(htmlData) {
            showMachine('m_'+uid);
            // $('#parkingReasonBtn'+uid).addClass('noVisible');
        }                   
    });
    setParkingReasonBtn(uid, null);
}

function setParkingReasonBtn(uid, reason)
{
    //временное отсутсвие водителя *temporary_driver
    //консервация *conservation
    //ремонт *repair
    //резерв *reserve
    // console.log(uid);
    $.ajax({  
        method: "POST",  
        url: 'system/setParkingReason/'+uid+'/'+reason,
        success: function(htmlData) {
            // $('#'+reason).attr('checked');
        }                   
    });
}

function setParkingComment(uid)
{
    commentTxt=$('#comment'+uid).val();
    // console.log(comment);
    $.ajax({  
        method: "POST",  
        url: 'system/setParkingComment/'+uid+'/'+commentTxt,
        success: function(htmlData) {
            $('#comment'+uid).addClass('saved');
            setTimeout(() =>  $('#comment'+uid).removeClass('saved'), 1000);
        }                   
    });
}


function saveDescription(id)
{
    txt = $('#description'+id).val();
    $.ajax({  
        method: "POST",  
        url: 'system/saveDescription/'+id+'/'+txt,
        success: function(htmlData) {
            $('#description'+id).addClass('saved');
            setTimeout(() =>  $('#description'+id).removeClass('saved'), 1000);
        }                   
    });
}

function openMachine(uid)
{
    pageNumber = "";
    $.ajax({  
        method: "POST",  
        url: "schemaPage/"+pageNumber,
        success: function(html){
            // showMachine('m_'+uid);
        }                   
    });

    // showBlock('schemaPage');
    // $('#search').val("'"+uid+"'");
    
}

function getOldRecord()
{
    pageNumber = "";
    $.ajax({  
        method: "POST",  
        url: "getOldRecord/",
        success: function(html){

        }                   
    });
}

function showTrackAnalysis(uid)
{
    if ($('#trackAnalysis'+uid).hasClass('noVisible')) {
        $('#trackAnalysis'+uid).addClass('visible').removeClass('noVisible');
        $('#track_anal_btn'+uid).html('<i class="fa fa-bar-chart" aria-hidden="true"></i> Скрыть анализ');
        $('#track_anal_btn'+uid).addClass('btn-secondary').removeClass('btn-danger');
    } else {
        $('#trackAnalysis'+uid).addClass('noVisible').removeClass('visible');
        $('#track_anal_btn'+uid).html('<i class="fa fa-bar-chart" aria-hidden="true"></i> Анализ треков');
        $('#track_anal_btn'+uid).addClass('btn-danger').removeClass('btn-secondary');
    }
}

function getStops(uid)
{
    dateBegin = $('#dateBegin'+uid).val();
    dateEnd = $('#dateEnd'+uid).val();

    timeBegin = $('#timeBegin'+uid).val();
    timeEnd = $('#timeEnd'+uid).val();

    glonassSerial = $('#glonass_serial'+uid).html() * 1;
    $('#resultGetStop').html('<i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Загрузка и анализ полученных данных.');

    $.ajax({  
        method: "POST",  
        url: "getStops/"+glonassSerial+'/'+dateBegin+'/'+dateEnd+'/'+timeBegin+'/'+timeEnd+'/'+uid,
        success: function(html){
            $('#resultGetStop').html(html);
        }                   
    });
}

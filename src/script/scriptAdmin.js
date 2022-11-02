function showBlock(type,pageNumber)
{
    if ((pageNumber == undefined)) {
        pageNumber = "";
    } else {    }
    $.ajax({  
        method: "POST",  
        url: "admin/"+type+"/"+pageNumber,
        success: function(html){
            $('.subMenu').hide();
            $('.adminMenu li').removeClass('activePoint');
            $('#'+type).addClass('activePoint');
            $('#block').html(html);
            $('.pagePaginator').removeClass('pagePaginator-active');
            $('#pagePaginator'+pageNumber).addClass('pagePaginator-active');
        }                   
    });
}

function showEditRow(type,rowId)
{
    $.ajax({  
        method: "POST",  
        url: type+"/editRow/"+rowId,
        success: function(html){
            $('#result').html(html);
            $('#result').show();
            $('#errors').html('');
        }                   
    });
}

function editActiveRecord(type,id)
{
    $('#'+type+'EditForm').ajaxSubmit({
        type: 'POST',
        url: type+'/edit/'+id,
        success: function(htmlData) {
            pageNumber = htmlData / 1;
            if (isNaN(pageNumber)) {
                $('#errors').html('<span>'+htmlData+'</span>');
            } else {
                showBlock(type,pageNumber);
            }
        }
    });
}

function deleteActiveRecord(type,id)
{
    var del=confirm("Вы уверены что хотите удалить запись "+id+" ?");
    if (del== true){
        $.ajax({  
            method: "POST",  
            url: type+"/delete/"+id,
            success: function(htmlData) {
                pageNumber = htmlData / 1;
                if (isNaN(pageNumber)) {
                    $('#errors').html('<span>'+htmlData+'</span>');
                } else {
                    showBlock(type,pageNumber);
                }
            }                   
        });
    }
}

function showFormAddRow(type)
{
    $.ajax({  
        method: "POST",  
        url: type,
        success: function(htmlData) {
            $('#result').html(htmlData);
            $('#result').show();
            $('#errors').html('');
        }                   
    });
}

function showFormAddRowProp(type,id)
{
    $.ajax({  
        method: "POST",  
        url: type+'/'+id,
        success: function(htmlData) {
            $('#result').html(htmlData);
            $('#result').show();
            $('#errors').html('');
        }                   
    });
}

function cancelForm()
{
    $('#result').hide();
    $('#errors').html('');
}

function save(type)
{
    $('#'+type+'AddForm').ajaxSubmit({
        type: 'POST',
        url: type+'/save',
      success: function(htmlData) {
            pageNumber = htmlData / 1;
            if (isNaN(pageNumber)) {
                $('#errors').html('<span>'+htmlData+'</span>');
            } else {
                showBlock(type,pageNumber);
            }
        }      
    });
}

function saveCompany()
{
    $('#companySaveForm').ajaxSubmit({
        type: 'POST',
        url: 'company/save',
      success: function(htmlData) {
        location.reload(); return false;
        }      
    });

}

function changePassword(userId)
{
    $('#changePassword'+userId).ajaxSubmit({
        type: 'POST',
        url: "users/changePassword/"+userId,
      success: function(htmlData) {
        console.log(htmlData);
        pageNumber = htmlData / 1;
        if (isNaN(pageNumber)) {
            $('#errors').html('<span>'+htmlData+'</span>');
        } else {
            let userName = $('#userRow_'+userId+' :nth-child(2)').html();
            $('#errors').html('<span style="background-color:green;color:#fff;">Пароль пользователя <b>'+userName+'</b> успешно изменен!</span>');
            $('#actionField'+userId).html('');
        }
      }
    });
}

function showChangePasswordForm(userId)
{
    $('#result').html('');
    $('#actionField'+userId).html('<hr><form onkeypress="if(event.keyCode == 13) return false;" id="changePassword'
    +userId+
    '">Смена пароля: <input name="password" type="password"></form><div class="btn btn-success btn-sm" onclick="changePassword('
    +userId+
    ')"><i class="fa fa-save" aria-hidden="true"></i></div><div class="btn btn-danger btn-sm" onclick="closeChangePasswordForm('
    +userId+
    ')"><i class="fa fa-window-close-o" aria-hidden="true"></i></div>');
}

function closeChangePasswordForm(userId) {
    $('#actionField'+userId).html('');
    $('#errors').html('');
}

function changeBlockUser(userId)
{
    $.ajax({  
        method: "POST",  
        url:"users/block/"+userId,
        success: function() {
        }                   
    });    
}

function selectLogo()
{
    $('#companySaveLogoForm').ajaxSubmit({
		type: 'POST',
		url: 'logo/load',
		// target: '#result',
		success: function(htmlData) {
			// После загрузки файла очистим форму.
			$('#companySaveLogoForm')[0].reset();
            showBlock('company');

		}
	});
}

function checkSchemaFile(type)
{
    $('#result').html('');
    if(type == 'web' || type == 'web_geo')
    {
        $('#schemaFileSaveForm')[0].reset();
        $('#result').show();
        $('#result').html('<i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Загрузка с сервера <b>online.tkglonass.ru</b>');
    }
    $('#schemaFileSaveForm').ajaxSubmit({
		type: 'POST',
		url: 'schema/check/'+type,
		success: function(htmlData) {
            $('#result').html(htmlData);
            $('#result').show();
		}
	});
}

function schemaLoad()
{
        $('#schemaFileSaveForm').ajaxSubmit({
            type: 'POST',
            url: 'schema/load',
            success: function(htmlData) {
                $('#result').html(htmlData);
                $('#schemaFileSaveForm')[0].reset();
            }
        });   
}

function geo_schemaLoad()
{
        $('#schemaFileSaveForm').ajaxSubmit({
            type: 'POST',
            url: 'geo_schema/load',
            success: function(htmlData) {
                $('#result').html(htmlData);
                $('#schemaFileSaveForm')[0].reset();
            }
        });   
}


function schemaSave()
{
    $('#result').html('<i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Обновление базы данных');
    $.ajax({  
        method: "POST",  
        url:"schema/updateTableFromFile",
        success: function(htmlData) {
            $('#result').html(htmlData);
            if (htmlData.length < 70) {
                setTimeout(() => {  showBlock('schema'); }, 5000);    
            }
        }                   
    });
}

function geoSchemaSave()
{
    $('#result').html('<i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Обновление базы данных геозон');
    $.ajax({  
        method: "POST",  
        url:"geo_schema/updateTableFromFile",
        success: function(htmlData) {
            $('#result').html(htmlData);
            if (htmlData.length < 70) {
                setTimeout(() => {  showBlock('schema'); }, 5000);    
            }
        }                   
    });
}



function cancelSchema()
{
    showBlock('schema');
}

function deleteLogo()
{
    $.ajax({  
        method: "POST",  
        url:"company/deleteLogo",
        success: function() {
            showBlock('company');
        }                   
    });
}

function setUnUse(id)
{
    $.ajax({  
        method: "POST",  
        url:"addPropertiesTypes/setUnuse/"+id,
        success: function(htmlData) {
            pageNumber = htmlData / 1;
            if (isNaN(pageNumber)) {
                $('#errors').html('<span>'+htmlData+'</span>');
            } else {
                showBlock('propertiesTypes',pageNumber);
            }
        }
    });
}
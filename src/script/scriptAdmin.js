function showBlock(type,pageNumber)
{
    $('#wialonAddToken').html("");
    if ((pageNumber == undefined)) {
        pageNumber = "";
    } else {    }
    $.ajax({  
        method: "POST",  
        url: type+"/"+pageNumber,
        success: function(html){
            $('.subMenu').hide();
            $('.adminMenu li').removeClass('activePoint');
            $('.mainMenu li').removeClass('activePoint');
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
        // console.log(htmlData);
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

function checkSchemaFile(source,schemaType)
{
    $('#result').html('');
    if(source == 'file')
    {       
        $('#schemaFileSaveForm').ajaxSubmit({
            type: 'POST',
            url: 'schema/check/'+source,
            success: function(htmlData) {
                $('#result').html(htmlData);
                $('#result').show();
            }
        });

    } else if (source == 'web') {
        // $('#schemaFileSaveForm')[0].reset();
        $('#result').show();
        serverUrl = $('#serverUrl');
        // serverLogin = $('#serverLogin');
        // serverPassword = $('#serverPassword');

        // if (serverUrl.val() == '') {
        //     serverUrl.css('background-color', 'red');
        // }
        // if (serverLogin.val() == '') {
        //     serverLogin.css('background-color', 'red');
        // }
        // if (serverPassword.val() == '') {
        //     serverPassword.css('background-color', 'red');
        // }
        // if (serverUrl.val()=='' ||serverLogin.val()==''||serverPassword.val()=='') {
        //      $('#errors').html('<span style="color:red;"><b>Заполните необходимые поля</b>');
        //     setTimeout(() => { $('#errors').html('');  }, 5000);    
        // } else {
            $('#result').html('<hr><i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Загрузка с сервера <b>https://'+serverUrl.html()+'</b>');
            
            $.ajax({  
                type: 'POST',
                url: 'schema/check/'+source+'/'+schemaType,
                success: function(htmlData) {
                    $('#result').html(htmlData);
                    $('#result').show();
                }                  
            });
    }
}

function showBtnSchemaLoad(type)
{
    let idSchema = $('#schemaSelect').val();
    let btn='';
    if (type=='geo') {
        btn = "<button class='btn btn-primary' id='btnSchemaLoad' name='btnSchemaLoad' onclick='loadSchemaWeb(\""+idSchema+"\",\"geo\")'>Загрузить схему Геозон</button>";
    }
    if (type=='machine') {
        btn = "<button class='btn btn-primary' id='btnSchemaLoad' name='btnSchemaLoad' onclick='loadSchemaWeb(\""+idSchema+"\",\"machine\")'>Загрузить схему Машин</button>";    
    }
    
    $('#btnSchemaLoad').html(btn);
}

function loadSchemaWeb(schema, type)
{
    serverUrl = $('#serverUrl');
    $('#result').html('<hr><i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Проверка схемы </b>');
    
    if (type=='geo') {
        $.ajax({  
            method: "POST",  
            url:"geoSchema/web/load/"+schema,
            success: function(htmlData) {
                $('#result').html(htmlData);
                $('#result').show();
            }                   
        }); 
        
    }
    if (type=='machine') {
        $.ajax({  
            method: "POST",  
            url:"schema/web/load/"+schema,
            success: function(htmlData) {
                $('#result').html(htmlData);
                $('#result').show();
            }                   
        }); 
    }


}

function schemaLoad()
{
    $.ajax({  
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
    $.ajax({  
        type: 'POST',
        url: 'geo_schema/load',
        success: function(htmlData) {
            $('#result').html(htmlData);
            // $('#schemaFileSaveForm')[0].reset();
        }
    });   
}


function schemaSave()
{
    $('#result').html('<hr><i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Обновление базы данных. <hr>Пожалуйста дождитесь автоматической перезагрузки страницы.');
    $.ajax({  
        method: "POST",  
        url:"schema/updateTableFromFile",
        success: function(htmlData) {
            $('#result').html(htmlData);
            if (htmlData.length < 70) {
                // location.reload();
                showBlock('schema');
                // alert("Схема успешно обновлена!");

            }
        }                   
    });
}

function geoSchemaSave()
{
    $('#result').html('<hr><i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Обновление базы данных. <hr>Пожалуйста дождитесь автоматической перезагрузки страницы.');
    $.ajax({  
        method: "POST",  
        url:"geo_schema/updateTableFromFile",
        success: function(htmlData) {
            $('#result').html(htmlData);
            if (htmlData.length < 70) {
                showBlock('geoSchema');
            }
        }                   
    });
}



function cancelSchema(type)
{
    showBlock(type);
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

function showGroup(elemId,thisElem)
{
    let open = ($([thisElem]).hasClass('open'));
    if (!open) {
        expandGroup(elemId,thisElem);
    } else if (open) {
        collapsGroup(elemId,thisElem);
    } 
}

function expandGroup(elemId,thisElem)
{
    $([thisElem]).addClass('open');
    $([thisElem]).addClass('openGroup');
    $('[id=pm_'+elemId.substr(2)+']').html('<i class="fa fa-minus" aria-hidden="true"></i>');
    let elems = $('[parentguid='+elemId+']');
    elems.each(function(i, elem){
        $([elem]).addClass('visible');
        $([elem]).removeClass('noVisible');
    });
}

function collapsGroup(elemId,thisElem)
{
    $([thisElem]).removeClass('open');
    $([thisElem]).removeClass('openGroup');
    $('[id=pm_'+elemId.substr(2)+']').html('<i class="fa fa-plus-square" aria-hidden="true"></i>');

    let elems = $('[parentguid='+elemId+']');

    elems.each(function(i, elem){
        $([elem]).removeClass('visible');
        $([elem]).addClass('noVisible');
        $([elem]).removeClass('openGroup');
        if ($([elem]).hasClass('menu_G_L')) {
            collapsGroup($([elem]).attr('id'), $([elem]));
            $([elem]).removeClass('open');
            $([elem]).removeClass('openGroup');
        }
        $([elem]).removeClass('menuMachineActive');
    });
    $('[group='+elemId+']').html(''); //убираем данные о машине если свернута группа в которой машина состоит или родительская группа
}

function clearSearch()//работает только в режиме пользователя
{
    $('#cartMachine').html('');
    $('#search').val('');
    root = document.querySelectorAll('#menu > span:first-child');
    rootId = document.querySelectorAll('#menu > span:first-child')[0].id;
    $('#'+rootId).removeClass('open');
    collapsGroup(rootId, root);
    $.each($(".menuMachine"), function() {
        $(this).addClass('noVisible');
        $(this).removeClass('visible');
    });
}
function HC_clearSearch()
{
    $('#cartMachine').html('');
    $('#search').val('');
    $.each($(".hCar"), function() {
        $(this).addClass('noVisible');
        $(this).removeClass('visible');
        $(this).removeClass('hCarSelect');
    });
    showBlock('hiredCar',1);
}

function showMachine(this_id)
{
    $.each($(".menuMachine"), function() {
        $(this).removeClass('menuMachineActive');
    });
    $('#'+this_id).addClass('menuMachineActive');
    getMachineProp(this_id);
    
    parentId = $('#'+this_id).attr('parentguid');
    $('#cartMachine').attr('group', parentId);
   
}

function getMachineProp(this_id)
{
    $.ajax({  
        method: "POST",  
        url:"getMachineInfo/"+ this_id.substr(2),
        success: function(html) {
            $('#cartMachine').html(html); //в какой id отрендерить шаблон
        }                   
    });
}

function changeActiveClass(this_id)
{
    let _this = document.getElementById(this_id); //вызываемый элемент

    if($([_this]).hasClass('activeForUser')){
        changeActivatorGroup(this_id,'deactivate');

    } else if($([_this]).hasClass('notActiveForUser')){
        changeActivatorGroup(this_id,'activate');
    }

    if ($('#'+this_id).hasClass('menu_G_L_0')) {
        alert('Ошибка: Нельзя заблокировать основную группу !!!');
    }
}

function changeActivatorGroup(thisId, setStatus)
{
    parentsId = getParentsId(thisId);
    childrensId = getAllChildrens([thisId]);

    if (setStatus == 'activate') {
        $('#'+thisId).addClass('activeForUser');
        $('#'+thisId).removeClass('notActiveForUser');
        blockGroup(thisId);
        childrensId.forEach(element =>{
            $('#'+element).addClass('activeForUser');
            $('#'+element).removeClass('notActiveForUser');
            blockGroup(element);
        });
        parentsId.forEach(element => {
                $('#'+element).addClass('activeForUser');
                $('#'+element).removeClass('notActiveForUser');
                blockGroup(element);
        });

    } else if(setStatus == 'deactivate'){    
        $('#'+thisId).addClass('notActiveForUser');
        $('#'+thisId).removeClass('activeForUser');
        blockGroup(thisId);
        childrensId.forEach(element =>{
            $('#'+element).addClass('notActiveForUser');
            $('#'+element).removeClass('activeForUser');
            blockGroup(element);
        });

    }

    parentsId.forEach(element => {
        allActiveChildrenArr = getActiveChildren(element);
        allDeactiveChildrenArr = getDeactiveChildren(element);
        allChildrenArr = getChildren(element);
        if (allChildrenArr.length == allDeactiveChildrenArr.length) {
            $('#'+element).addClass('notActiveForUser');
            $('#'+element).removeClass('activeForUser');
            blockGroup(element);
        }
    });
}

function getActiveChildren(thisId)
{
    let activeChildren = document.querySelectorAll("[parentguid = "+thisId+"][class ~= 'activeForUser']");
    ch=[];
    activeChildren.forEach(element => {
        ch.push(element.id);
    });
    return ch;
}

function getDeactiveChildren(thisId)
{
    let notActiveChildren = document.querySelectorAll("[parentguid = "+thisId+"][class ~= 'notActiveForUser']");
    ch=[];
    notActiveChildren.forEach(element => {
        ch.push(element.id);
    });
    return ch;
}

function getChildren(thisId)
{
    let allChildren = document.querySelectorAll("[parentguid = "+thisId+"]");
    ch=[];
    allChildren.forEach(element => {
        ch.push(element.id);
    });
    return ch;
}

function getParentsId(id, arrayParentsId=[])
{
    arrayParentsId.push($('#'+id).attr('parentguid'))
    for (let l = $('#'+id).attr('level'); l > 1; l--) {
        id = $('#'+id).attr('parentguid');
        arrayParentsId.push($('#'+id).attr('parentguid'));
    }
    let l = arrayParentsId.length;
    arrayParentsId = arrayParentsId.filter((n) => {return n != arrayParentsId[l-1]});

    return arrayParentsId;
}

function getAllChildrens(chArr, arrayChildren)
{       
    if (typeof arrayChildren === 'undefined') {
        arrayChildren = [];
    }
    let allCh = [];
    chArr.forEach(elem => {
        ch = [];
        let arrayChildrens = document.querySelectorAll("[parentguid = "+elem+"]");
                arrayChildrens.forEach(element => {
                    ch.push(element.id);
                });
                allCh.push(...ch);
    });
    if (allCh.length > 0) {
        arrayChildren.push(...allCh);
        getAllChildrens(allCh, arrayChildren);
    }
    return arrayChildren;
}

function blockGroup(this_id) {

    self_id = this_id.substr(2);
    if ($('#'+this_id).hasClass('notActiveForUser')) {
        actStatus = '1';
    } 
    if ($('#'+this_id).hasClass('activeForUser')) {
        actStatus = '0';
    } 
    $.ajax({  
        method: "POST",  
        url:"activateGroup/"+actStatus+"/"+self_id,
        success: function(htmlData) {
            // console.log(htmlData);
        }
    });
}

function upDateToken(id,mypage)
{
    WTokenLogin = $('#WTokenLogin'+id).html();
    WTokenUrl = $('#WTokenUrl'+id).html();
    $('#result').show();
    $('#result').html('<i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Запрос авторизации на сервер '+WTokenUrl+'</b>');
    redirectUrlUrl = 'http://'+document.location.host + document.location.pathname+'?id='+id+'_'+mypage;
    window.location.href = 'https://'+WTokenUrl+'/login.html?&user='+WTokenLogin+'&redirect_uri='+redirectUrlUrl;

}

function saveToken(id,token,page)
{
    $.ajax({  
        method: "POST",  
        url:"saveToken/"+id+'/'+token,
        success: function(htmlData) {
            // console.log(htmlData);
            showBlock('wialonAccounts',page); // открываем блок "Аккаунты Wialon"
        }
    });
 
}

function updateMachineList(id)
{            
    WTokenUrl = $('#WTokenUrl'+id).html();
    $('#result').show();
    $('#result').html('<i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Загрузка списка машин с сервера '+WTokenUrl+'</b>');
    WToken = $('#WToken'+id).val();
    $.ajax({  
        method: "POST",  
        url:"updateMachineList/"+id+'/'+WToken,
        success: function(htmlData) {
            $('#result').show();
            $('#result').html(htmlData);
        }
    });
    
}

function selectHCar(carId)
{
    $('.hCar').removeClass('hCarSelect');
    $('#hCar'+carId).addClass('hCarSelect');
    showHCar(carId);
}

function showHCar(carId)
{
    $.ajax({  
        method: "POST",  
        url:"showHCarInfo/"+carId,
        success: function(htmlData) {
            $('#hCarInfo').html(htmlData);
        }
    });
}

function showParamHiredCarOffenses(str)
{
    arr = str.split('_');
    type = arr[0];
    id = arr[1];
    
    $('#HC_offensesAdd').addClass('visible');
    $('#HC_offensesAdd').removeClass('noVisible');
}

function addOffensesHiredCar()
{
    type = $(':checked').attr('typeOver');
    id = ($(':checked').attr('id').split('_'))[1];
    quantity = $('#quantity').val();
    if (quantity =='') {
        $('#quantity').css('background-color','#ffcdcd');
        setTimeout(() => {  $('#quantity').css('background-color','#fff'); }, 500);
    }
    // console.log(id);
    $.ajax({  
        method: "POST",  
        url:"addOffensesHiredCar/"+type+"/"+id+"/"+quantity,
        success: function(htmlData) {
            $('#status'+id).html(htmlData);
            $('#HC_offensesAdd').hide();
            setTimeout(() => {  selectHCar(id); }, 3000);
        }
    });
}

function getAgToken()
{
    $('#agServerForm').ajaxSubmit({
        type: 'POST',
        url: 'getAgToken/',
        success: function(htmlData) {
            showBlock('company');
            // console.log(htmlData);
        }
    });  
    
    // let url = $('#serverUrl').val();
    // let login = $('#serverLogin').val();
    // let password = $('#serverPassword').val();

}

function selectKeyGeoGroup(groupType)
{
let value = $('#'+groupType).val();
arr = value.split('_');
uid = arr[1];

    $.ajax({  
        method: "POST",  
        url:"selectGroup/"+groupType+'/'+uid,
        success: function(htmlData) {
            showBlock('geoSchema');
            console.log(htmlData);
        }
    });
}

function updateGeoCoords()
{
    $('#resultCoords').html('<i class="fa fa-spinner fa-2x fa-spin" aria-hidden="true"></i> - Загрузка координат с сервера и запись в базу');
    $.ajax({  
        method: "POST",  
        url:"updateGeoCoords/",
        success: function(htmlData) {
            $('#resultCoords').html(htmlData);
        }
    });
}


function inPolygon(x,y)
{
    var xp = new Array(54.6072012,54.6075002,54.6075749,54.6080031,54.6082164,54.6083017,54.6081758,54.6080808,54.6069793,54.6069028,54.6060831,54.6064269,54.6067747); // Массив X-координат полигона
    var yp = new Array(46.1009556,46.1010844,46.1020285,46.102442,46.1028975,46.1034702,46.1037385,46.1041825,46.1036735,46.1041111,46.1013524,46.1016384,46.101745); // Массив Y-координат полигона
    function inPoly(x,y){
      var npol = xp.length;
      var j = npol - 1;
      var c = false;
      for (var i = 0; i < npol;i++){
          if ((((yp[i]<=y) && (y<yp[j])) || ((yp[j]<=y) && (y<yp[i]))) &&
          (x > (xp[j] - xp[i]) * (y - yp[i]) / (yp[j] - yp[i]) + xp[i])) {
           c = !c
           }
           j = i;
      }
    return c;
    }
    return inPoly(x,y);
}
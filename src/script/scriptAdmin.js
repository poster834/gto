function showBlock(id,page)
{
    if ((page == undefined)) {
        page = "";
    } else {}
    $.ajax({  
        method: "POST",  
        url: "admin/"+id+"/"+page,
        success: function(html){
            $('.adminMenu li').removeClass('activePoint');
            $('#'+id).addClass('activePoint');
            $('#block').html(html);
            $('.pagePaginator').removeClass('pagePaginator-active');
            $('#pagePaginator'+page).addClass('pagePaginator-active');
        }                   
    });
}

function showEditRow(id)
{
    $.ajax({  
        method: "POST",  
        url: "roles/editRow/"+id,
        success: function(html){
            $('#result').html(html);
            $('#addRole').hide();
        }                   
    });
}

function editActiveRecord(type,id)
{
    $('#'+type+'EditForm').ajaxSubmit({
          type: 'POST',
          url: type+'/edit/'+id,
        //   target: '',
          success: function(data) {
            page = data/1;
            showBlock(type,page);
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
            success: function(data){
                page = data/1;
                showBlock(type,page);
            }                   
        });
    }
}

function showFormAddRow(type)
{
    $('#result').html('');
    $('#addRole').show();
}

function save(type)
{
    $('#'+type+'AddForm').ajaxSubmit({
        type: 'POST',
        url: type+'/save',
      //   target: '',
        success: function(html) {
            $('#block').html(html);
        }
      });
}
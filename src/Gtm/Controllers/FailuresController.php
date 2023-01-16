<?php
namespace Gtm\Controllers;

use Gtm\Models\Roles\Role;
use Gtm\Models\Regions\Region;
use Gtm\Exceptions\NotFoundException;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Exceptions\NotAllowException;
use Gtm\Models\Directions\Direction;
use Gtm\Models\Failures\Failure;
use Gtm\Models\FailuresPhoto\FailuresPhoto;
use Gtm\Models\FailuresTypes\FailuresType;
use Gtm\Models\Machines\Machine;
use Gtm\Models\MachinesFixed\MachineFixed;
use Gtm\Models\Properties\Properties;
use Gtm\Models\Users\UsersAuthService;
use Gtm\Models\Users\User;

ini_set('display_errors',1);
error_reporting(E_ALL);

class FailuresController extends AbstractController
{
    public function show($uid,$pageNumb)
    {
        $pages = Failure::getCountPaginatorPagesDoneFailuresByUid($uid);
        $failureByPage = Failure::findDoneFailuresPaginatorByUid($uid, $pageNumb);
        $failuresPhoto=[];
        foreach($failureByPage as $failure)
        {
            $failuresPhoto[$failure['id']] = FailuresPhoto::getPhotoByFailuresId($failure['id']);
        }
        $this->view->renderHtml('main/blocks/failures.php', [
            'doneFailures' => $failureByPage,
            'pages' => $pages,
            'uid' => $uid,
            'activePage'=>$pageNumb,
            'failuresPhoto'=>$failuresPhoto,
        ]);
    }

    public function saveTask($id)
    {
        $id = (int)$id;
        $userId = $_POST['solvUser'];
        $comment = $_POST['comment'];
        $result = '';
        if ($userId == 'null') {
            $result = "<b>Выберете 'Кто устранил неисправность'</b>";
        } else {
            $failure = Failure::getById($id);
            $failure->setUserService($userId);
            $failure->setServiceText($comment);
            $failure->setDateService(date('Y-m-d'));
            $failure->save();
            $result = "";
        }

        echo $result;
    }

    public function deleteTask($id)
    {
        $id = (int)$id;
            $failure = Failure::getById($id);            
            FailuresPhoto::deleteByFailuresId($id);
            $failure->delete();
    }

    public function addPhoto($id)
    {
        $path = $_SERVER["DOCUMENT_ROOT"].'/gtm/photo/';
        $file = @$_FILES['photo'.$id];
        $error = '';
        $allow = array('jpg','jpeg','png');
        if (!empty($file)) {
            // Проверим на ошибки загрузки.
            if ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
                $error = 'Ошибка: Файл не загружен. Превышен допустимый размер - 2Mb';
                echo $error;
                @$_FILES['photo'.$id]=null;
                exit();
            } elseif (!empty($file['error']) || empty($file['tmp_name'])) {
                switch (@$file['error']) {
                    case 1:
                    case 2: $error = 'Превышен размер загружаемого файла.'; break;
                    case 3: $error = 'Файл был получен только частично.'; break;
                    case 4: $error = 'Файл не был загружен.'; break;
                    case 6: $error = 'Файл не загружен - отсутствует временная директория.'; break;
                    case 7: $error = 'Не удалось записать файл на диск.'; break;
                    case 8: $error = 'PHP-расширение остановило загрузку файла.'; break;
                    case 9: $error = 'Файл не был загружен - директория не существует.'; break;
                    case 10: $error = 'Превышен максимально допустимый размер файла.'; break;
                    case 11: $error = 'Данный тип файла запрещен.'; break;
                    case 12: $error = 'Ошибка при копировании файла.'; break;
                    default: $error = 'Файл не был загружен - неизвестная ошибка.'; break;
                }
                echo $error;
                @$_FILES['photo'.$id]=null;
                exit();
            } else {
                // Оставляем в имени файла только буквы, цифры и некоторые символы.
                $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
                $name = mb_eregi_replace($pattern, '-', $file['name']);
                $name = mb_ereg_replace('[-]+', '-', $name);
                $parts = pathinfo($name);
                // $name = mt_rand().".".$parts;
                if (empty($name) || empty($parts['extension'])) {
                    $error = 'Не удалось загрузить файл.';
                    echo $error;
                    @$_FILES['photo'.$id]=null;
                    exit();
                } elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
                    $error = 'Недопустимый тип файла';
                    echo $error;
                    @$_FILES['photo'.$id]=null;
                    exit();
                } else {
                    //загружаем файл и записываем информацию в БД
                    $fileUpload = $file['tmp_name'];
                    $name = mt_rand()."(".date('dmy').").".$parts['extension'];
                    move_uploaded_file($fileUpload, $path . $name);

                    $photo = new FailuresPhoto();
                    $photo->setUrl($name);
                    $photo->setFailuresId($id);
                    $photo->save();
                } 
            }
        }
    }

    public function showPhoto($id)
    {   
              
        $failuresPhoto = FailuresPhoto::getPhotoByFailuresId($id);
        $failured = (Failure::getById($id))->getUid();
        $user = UsersAuthService::getUserByToken();
        return $this->view->renderHtml('main/blocks/failuresPhoto.php', [
            'failuresPhoto' => $failuresPhoto,
            'uid' => $failured,
            'user' => $user,

        ]);
    }

    public function deletePhoto($id)
    {
        FailuresPhoto::deletePhoto($id);
    }

    public function showAddFailuresBtn($uid)
    {
        $failuresTypes = FailuresType::findAll();
        $machine = Machine::findOneByColumn('uid',$uid);
        $failures = Failure::getAllMachineFailuresByUid($uid);
        $openFailuresType = []; //массив типов нарушений, которые открыты на данный момент времени чтобы не включать их в показ кнопок добавдения новых нарушений
        foreach ($failures as $failure) {
            if ($failure->getUserService() == NULL) {
                $openFailuresType[] = $failure->getTypeId();
            }
        }
        return $this->view->renderHtml('main/blocks/addFailures.php', [
            'uid' => $uid,
            'failuresTypes' => $failuresTypes,
            'openFailuresType' => $openFailuresType,
            'machine' => $machine,

        ]);
    }

public function showAddFailuresForm($uid, $failId)
{   
    $properties = Properties::getAllByUid($uid);
    $machine = Machine::findOneByColumn('uid',$uid);
    $user = UsersAuthService::getUserByToken();
    $failuresType = FailuresType::getById($failId);
    $fixed = MachineFixed::getFixedInfo($uid);
    $mechanicName = $mechanicPhone = $mechanicEmail = $regionName = $directionName = '';

    if (isset($fixed['user_id']) && ($fixed['user_id'] <> 0 || $fixed['user_id']<>'0')) {
        $mechanic = User::getById($fixed['user_id']);
        $mechanicName = $mechanic->getName();
        $mechanicPhone = $mechanic->getPhone();
        $mechanicEmail = $mechanic->getEmail();
    }
    if (isset($fixed['region_id']) && ($fixed['region_id'] <> 0 || $fixed['region_id']<>'0')) {
        $region = Region::getById($fixed['region_id']);
        $regionName = $region->getName();
        $direction = Direction::getById($region->getDirectionId());
        $directionName = $direction->getName();
    }   

    return $this->view->renderHtml('main/blocks/addFailuresForm.php', [
        'uid' => $uid,
        'failId' => $failId,
        'machine' => $machine,
        'properties' => $properties,
        'user' => $user,
        'failuresType' => $failuresType,
        'mechanicName' => $mechanicName,
        'mechanicPhone' => $mechanicPhone,
        'mechanicEmail' => $mechanicEmail,
        'regionName' => $regionName,
        'directionName' => $directionName,
        'mechanicId' => $fixed['user_id'],
        'regionId' => $fixed['region_id'],

    ]);
}

public function saveFailure($uid, $failuresTypeId, $mechanicId, $regionId, $description)
{
    $user = UsersAuthService::getUserByToken();
    $failure = new Failure();
    $machine = Machine::findOneByColumn('uid', $uid);
    
    $failure->setTypeId($failuresTypeId);
    $failure->setUserId($user->getId());
    $failure->setDateCreate(date('Y-m-d'));
    $failure->setDescription($description);
    $failure->setSerial($machine->getSerial());
    $failure->setUid($uid);
    $failure->setMechanicId($mechanicId);
    $failure->setRegionId($regionId);
    $failure->save();
    
}


public function failuresNotification()
{
    echo "failuresNotification";
}


public function saveDescription($id, $txt)
{
    $failure = Failure::getById($id);
    $failure->setDescription($txt);
    $failure->save();
}


public function selectFailuresType($fTypeId,$page)
{
$failuresByType = Failure::findActiveByTypeId($fTypeId, $page);
$pages = Failure::getActivePaginatorPagesByColumn('type_id', $fTypeId);
    return $this->view->renderHtml('main/blocks/selectFailuresType.php', [
        'fTypeId' => $fTypeId,
        'activeFailures' => $failuresByType,
        'pages' => $pages,
        'activePage'=>$page


    ]);
}

}
<?php
namespace Gtm\Controllers;
use Gtm\Models\Roles\Role;
use Gtm\Models\Users\User;
use Gtm\Models\Companys\Company;
use Gtm\Exceptions\InvalidArgumentException;
use Gtm\Models\Regions\Region;
use Gtm\Models\Directions\Direction;
use Gtm\Models\FailuresTypes\FailuresType;
use Gtm\Models\OffensesTypes\OffensesType;
use Gtm\Models\Machines\Machine;
use Gtm\Models\Groups\Group;
use Gtm\Models\Properties\Properties;
use Gtm\Models\PropertiesTypes\PropertiesType;
use Gtm\Models\Devices\Device;
use Gtm\Models\Schemas\Schema;
use Gtm\Models\WialonAccounts\WialonAccount;

 ini_set('display_errors',1);
 error_reporting(E_ALL);

class AdminController extends AbstractController
{
    public function getAgToken()
    {
        $arr = $_POST;
        $url = $arr['serverUrl'];
        $login = $arr['serverLogin'];
        $password = $arr['serverPassword'];
        $token = file_get_contents('https://'.$url.'/ServiceJSON/Login?UserName='.$login.'&Password='.$password.'&UTCOffset=180');
        $company = Company::getById(1);
        $company->setAgServer($url);
        $company->setAgLogin($login);
        $company->setAgToken($token);
        $company->save();
    }
    
    public function mainAdmin()
    {   
        $this->view->renderHtml('admin/mainAdmin.php',[
                    
        ]);
    }

    // public function getWialonSid($token)
    // {
    //     $json = file_get_contents('https://hst-api.wialon.com/wialon/ajax.html?svc=token/login&params={"token":"'.$token.'","fl":1}');
    //     $jsonArr = json_decode($json, true);
    //     $eid = $jsonArr['eid'];
    //     // var_dump($eid);
    //     $get = 'https://hst-api.wialon.com/wialon/ajax.html?svc=core/search_items&params={"spec":{"itemsType":"avl_unit","propName":"sys_name","propValueMask":"*","sortType":"0","propType":"0","or_logic":""},"force":1,"flags":1,"from":0,"to":0}&sid='.$eid;
    //     // var_dump($get);
    //     $cars = file_get_contents($get);

    //     $carsArr = json_decode($cars, true);
    //     var_dump($carsArr['items']);
    // }

    public function company()
    {
        $this->view->renderHtml('admin/blocks/company.php',[]);
    }

    public function roles($pageNumber)
    {
        $roles = Role::findAllPerPage($pageNumber);
        $pages = Role::getPagesPaginator();
        $this->view->renderHtml('admin/blocks/roles.php',[
            'roles'=>$roles,
            'pages'=>$pages,
        ]);
    }

    public function users($pageNumber)
    {
        $users = User::findAllPerPage($pageNumber);
        $pages = User::getPagesPaginator();
        $roles = Role::findAll();
        $this->view->renderHtml('admin/blocks/users.php',[
            'users'=>$users,
            'pages'=>$pages,
            'roles'=>$roles,
        ]);
    }

    public function directions($pageNumber)
    {
        $directions = Direction::findAllPerPage($pageNumber);
        $pages = Direction::getPagesPaginator();
        $this->view->renderHtml('admin/blocks/directions.php',[
            'directions'=>$directions,
            'pages'=>$pages,
        ]);
    }

    public function propertiesTypes($pageNumber)
    {
        $propertiesTypes = PropertiesType::findAllPerPage($pageNumber);
        $properties = new Properties();
        $propertiesArr = [
            0=>['name'=>'DeviceIMEI', 'count'=>352, 'use'=>false],
            1=>['name'=>'DeviceSIM', 'count'=>350, 'use'=>true],
        ];
        $parametersArray = [
            0=>['name'=>'ID', 'use'=>false],
        ];
        $pages = PropertiesType::getPagesPaginator();
        $this->view->renderHtml('admin/blocks/propertiesTypes.php',[
            'propertiesTypes'=>$propertiesTypes,
            'propertiesArray'=>$propertiesArr,
            'parametersArray'=>$parametersArray,
            'pages'=>$pages,
        ]);
    }

    public function regions($pageNumber)
    {
        $regions = Region::findAllPerPage($pageNumber);
        $pages = Region::getPagesPaginator();
        $this->view->renderHtml('admin/blocks/regions.php',[
            'regions'=>$regions,
            'pages'=>$pages,
        ]);
    }

    public function failuresTypes($pageNumber)
    {
        $failuresTypes = FailuresType::findAllPerPage($pageNumber);
        $pages = FailuresType::getPagesPaginator();
        $this->view->renderHtml('admin/blocks/failuresTypes.php',[
            'failuresTypes'=>$failuresTypes,
            'pages'=>$pages,
        ]);
    }

    public function offensesTypes($pageNumber)
    {
        $offensesTypes = OffensesType::findAllPerPage($pageNumber);
        $pages = OffensesType::getPagesPaginator();
        $this->view->renderHtml('admin/blocks/offensesTypes.php',[
            'offensesTypes'=>$offensesTypes,
            'pages'=>$pages,
        ]);
    }

    public function machines()
    {
        $groupsCount = $machinesCount = 0;
        $machinesCount = Machine::getCountInTable();
        $groupsCount = Group::getCountInTable();
        $this->view->renderHtml('admin/blocks/machines.php',[
            'groupsCount' => $groupsCount,
            'machinesCount' => $machinesCount,
        ]);
    }

    public function schema()
    {
        $company = Company::getById(1);
        $token = $company->getAgToken();
        $login = $company->getAgLogin();
        $server = $company->getAgServer();
        $groupsCount = Group::getCountInTable();
        $groupsTree = Schema::getSchemaTree();
        $machinesCount = Machine::getCountInTable();
        $dateTime = new \DateTime(Company::getById(1)->getDateUpdate());
        $devicesCount = Device::getCountInTable();
        $this->view->renderHtml('admin/blocks/schema.php',[
            'groupsCount' =>$groupsCount,
            'machinesCount'=>$machinesCount,
            'devicesCount'=>$devicesCount,
            'dateTime'=>$dateTime,
            'groupsTree'=>$groupsTree,
            'token'=>$token,
            'login'=>$login,
            'server'=>$server,
        ]);
    }


    public function deleteLogo()
    {
        $company = Company::getById(1);
        $company->setLogo('');
        $company->save();
    }
    
    public function saveCompany()
    {
        $company = Company::getById(1);
        $arr = $_POST;
        if (strlen($_POST['name'])<2) {
            $arr['name'] = 'Наименование компании';
        }
        $company->updateFromArray($arr);
    }

    public function logoLoad()
    { 
        $file = @$_FILES['logo'];
        $error = $success = '';
         
        // Разрешенные расширения файлов.
        $allow = array('jpg', 'jpeg', 'png', 'gif');
         
        // Директория, куда будут загружаться файлы.
        $path = $_SERVER["DOCUMENT_ROOT"].'/gtm/src/templates/img/company/uploads/';

        if (!empty($file)) {
            // Проверим на ошибки загрузки.
            if (!empty($file['error']) || empty($file['tmp_name'])) {
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
            } elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
                $error = 'Не удалось загрузить файл.';
            } else {
                // Оставляем в имени файла только буквы, цифры и некоторые символы.
                $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
                $name = mb_eregi_replace($pattern, '-', $file['name']);
                $name = mb_ereg_replace('[-]+', '-', $name);
         
                $parts = pathinfo($name);
                if (empty($name) || empty($parts['extension'])) {
                    $error = 'Не удалось загрузить файл.';
                } elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
                    $error = 'Недопустимый тип файла';
                } else {
                    // Перемещаем файл в директорию.
                    if (move_uploaded_file($file['tmp_name'], $path . $name)) {
                        // Далее можно сохранить название файла в БД и т.п.
                        $success = 'Файл «' . $name . '» успешно загружен.';
                        $company = Company::getById(1);
                        $company->setLogo($name);
                        $company->save();

                    } else {
                        $error = 'Не удалось загрузить файл.';
                    }
                }
            }
         
            // Выводим сообщение о результате загрузки.
            if (!empty($success)) {
                echo $name;	
            } else {
                echo '<span class="error">' . $error . '</span>';
            }
        }
    }



   
}


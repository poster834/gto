<?php
namespace Gtm\Controllers;

use Gtm\Models\Groups\Group;
use Gtm\Models\Machines\Machine;
use Gtm\Models\Companys\Company;
use Gtm\Models\Properties\Properties;
use Gtm\Models\PropertiesTypes\PropertiesType;
use Gtm\Models\Devices\Device;
use Gtm\Models\Fences\Fence;

ini_set('display_errors',1);
error_reporting(E_ALL);
class SchemasController extends AbstractController
{
    public function schemaLoad()
    {
        $path = $_SERVER["DOCUMENT_ROOT"].'/gtm/src/data/';
        $name = 'schema.json';
        if (@$_FILES['schemaFile'] == null) {
            $moveKey = rename(__DIR__.'/../../data/schema_tmp.json', __DIR__.'/../../data/schema.json');
        } else {
            $file = @$_FILES['schemaFile'];
            $fileUpload = $file['tmp_name'];
            // Перемещаем файл в директорию.
            $moveKey = move_uploaded_file($fileUpload, $path . $name);
        }
        if ($moveKey) {
            // Далее можно сохранить название файла в БД и т.п.
            echo '<hr><span style="background-color:green;color:#fff;">Файл '.$name.' - успешно сохранен.</span><hr>';
            echo '<span class="btn btn-success" onclick="schemaSave()">Обновить список машин</span>';    
        } else {
            $error = '<hr>Не удалось загрузить файл.';
            echo '<span style="background-color:red;color:#fff;">' . $error . '</span>';
        }   
    }

    public function geoSchemaLoad()
    {
        $path = $_SERVER["DOCUMENT_ROOT"].'/gtm/src/data/';
        $name = 'geo_schema.json';
        if (@$_FILES['schemaFile'] == null) {
            $moveKey = rename(__DIR__.'/../../data/geo_schema_tmp.json', __DIR__.'/../../data/geo_schema.json');
        } else {
            $file = @$_FILES['schemaFile'];
            $fileUpload = $file['tmp_name'];
            // Перемещаем файл в директорию.
            $moveKey = move_uploaded_file($fileUpload, $path . $name);
        }
        if ($moveKey) {
            // Далее можно сохранить название файла в БД и т.п.
            echo '<hr><span style="background-color:green;color:#fff;">Файл '.$name.' - успешно сохранен.</span><hr>';
            echo '<span class="btn btn-success" onclick="geoSchemaSave()">Обновить список геозон</span>';    
        } else {
            $error = '<hr>Не удалось загрузить файл.';
            echo '<span style="background-color:red;color:#fff;">' . $error . '</span>';
        }   
    }

    

    public function schemaCheck($src)
    {
        $source = '';
        if ($src == 'file') {
            $source = ' JSON файл схемы';
        }
        if ($src == 'web') {
            $source = 'WEB приложение On-Line';
        }
        if ($src == 'web_geo') {
            $source = 'Обновление Геозон с WEB On-Line';
        }
        echo 'Способ загрузки - '. $source;
        $file = @$_FILES['schemaFile'];
        $error = '';
        $allow = array('json');
        if (!empty($file)) {
            // Проверим на ошибки загрузки.
            if ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
                $error = 'Не удалось загрузить файл.';
                echo '<hr><span style="background-color:red;color:#fff;">' .$error . '</span>';
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
                echo '<hr><span style="background-color:red;color:#fff;">' .$error . '</span>';
                exit();
            } else {
                // Оставляем в имени файла только буквы, цифры и некоторые символы.
                $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
                $name = mb_eregi_replace($pattern, '-', $file['name']);
                $name = mb_ereg_replace('[-]+', '-', $name);
                $parts = pathinfo($name);
                if (empty($name) || empty($parts['extension'])) {
                    $error = 'Не удалось загрузить файл.';
                    echo '<hr><span style="background-color:red;color:#fff;">' . $name.' - '.$error . '</span>';
                    exit();
                } elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
                    $error = 'Недопустимый тип файла';
                    echo '<hr><span style="background-color:red;color:#fff;">' . $parts['extension'].' - '.$error . '</span>';
                    exit();
                } else {
                    if ($src == 'file') {
                        $jsonFile = file_get_contents($file['tmp_name'], true);
                        $obj = json_decode($jsonFile, true);
                        if ($obj == null) {
                            $jsonFile = substr($jsonFile, 3);
                            $obj = json_decode($jsonFile, true);
                        }
                    }
                } 
            }
        } else if ($src == 'web'){
            $data = file_get_contents('https://online.tkglonass.ru/ServiceJSON/EnumDevices?session=5EB5C3D01E7889A10EB2C3459619103262B16A8D8B7DC0B18E7958B3620A6C1F&schemaID=31177dec-ea50-4f56-a1f1-5550b56f8eed', true);
            file_put_contents(__DIR__.'/../../data/schema_tmp.json', $data);
            $obj = json_decode($data, true);
        } else if ($src == 'web_geo'){
            $data_geo = file_get_contents('https://online.tkglonass.ru/ServiceJSON/EnumGeoFences?session=5EB5C3D01E7889A10EB2C3459619103262B16A8D8B7DC0B18E7958B3620A6C1F&schemaID=31177dec-ea50-4f56-a1f1-5550b56f8eed', true);
            file_put_contents(__DIR__.'/../../data/geo_schema_tmp.json', $data_geo);
            $obj_geo = json_decode($data_geo, true);
        }
        $groups = $items = 0;
        $result = '';
        if (isset($obj['Groups'])) {
            $groups = count($obj['Groups']);
            $result .= '<hr>Всего Групп Машин в предложенной схеме: <b>'.$groups."</b><br>";
        }
        if (isset($obj['Items'])) {
            $items = count($obj['Items']);
            $result .= 'Всего Машин в предложенной схеме:  <b>'.$items." </b><hr>";
        }
        if (isset($obj_geo['Items'])) {
            $items_geo = count($obj_geo['Items']);
            $result = 'Всего Геозон в предложенной схеме:  <b>'.$items_geo." </b><hr>";
        }
        echo '<br>'.$result;
   
        if (($items>$groups) && ($groups>0)) {
            echo '<span class="btn btn-success" onclick="schemaLoad()">Сохранить файл схемы</span>   <span class="btn btn-danger" onclick="cancelSchema()">Отмена</span>';   
        } else if($items_geo > 0){
            echo '<span class="btn btn-success" onclick="geo_schemaLoad()">Сохранить файл геозон</span>   <span class="btn btn-danger" onclick="cancelSchema()">Отмена</span>';   
        } else {
            echo '<span style="background-color:red;color:#fff;">Похоже на то, что в файле нет нужных данных. Выберете другой файл</span> <br> <span class="btn btn-danger" onclick="cancelSchema()">Отмена</span>';  
        }
    }

    public function updateTableFromFile()
    {
        $file = null;
        $file = file_get_contents(__DIR__.'/../../data/schema.json', true);
        if ($file == null) {
            echo '<span style="background-color:red;color:#fff;">Похоже на то, что с файлом чтото случилось, его нельзя прочитать. Попробуйте выбрать другой файл. </span>';  
        } else {
            $this->saveSchemaToBase(); 
        }
    }

    public function updateFencesTableFromFile()
    {
        $fileJSON = file_get_contents(__DIR__.'/../../data/geo_schema.json', true);
        $arrays_G_I = json_decode($fileJSON, true);
        $geoGroups = $arrays_G_I['Groups'];
        $fences = $arrays_G_I['Items'];
        Fence::truncateTable();
        Fence::saveToBase($fences);
    }

    private function saveSchemaToBase()
    {
        $fileJSON = file_get_contents(__DIR__.'/../../data/schema.json', true);
        $arrays_G_I = json_decode($fileJSON, true);
        $groups = $arrays_G_I['Groups'];
        $machines = $arrays_G_I['Items'];
        $groupsArray = [];
        $machinesPropertiesArray = [];
        $machinesArray = [];
        $devicesArray = [];
        $basePropertiesArray = ['glonass_serial', 'guid', 'name','image', 'image_colored'];
        $company =  Company::getById(1);
        $rootGuid = '';

        $properties = [];

        if (is_null($groups) || is_null($machines)) {
            $this->view->renderHtml('errors/invalidArgument.php', ['error'=>' Нет данных. Возможно файл содержит BOM символы!']);
            exit();
        } else {
            foreach($groups as $group)
            {
                $groupsArray[$group['ID']] = $group['Name'];
                if ($group['ParentID'] == null) {//вычисляем корневую группу
                    $rootGuid = $group['ID']; 
                }
            }

            //формируем массив свойств каждой машины. Основные свойства добавляем в хвост для исключения перезаписи их не основными свойствами из схемы.
            foreach ($machines as $machine)
            {
                foreach($machine['Properties'] as $properties)
                {
                    if(empty($properties['Value']) || $properties['Value']=='00000000-0000-0000-0000-000000000000'){
                        continue;
                    }elseif (!is_array($properties['Value'])) {
                        $machinesPropertiesArray[$machine['ID']][$properties['Name']] = $properties['Value'];
                    } else {
                        //если встречаем массив (например тарировка) забиваем заглушку. Позже можно разобрать в таблицу тарировок если будет желание
                        $machinesPropertiesArray[$machine['ID']][$properties['Name']] = 'array';
                    }
                }
                if ($machine['Serial'] == 0 || $machine['Serial'] == null || $machine['Serial'] == '') {
                    $serial = '11111';
                } else {
                    $serial = $machine['Serial'];
                }
                $machinesPropertiesArray[$machine['ID']][$basePropertiesArray[0]] = $serial;
                $machinesPropertiesArray[$machine['ID']][$basePropertiesArray[1]] = $machine['ParentID'];
                $machinesPropertiesArray[$machine['ID']][$basePropertiesArray[2]] = $machine['Name'];
                $machinesPropertiesArray[$machine['ID']][$basePropertiesArray[3]] = $machine['Image'];
                $machinesPropertiesArray[$machine['ID']][$basePropertiesArray[4]] = $machine['ImageColored'];                
                
                //формируем массив для добавления машин в базу
                $machinesArray[$machine['ID']]['uid'] = $machine['ID'];
                $machinesArray[$machine['ID']]['guid'] = $machine['ParentID'];

                //формируем массив для добавления устройств в базу. Только реальные приборы
                if ((int)$machine['Serial']>11115) {
                    $devicesArray[$machine['ID']] = $machine['Serial'];
                }
                

            }
        }
        // сортируем вновь созданный массив натуральный сортировкой
        natsort($groupsArray);
        $countRootGr = 0;
        //добавляем в общий массив информацию о конкретной группе 
        foreach($groupsArray as $key=>$value)
        {
            foreach($groups as $group)
            {
                if ($key == $group['ID']) {
                    $groupsArray[$key] = $group;
                    if ($groupsArray[$key]['ParentID'] == null) {
                        $countRootGr ++; // проверяем количество групп с ParentID == null чтобы выбрать только 1 корневую группу
                    }
                }
            } 
        }

        if ($countRootGr > 1) {
            echo '<span style="background-color:red;color:#fff;">Невозможно определить корневую группу. Обратитесь к владельцу загруженной схемы.</span><br> <span class="btn btn-danger" onclick="cancelSchema()">Отмена</span>';
        } else {
             //записываем группы машин в базу
            Group::truncateTable();
            Group::saveToBase($groupsArray);
        }

        //записываем все машины в базу
        Machine::truncateTable();
        Machine::saveToBase($machinesArray);
        
        //записываем доступные свойства машин в базу
        Properties::truncateTable();
        Properties::saveToBase($machinesPropertiesArray, $basePropertiesArray);

        PropertiesType::saveToBase(Properties::findAllWithCount(), $basePropertiesArray);

        //записываем доступные приборы в базу
        Device::truncateTable();
        Device::saveToBase($devicesArray);

        $company = Company::getById(1);
        $company->setRootGuid($rootGuid);
        $today = date('Y-m-d H:i:s');
        $company->setDateUpdate($today);
        $company->save();
        echo "В случае отсутствия ошибок данные обновятся через 5 секунд.<pre>";
        
    }

}
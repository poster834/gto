<?php
namespace Gtm\Controllers;

use Gtm\Models\Groups\Group;
use Gtm\Models\Machines\Machine;
use Gtm\Models\Companys\Company;
use Gtm\Models\Coords\Coord;
use Gtm\Models\Coords\CoordLocality;
use Gtm\Models\Coords\CoordPlant;
use Gtm\Models\Coords\CoordRoad;
use Gtm\Models\Properties\Properties;
use Gtm\Models\PropertiesTypes\PropertiesType;
use Gtm\Models\Devices\Device;
use Gtm\Models\Fences\Fence;
use Gtm\Models\Groups\GeoGroup;
use Gtm\Models\Schemas\GeoSchema;

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
            echo '<hr><span style="background-color:green;color:#fff;">Файл '.$name.' - успешно сохранен.</span>
            
            <hr><span>Подтвердите обновление списка машин из сохраненного файла схемы.</span><br><br>';
            echo '<span class="btn btn-success" onclick="schemaSave()">Обновить список машин</span> <span class="btn btn-danger" onclick="cancelSchema()">Отмена</span>';    
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


    public function schemaCheck($src,$schemaType)
    {
        // $schemaType = ('machine'|'geo');
        $source = '';
        if ($src == 'file') {
            $source = ' JSON файл схемы';
        }
        if ($src == 'web') {
            $source = 'WEB приложение On-Line<br>';
        }
        echo '<hr>Способ загрузки - '. $source;

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
            $token = Company::getById(1)->getAgToken();
            $url = Company::getById(1)->getAgServer();      
            $getUrl = "https://".$url."/ServiceJSON/EnumSchemas?session=".$token."'";
            $schemas = file_get_contents($getUrl);

            $this->view->renderHtml('admin/blocks/schemaSelect.php',[
                'schemas' => $schemas,
                'token' => $token,
                'type' => $schemaType,
            ]);
        }
   
    }

    public function schemaWebLoad($schema)
    {   
        $token = Company::getById(1)->getAgToken();
        $url = Company::getById(1)->getAgServer();
        $data = file_get_contents('https://'.$url.'/ServiceJSON/EnumDevices?session='.$token.'&schemaID='.$schema, true);
        file_put_contents(__DIR__.'/../../data/schema_tmp.json', $data);
        $obj = json_decode($data, true);
        $groups = $items = 0;
        $result = '';
        if (isset($obj['Groups'])) {
            $groups = count($obj['Groups']);
            $result .= '<hr>Всего Групп в предложенной схеме: <b>'.$groups."</b><br>";
        }
        if (isset($obj['Items'])) {
            $items = count($obj['Items']);
            $result .= 'Всего Машин в предложенной схеме:  <b>'.$items." </b><hr>
            Вы можете сохранить файл схемы для дальнейшего обновления базы данных<br><br>";
        }
        echo $result;
   
        if (($items>0) && ($groups>0)) {
            echo '<span class="btn btn-success" onclick="schemaLoad()">Сохранить файл схемы</span>   <span class="btn btn-danger" onclick="cancelSchema(\'schema\')">Отмена</span>';   
        } else {
            echo '<span style="background-color:red;color:#fff;">Похоже на то, что в файле нет нужных данных. Выберете другой файл</span> <br> <span class="btn btn-danger" onclick="cancelSchema(\'schema\')">Отмена</span>';  
        }
    }

    public function geoSchemaWebLoad($schema)
    {   
        $token = Company::getById(1)->getAgToken();
        $url = Company::getById(1)->getAgServer();
        $data = file_get_contents('https://'.$url.'/ServiceJSON/EnumGeoFences?session='.$token.'&schemaID='.$schema, true);
        file_put_contents(__DIR__.'/../../data/geo_schema_tmp.json', $data);
        $obj = json_decode($data, true);
        $groups = $items = 0;
        $result = '';
        if (isset($obj['Groups'])) {
            $groups = count($obj['Groups']);
            $result .= '<hr>Всего Групп в предложенной схеме: <b>'.$groups."</b><br>";
        }
        if (isset($obj['Items'])) {
            $items = count($obj['Items']);
            $result .= 'Всего Геозон в предложенной схеме:  <b>'.$items." </b><hr>
            Вы можете сохранить файл схемы для дальнейшего обновления базы данных<br><br>";
        }
        echo $result;
   
        if (($items>0) && ($groups>0)) {
            echo '<span class="btn btn-success" onclick="geo_schemaLoad()">Сохранить файл схемы</span>   <span class="btn btn-danger" onclick="cancelSchema(\'geoSchema\')">Отмена</span>';   
        } else {
            echo '<span style="background-color:red;color:#fff;">Похоже на то, что в файле нет нужных данных. Выберете другой файл</span> <br> <span class="btn btn-danger" onclick="cancelSchema(\'geoSchema\')">Отмена</span>';  
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
        $root = GeoGroup::getRootGeoGroup();
  
        GeoSchema::setParamByName('schemaName', $root->getName());
        GeoSchema::setParamByName('schemaId', $arrays_G_I['ID']);
        GeoSchema::setParamByName('rootGuid', $root->getUid());
        GeoSchema::setParamByName('upDate', date('Y-m-d H:i:s'));

        GeoGroup::truncateTable();
        GeoGroup::saveToBase($geoGroups);
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
        $basePropertiesArray = ['glonass_serial', 'image', 'image_colored'];
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
                $machinesPropertiesArray[$machine['ID']][$basePropertiesArray[1]] = $machine['Image'];
                $machinesPropertiesArray[$machine['ID']][$basePropertiesArray[2]] = $machine['ImageColored'];                             
                
                //формируем массив для добавления машин в базу
                $machinesArray[$machine['ID']]['uid'] = $machine['ID'];
                $machinesArray[$machine['ID']]['guid'] = $machine['ParentID'];
                $machinesArray[$machine['ID']]['name'] = $machine['Name'];

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
            $blockedGroups =  Group::checkBlockedGroup();
            Group::truncateTable();
            Group::saveToBase($groupsArray, $blockedGroups);
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
        echo "ok";
        
    }

    public function geoSchema()
    {
        $token = Company::getById(1)->getAgToken();
        $url = Company::getById(1)->getAgServer();
        $login = Company::getById(1)->getAgLogin();
        // $geoSchema = GeoSchema::getInstance();
        $geoGroups = GeoGroup::findAll();
        $geoFences = Fence::findAll();
        $rootGroup = GeoGroup::getRootGeoGroup();
        $rootGroupUid = $rootGroup->getUid();
        $allGeoGroups = GeoGroup::findAll();
        $companyName = Company::getById(1)->getName();
        foreach($allGeoGroups as $key => $group)
        {
            if ($group->getUid() == $rootGroupUid) {
                unset($allGeoGroups[$key]);
            }
        }
        $upDate = null;
        $forageUid = GeoSchema::getParamByName('forageUid');
        $plantsUid = GeoSchema::getParamByName('plantsUid');
        $upDate = GeoSchema::getParamByName('upDate');
        $granariesUid = GeoSchema::getParamByName('granariesUid');
        $farmsUid = GeoSchema::getParamByName('farmsUid');
        $roadsUid = GeoSchema::getParamByName('roadsUid');
        $localityUid = GeoSchema::getParamByName('localityUid');        
        $plantsFences = Fence::findAllByColumn('guid', $plantsUid);
        $granariesFences = Fence::findAllByColumn('guid', $granariesUid);

        $this->view->renderHtml('admin/geoSchema.php',[
            'upDate' => $upDate,
            'token' => $token,
            'server' => $url,
            'login' => $login,
            'geoGroups' => $geoGroups,
            'geoFences' => $geoFences,
            'geoGroupAllLevel' => $allGeoGroups,
            'forageUid' => $forageUid,
            'plantsUid' => $plantsUid,
            'plantsFences' => $plantsFences,
            'companyName' => $companyName,
            'granariesUid' => $granariesUid,
            'granariesFences' => $granariesFences,
            'farmsUid' => $farmsUid,
            'roadsUid' => $roadsUid,
            'localityUid' => $localityUid,
        ]);
    }

    public function updateGeoCoords()
    {
        $token = Company::getById(1)->getAgToken();
        $url = Company::getById(1)->getAgServer();

        $geoSchemaParams = GeoSchema::getAllParams();

        $schemaId = GeoSchema::getParamByName('schemaId');
        $plantsUid = GeoSchema::getParamByName('plantsUid');
        $roadsUid = GeoSchema::getParamByName('roadsUid');
        $localityUid = GeoSchema::getParamByName('localityUid');
        
        $geoSchemaParamsName = [];
        foreach($geoSchemaParams as $key => $param)
        {
            $geoSchemaParamsName[$param['name']]=$param['value'];
        }

        $flipGeoSchemaParams = array_flip($geoSchemaParamsName);

        $urlPlants = 'https://'.$url.'/ServiceJSON/GetGeofences?session='.$token.'&schemaID='.$schemaId.'&IDs='.$plantsUid; //площадки ГК Талина
        $dataP = file_get_contents($urlPlants, true);
        $plantsArr = json_decode($dataP, true);
        $i1 = $i2 = $i3 = 0;

        Coord::truncateTable();

        foreach($plantsArr as $plant)
        {
            $xs = $plant['Lat'];
            $ys = $plant['Lng'];
        $i1++;
        
        $type = '';
        if (isset($flipGeoSchemaParams[$plant['ParentID']])) {
            $type = $flipGeoSchemaParams[$plant['ParentID']];
        }
        if (isset($flipGeoSchemaParams[$plant['ID']])) {
            $type = $flipGeoSchemaParams[$plant['ID']];
        }
        if ($type <> '') {
            foreach($xs as $key => $val)
            {
                $coord = new Coord();
                $coord->setFenceUid($plant['ID']);
                $coord->setParentUid($plant['ParentID']);
                $coord->setXCoord($val);
                $coord->setYCoord($ys[$key]);
                $coord->setType($type);
                $coord->save();           
            }
        }
            
        }

        $urlRoads = 'https://'.$url.'/ServiceJSON/GetGeofences?session='.$token.'&schemaID='.$schemaId.'&IDs='.$roadsUid; //дороги
        $dataR = file_get_contents($urlRoads, true);
        $roadsArr = json_decode($dataR, true);
     
        foreach($roadsArr as $road)
        {
            $xs = $road['Lat'];
            $ys = $road['Lng'];
    $i2++;
        $type = '';
        if (isset($flipGeoSchemaParams[$road['ParentID']])) {
            $type = $flipGeoSchemaParams[$road['ParentID']];
        }
        if (isset($flipGeoSchemaParams[$road['ID']])) {
            $type = $flipGeoSchemaParams[$road['ID']];
        }
            if ($type <> '') {
                foreach($xs as $key => $val)
                {
                    $coord = new Coord();
                    $coord->setFenceUid($road['ID']);
                    $coord->setParentUid($road['ParentID']);
                    $coord->setXCoord($val);
                    $coord->setYCoord($ys[$key]);
                    $coord->setType($type);
                    $coord->save();           
                }
            }
        }

        $urlLocality = 'https://'.$url.'/ServiceJSON/GetGeofences?session='.$token.'&schemaID='.$schemaId.'&IDs='.$localityUid; //населенные пункты
        $dataL = file_get_contents($urlLocality, true);
        $localityArr = json_decode($dataL, true);
            
        foreach($localityArr as $locality)
        {
            $xs = $locality['Lat'];
            $ys = $locality['Lng'];
    $i3++;
    $type = '';
    if (isset($flipGeoSchemaParams[$locality['ParentID']])) {
        $type = $flipGeoSchemaParams[$locality['ParentID']];
    }
    if (isset($flipGeoSchemaParams[$locality['ID']])) {
        $type = $flipGeoSchemaParams[$locality['ID']];
    }
        if ($type <> '') {
            foreach($xs as $key => $val)
            {
                $coord = new Coord();
                $coord->setFenceUid($locality['ID']);
                $coord->setParentUid($locality['ParentID']);
                $coord->setXCoord($val);
                $coord->setYCoord($ys[$key]);
                $coord->setType($type);
                $coord->save();           
            }
        }
        }
        echo "Загрузка завершена. Добавлено геозон в БД: 
        <br><b>Площадки ГК Талина:</b> $i1
        <br><b>Трасса (дороги):</b> $i2
        <br><b>Населенные пункты:</b> $i3
        ";
    }

    public function getGeoPlants()
    {

    }

}


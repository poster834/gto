<?php
namespace Gtm\Controllers;
use Gtm\Models\Users\UsersAuthService;
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
use Gtm\Models\Failures\Failure;
use Gtm\Models\Schemas\Schema;
use Gtm\Models\Alerts\Alert;


class AlertsController extends AbstractController
{
    public function sendFailuresAlerts($id)
    {
        $alerts = [];
        $failuresByDirection = Failure::getActiveFailuresInDirectionId($id);
        foreach($failuresByDirection as $f_B_D)
        {
            $failureName = (FailuresType::getById($f_B_D['type_id']))->getName();
            $machine = Machine::findOneByColumn('uid', $f_B_D['uid']);
            $recipientsId = []; // получатели сообщения
            $serviceUsers = User::findAllByColumn('role_id', 2); //сервисные инженеры
            foreach($serviceUsers as $su){
                $recipientsId[] = $su->getId();
            }
            $recipientsId[] = $f_B_D['mechanic_id']; //+ закрепленный механик в получатели сообщения
            foreach ($recipientsId as $recipientId) {
                $alert = null;            
                $msg = '';
                $issetAlert = Alert::testUniqMessage($recipientId, $f_B_D['failure_id'], 'failures');
                $glonassID = $f_B_D['serial']=='11111'?' Не установлен!':$f_B_D['serial'];
                $msg = 
                '<h4>'.$machine->getName().'</h4>'.
                '<b>ГЛОНАСС ID: </b>'.$glonassID.'<br>'.
                '<b>Дата: </b>'.date("d.m.Y", strtotime($f_B_D['date_create'])).'<br>'.
                '<b>Проблема: </b>'.$failureName.'<br>'.
                
                '<b>Механик: </b>'.User::getById($f_B_D['mechanic_id'])->getName().' ('.User::getById($f_B_D['mechanic_id'])->getPhone().')<br>'.
                '<b>Район: </b>'.Region::getById($f_B_D['region_id'])->getName().'<br>'.
                '<b>Дополнительно: </b>'.$f_B_D['description'].'<br>';

                if (count($issetAlert) > 0) {
                    $alert = Alert::getById($issetAlert);
                } else {
                    $alert = new Alert();
                }

                $alert->setType('failures');
                $alert->setObjectId($f_B_D['failure_id']);
                $alert->setMessageDate(date('Y-m-d'));
                $alert->setMessageTxt($msg);
                $alert->setSenderId(UsersAuthService::getUserByToken()->getId());
                $alert->setRecipientId($recipientId); //отправка каждому получателю оповещения
                $alerts[$recipientId][] = $alert;
                // $alert->save(); // !!! закомментировано, т.к. сохранять в базу нет необходимости
                
                //записываем дату отправки оповещения
                $failure = Failure::getById($f_B_D['failure_id']);
                $failure->setAlertDate(date('Y-m-d'));
                $failure->save();
            }
        }  
        if(self::sendMessages($alerts, $id))
        {
            echo "Сообщения отправлены";
        } else {
            echo "Ошибка отправки сообщений";
        }
        
    }

    private static function sendMessages($arr, $directionId)
    {
        $flag = true;
        foreach($arr as $userId => $messArr)
        {
            if (User::getById($userId)->getRoleId() == 2) { // специалист сервисной службы
                $header = "From:scsrv@atyashevo.ru\r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $message = 'Добрый день, '.User::getById($userId)->getName().'!<br>';
                $header .= 'Cc: pavel.boltunov@atyashevo.ru, markin_r@atyashevo.ru, monitoring.sc@atyashevo.ru, k.semtin@atyashevo.ru, sb.mb@atyashevo.ru, stoper@atyashevo.ru' . "\r\n"; // копия сообщения на этот адрес
                $header .= 'Content-type: text/html; charset="utf-8"';
                $email = User::getById($userId)->getEmail();
                $message .= 'На '.date('d.m.Y').'г. выявлены следующие проблемы приборов ГЛОНАСС по направлению - '.Direction::getById($directionId)->getName().'<br><br>'. "\r\n";
                foreach($messArr as $mess)
                {
                    $message .= $mess->getMessageTxt().'_______________________________<br>'. "\r\n";
                }
                
                //формирование и отправка письма
                $message .="________________________________________<br>
                Сообщение создано автоматически. Отвечать на него не нужно.<br>
                По всем вопросам обращайтесь в Отдел мониторинга и контроля.<br>
                Дежурный: +7 927-196-52-60<br>
                Общий: 8 800 300 00 01<br>". "\r\n";
                
                $subject = 'Проблемы приборов ГЛОНАСС по направлению - '.Direction::getById($directionId)->getName();
                $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
                if (mail($email,$subject,$message,$header)) {
                    $email = $message = '';
                } else {
                    $flag = false;
                }
                
            }

            if (User::getById($userId)->getRoleId() == 4 || User::getById($userId)->getRoleId() == 6) { // механик или руководитель площадки
                $header = "From:scsrv@atyashevo.ru\r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $message = 'Добрый день, '.User::getById($userId)->getName().'!<br>';
                $header .= 'Cc: monitoring.sc@atyashevo.ru' . "\r\n"; // копия сообщения на этот адрес
                $header .= 'Content-type: text/html; charset="utf-8"';
                $email = User::getById($userId)->getEmail();
                $message .= 'На '.date('d.m.Y').'г. выявлены следующие проблемы приборов ГЛОНАСС на закрепленной за Вами технике:<br><br>'. "\r\n";
                foreach($messArr as $mess)
                {
                    $message .= $mess->getMessageTxt().'_______________________________<br>'. "\r\n";
                }
                $message .='Просьба обратиться в Отдел мониторинга для согласования мест стоянки и даты устранения неполадок.<br>'. "\r\n";
                $message .="________________________________________<br>
                Сообщение создано автоматически. Отвечать на него не нужно.<br>
                По всем вопросам обращайтесь в Отдел мониторинга и контроля.<br>
                Дежурный: +7 927-196-52-60<br>
                Общий: 8 800 300 00 01<br>". "\r\n";
                
                $subject = 'Проблемы приборов ГЛОНАСС на закрепленной за Вами технике. ('.User::getById($userId)->getName().')';
                $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
                if (mail($email,$subject,$message,$header)) {
                    $email = $message = '';
                } else {
                    $flag = false;
                }
            }
        }
        return $flag;
    }
}
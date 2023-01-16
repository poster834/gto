<h3>Направления</h3>

    <div class="row">
        <div class="col-lg-3">
            <ul class="subMenu">
            <?php foreach($directions as $direction):?>
                <?php $count = "";?>
                <?php foreach($activeFailuresInDirection as $a_F_I_D):?>
                <?php if ($a_F_I_D['direction_id'] == $direction->getId()) {
                    $count = "<b> (Поломок: ".$a_F_I_D['count'].")</b>";
                }?>
                    <?php endforeach;?>
                <li id="direction<?=$direction->getId()?>" onclick="selectDirection(<?=$direction->getId()?>)"><?=$direction->getName().$count;?></li>
            <?php endforeach;?>
            </ul>
        
        </div>
        <div class="col-lg-9">
        <span id="rightCol">
                <h5>Рассылка оповещений по направлениям:</h5>

                <table class='simpleTable'>
                    <tr>
                        <th>Без оповещения</th>
                        <th>Кто будет оповещен</th>
                        <th>Оповестить</th>
                    </tr>
                    <tr>
                        <td>???</td>
                        <td>Сервисные инженеры (Оповещения о поломках ГЛОНАСС)</td>
                        <td>
                            <!-- <button type="button" id="" class="btn btn-primary btn-sm" onclick="sendAlerts('service')">Оповестить</button> -->
                        </td>
                    </tr>
                    <tr>
                        <td>???</td>
                        <td>Механики, закрепленные за транспортом с выявленными поломками ГЛОНАСС или нарушениями при осуществлении сельскохозяйственных работ.  </td>
                        <td>
                            <!-- <button type="button" id="" class="btn btn-primary btn-sm" onclick="sendAlerts('mechanic')">Оповестить</button> -->
                        </td>
                    </tr>
                    <tr>
                        <td>???</td>
                        <td>Агрономы о нарушениях при осуществлении сельскохозяйственных работ.</td>
                        <td>
                            <!-- <button type="button" id="" class="btn btn-primary btn-sm" onclick="sendAlerts('agronomist')">Оповестить</button> -->
                        </td>
                    </tr>
                    <tr>
                        <td>???</td>
                        <td>Служба безопасности при выявлении отклонений в работе технике</td>
                        <td>
                            <!-- <button type="button" id="" class="btn btn-primary btn-sm" onclick="sendAlerts('agronomist')">Оповестить</button> -->
                        </td>
                    </tr>
                    <tr>
                        <td>???</td>
                        <td>Экономический отдел при поломках приборов ГЛОНАСС и ДУТ</td>
                        <td>
                            <!-- <button type="button" id="" class="btn btn-primary btn-sm" onclick="sendAlerts('agronomist')">Оповестить</button> -->
                        </td>
                    </tr>
                </table>
       
                </span>
            
        </div>
    </div>


<ul class='adminMenu'>
    <li id="company" onclick="showBlock(this.id)">Компания</li>
    <li id="roles" onclick="showBlock(this.id,1)">Группы пользователей</li>
    <li id="users" onclick="showBlock(this.id,1)">Пользователи</li>
    <li id="directions" onclick="showBlock(this.id,1)">Направления            </li>
    <li id="regions" onclick="showBlock(this.id,1)">Районы            </li>
    <li id="failuresTypes" onclick="showBlock(this.id,1)">Типы поломок            </li>
    <li id="offensesTypes" onclick="showBlock(this.id,1)">Типы нарушений            </li>
    <li id="machines" onclick="showBlock(this.id)">Машины           </li>
        <!-- <ul class='subMenu' id='machines_subMenu'>
            <li id="machines_groups" onclick="showSubBlock(this.id,1)">По группам</li>
            <li id="machines_owners" onclick="showSubBlock(this.id,1)">По юр. владельцам</li>
            <li id="machines_gpsFailures" onclick="showSubBlock(this.id,1)">По поломкам глонасс</li>
            <li id="machines_offenses" onclick="showSubBlock(this.id,1)">По нарушениям</li>
        </ul> -->
    <li id="devices" onclick="showBlock(this.id,1)">Приборы ГЛОНАСС            </li>
        <!-- <ul class='subMenu' id='devices_subMenu'>
            <li id="devices_groups" onclick="showSubBlock(this.id,1)">По группам</li>
            <li id="devices_owners" onclick="showSubBlock(this.id,1)">По юр. владельцам</li>
            <li id="devices_gpsFailures" onclick="showSubBlock(this.id,1)">По поломкам глонасс</li>
            <li id="devices_offenses" onclick="showSubBlock(this.id,1)">По нарушениям</li>
        </ul> -->
    <!--<li id="groups" onclick="showBlock(this.id,1)">Группы техники            </li>
    <li id="legalOrganization" onclick="showBlock(this.id,1)">Юридические лица            </li>
    <li id="statistic" onclick="showBlock(this.id,1)">Статистика            </li> -->
</ul>
<hr>
<span class="btn btn-primary" onclick="window.location.href = 'system'">Перейти в режим пользователя</span>
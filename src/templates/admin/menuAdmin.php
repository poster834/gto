<ul class='adminMenu'>
    <li id="company" onclick="showBlock(this.id)"><span class='iconBefore'><i class="fa fa-building" aria-hidden="true"></i></span>Компания</li>
    <li id="roles" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-users" aria-hidden="true"></i></span>Группы пользователей </li>
    <li id="users" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-user" aria-hidden="true"></i></span>Пользователи</li>
    <li id="schema" onclick="showBlock(this.id)"><span class='iconBefore'><i class="fa fa-bars" aria-hidden="true"></i></span>Схема машин          </li>
    <li id="propertiesTypes" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-cogs" aria-hidden="true"></i></span>Свойства приборов и машин          </li>
    <li id="directions" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-code-fork" aria-hidden="true"></i></span>Направления            </li>
    <li id="regions" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-circle-o" aria-hidden="true"></i></span>Районы            </li>
    <li id="failuresTypes" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>Типы поломок            </li>
    <li id="offensesTypes" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-window-close" aria-hidden="true"></i></span>Типы нарушений            </li>
   
    <li id="machines" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-car" aria-hidden="true"></i></span>Машины</li>
        <!-- <ul class='subMenu' id='machines_subMenu'>
            <li id="machines_groups" onclick="showSubBlock(this.id,1)">По группам</li>
            <li id="machines_owners" onclick="showSubBlock(this.id,1)">По юр. владельцам</li>
            <li id="machines_gpsFailures" onclick="showSubBlock(this.id,1)">По поломкам глонасс</li>
            <li id="machines_offenses" onclick="showSubBlock(this.id,1)">По нарушениям</li>
        </ul> -->
    <li id="devices" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-microchip" aria-hidden="true"></i></span>Приборы ГЛОНАСС</li>
    <li id="wialonAccounts" onclick="showBlock(this.id,1)"><span class='iconBefore'><i class="fa fa-map" aria-hidden="true"></i></span>Аккаунты Wialon</li>
    <li id="geoSchema" onclick="showBlock(this.id)"><span class='iconBefore'><i class="fa fa-globe" aria-hidden="true"></i></span>Схема геозон</li>
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
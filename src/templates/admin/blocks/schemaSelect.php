<select name="schemaSelect" id="schemaSelect" onchange="showBtnSchemaLoad('<?=$type;?>')">
    <option value="null" disabled selected>Выберете схему из списка</option>
    <?php foreach(json_decode($schemas) as $schema):?>
        <option value="<?=$schema->ID;?>"><?=$schema->Name;?></option>
    <?php endforeach;?>
</select>

<div id='btnSchemaLoad' style="margin-top: 20px;">

</div>

<div class="dataFile">
   
    </div>
    <div id="result"></div>
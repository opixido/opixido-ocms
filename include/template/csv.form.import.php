<!-- OUI JE VAIS RENTRER CA DANS INCTRADS -->

<form action="?popup=true&doCsv=true" method="post" name="formCsv" id="formCsv" ENCTYPE="multipart/form-data">

			<input type="hidden" name="champ" value="<?= $_REQUEST['champ']; ?>"/>
			<input type="hidden" name="table" value="<?= $_REQUEST['table']; ?>"/>
			<input type="hidden" name="pk" value="<?= $_REQUEST['pk']; ?>"/>
			<input type="hidden" name="id" value="<?= $_REQUEST['id']; ?>"/>

			<label class="genform_txt">
			<img style="vertical-align:middle" src="<?= t('field_img_csv_upload'); ?>" alt="<?= t('csv_titre_upload'); ?>"/>&nbsp;
			<?= t('csv_upload'); ?>
			</label>
			<div class="genform_champ">
			<img style="float:right;" src="pictos_stock/tango/16x16/apps/help-browser.png" alt="<?= t('csv_help_upload'); ?>" />
			<input type="file" name="txt_csvFile" id="txt_csvFile" class="genform_upload"/>
			</div>
			<br />

			<label class="genform_txt">
			<img style="vertical-align:middle" src="<?= t('field_img_csv_summary'); ?>" alt="<?= t('csv_titre_summary'); ?>"/>&nbsp;
			<?= t('csv_summary'); ?>
			</label>	
			<div class="genform_champ">	
			<img style="float:right;" src="pictos_stock/tango/16x16/apps/help-browser.png" alt="<?= t('csv_help_summary'); ?>"/>			
			<textarea class="genform_varchar" name="txt_summary" id="txt_summary"></textarea>
			</div>
			<br />

			<label class="genform_txt">
			<img style="vertical-align:middle" src="<?= t('field_img_csv_caption'); ?>" alt="<?= t('csv_titre_caption'); ?>"/>&nbsp;
			<?= t('csv_caption'); ?>
			</label>
			<div class="genform_champ">		
			<img style="float:right;" src="pictos_stock/tango/16x16/apps/help-browser.png" alt="<?= t('csv_help_caption'); ?>"/>			
			<input type="text" size=75 name="txt_caption" id="txt_caption" value=""/>
			</div>
			<br />

			<label class="genform_txt">
			<img style="vertical-align:middle" src="<?= t('field_img_csv_delimiter'); ?>" alt="<?= t('csv_titre_delimiter'); ?>"/>&nbsp;
			<?= t('csv_delimiter'); ?>
			</label>
			<div class="genform_champ">			
			<img style="float:right;" src="pictos_stock/tango/16x16/apps/help-browser.png" alt="<?= t('csv_help_delimiter'); ?>"/>
			<select name="txt_delimiter" id="txt_delimiter">
			  <option value=";" selected=true>point virgule (export .csv d\'Excel par défaut)</option>
			  <option value="," >virgule</option>
			  <option value="." >point</option>
			  <option value="#" >dièse</option>
			  <option value="$" >dollar ($)</option>
			</select>
			</div>
			<br />		

			<label class="genform_txt">
			<img style="vertical-align:middle" src="<?= t('field_img_csv_toph'); ?>" alt="<?= t('csv_titre_toph'); ?>"/>&nbsp;
			<?= t('csv_toph'); ?>
			</label>
			<div class="genform_champ">
			<img style="float:right;" src="pictos_stock/tango/16x16/apps/help-browser.png" alt="<?= t('csv_help_toph'); ?>"/>
			<input size=2 type="text" name="txt_topHeader" id="txt_topHeader" value="0"/>			  			  			
			</div>
			<br />	

			<label class="genform_txt">
			<img style="vertical-align:middle" src="<?= t('field_img_csv_lefth'); ?>" alt="<?= t('csv_titre_lefth'); ?>" />&nbsp;
			<?= t('csv_lefth'); ?>
			</label>	
			<div class="genform_champ">
			<img style="float:right;" src="pictos_stock/tango/16x16/apps/help-browser.png" alt="<?= t('csv_help_lefth'); ?>" />
			<input type="text" size=2 name="txt_leftHeader" id="txt_leftHeader" value="0"/>			  
			</div>
			<br />

			<label class="valid_form" for="validFormCsv" >
				<input class="inputimage" src="<?= t('field_img_csv_gen'); ?>" title="<?= t('csv_gen'); ?>" name="validFormCsv" id="validFormCsv" value="Valider" type="image"><?= t('csv_gen'); ?>
			</label>

			</form>
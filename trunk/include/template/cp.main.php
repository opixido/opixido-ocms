<div id="entete_cp">
	<span><?= $this->get('infoTime'); ?></span>
</div>


	<table id="table_cp" width="99%">
		<tr>
			<td style="vertical-align:top">
			
			
				<div id="picto_grid">
				
						<?= $this->get('pictoGrid'); ?>		
				
				</div>
			
			</td>
			<td style="vertical-align:top">
			
			
			<div id="user_info">
			    <?= $this->get('userInfos'); ?>
				
			    
				<?
			
			if($GLOBALS['gs_obj']->can('edit','any_table')) { 
				?>
			  <div id="recent_info">
			<!--
			    <span class="onglet" id="onglet_1" onClick="switchInfo('list_attente', this);">A valider</span>
			    -->
			    <span class="onglet" onClick="switchInfo('list_valide', this);">Validées</span>
			  <!--  <span class="onglet" onClick="switchInfo('list_create', this);">Créées</span> -->
			    <span class="onglet" onClick="switchInfo('list_action', this);">Actions</span>
			
				<!--	<?= $this->get('updatedRubs'); ?>		-->	
					<?= $this->get('validatedRubs'); ?>			
				<!--	<?= $this->get('lastCreatedRubs'); ?>		-->
					<?= $this->get('globalActions'); ?>	
			
			  </div>
			  
			  <? 
			}
			?>
			
			</div>	
			</td>
		</tr>
	</table>





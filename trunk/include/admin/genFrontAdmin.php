<?php
#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#



if(!class_exists('genFrontAdmin')) {

	
	class genFrontAdmin {
	
	
	    function genFrontAdmin($table,$id,$printIt = false) {
	
	        global $gs_obj,$nbFrontAdmin;
	        
	        
	
			$this->aurl = ADMIN_URL.'?gfa=1&amp;';
	        $this->frontModif = true;//$_SESSION['adm']['frontModif'];
	
	        $this->gs = &$gs_obj;
	
	        $this->table = $table;
	        $this->id = $id;
	        $this->printIt = $printIt;
	
	        $this->fields = array();
	        $this->authorized =true;
	
	        if(!$this->gs->can('edit',$table,'',$id)) {
		    $nbFrontAdmin++;
	            $this->authorized = false;
	        }
	
	
	
	    }
	
	    function trad($t,$pre='ce_') {
		global $frontAdminTrads;
	
	
	
		if(is_array($t))
			$t = $t[0];
	
		if(tradExists($t)){
			return t($t);
		}
	
	
		if(tradExists($pre.$t)) {
	
			return t($pre.$t);
		}
	
	
	
	
		$t = explode("_",$t);
		for($p=1;$p<count($t);$p++)
			$j .= ucfirst($t[$p]." ");
		return $j;
	
	
	     }
	
	    function startField($field,$actions=array(),$autoHeight=1) {
	
	        global $relinv;
	
	
			$aurl = $this->aurl;
	
	        if(!$this->authorized || !$this->frontModif) {
	            return ;
	        }
	
	
	
	
	        if(!is_array($actions)) {
	            $actions = array($actions);
	        }
	
	
	        if(is_array($field)) {
	        	$field = $field[0];
	        } 
	        
	        return '<div class="gfa" onclick="gfa(this,\''.$this->table.'\',\''.$this->id.'\',\''.$field.'\')" onmouseover="gfa_roll(this)" > ';
	/*
	        $field_nom = $field;
	        $infoMod = ' <b>'.$this->trad($field_nom).'</b> de <b>'.$this->trad($this->table).'</b>';
	        $txt = t('modifier').$infoMod;
	        if(is_array($field)) {
	            $field_nom = $field[0];
	            $fields = $field;
	            $field = implode("_-_",$field);
	
	            $infoMod = ' <b>'.$this->trad($this->table).'</b>';
	            $txt =  t('modifier').$infoMod;
	        } else if($field == "all") {
		      $infoMod = ' <b>'.$this->trad($this->table).'</b>';
	            $txt =  t('modifier').$infoMod;
	
	        }
	*/
	
	
		array_unshift($actions, array('edit',$field));
	
	        $acts = 'new Array(false';/*
	        if($field == "none")
	            $acts .= "false";
	        else
	            $acts .= 'new Array(\''.$aurl.'curPage=0&amp;curTable='.$this->table.'&curId='.$this->id.'&field='.$field.'\',\'<img src=/img/admin/edit.gif /> '.$txt.'\')';
	
	*/
		$new_array = ',new Array(\'';
		$virgule = '\',\'';
		$fin_array = '\')';
	        foreach($actions as $v) {
	
	            if(!is_array($v)) {
	                $v = array($v);
	            }
	
	
			$re = $this->getUrlFor($v);
	
	  	    	$acts .= $new_array.$re[0].$virgule.str_replace('"','\\\'',($re[1]));
	
	  	    	if($re[2]) {
	  	    		$acts .= $virgule.$re[2];
	  	    	}
	  	    	$acts.= $fin_array;
	
	
	
	        }
	
	
	        $acts .= ")";
	
	        $ret = "";
	        $ret .= ('<div id="genFrontAdmin_'.$this->table."_".$this->id."_".$field.'" ');
	
	        if($field == 'all' || is_array($fields))
	        	$infoMod = $this->trad($this->table,'');
	        else
	        	$infoMod = $this->trad($field,'');
	
	        //p('<a class="genFrontAdmin" ');
	        $ret .= (' onmouseover="genFrontAdmin_highlight(\''.$this->table."_".$this->id."_".$field.'\',\''.$infoMod.'\',\''.$autoHeight.'\');" ');
	        $ret .= ('   ');
	        $ret .= (' ');
	        $ret .= (' onclick="return popupAdmin('.$acts.');" >');
	        $ret .= ('<span ><img src="'.ADMIN_URL.t('src_light_edit').'" /></span> ');//$infoMod
	//onmouseout="genFrontAdmin_unhighlight(\''.$this->table."_".$this->id."_".$field.'\');"
	        if($this->printIt)
	            p($ret);
	        else
	            return $ret;
	
	    }
	
	    function getUrlFor($v) {
	
	    		$re = array();
	    		$aurl = $this->aurl;
	
	    		global $relinv;
	    	     switch ($v[0]) {
	
	  	    	case 'add_sub':
	
				/* Ajouter un element en dessous */
				$fk_champ  = $v[1];
				$fk_table = $relinv[$this->table][$fk_champ][0];
				$fk_champ = $relinv[$this->table][$fk_champ][1];
	
	
				$re[0] = $aurl.'curTable='.$fk_table.'&curId=new&curPage=0&genform__add_sub_table='.$this->table.'&genform__add_sub_id='.$this->id.'&field=all';
				$re[1] = '<img src="'.ADMIN_URL.t('src_new').'" /> Ajouter <b>'.$this->trad('un_'.$fk_table).'</b> sous  <b>'.$this->trad('ce_'.$this->table).'</b>';
	
	
	  	    	break;
	
	  	    	case 'add':
	
	  	    	   	$fk_table = $v[1];
	               		$re[0] = $aurl.'curTable='.$fk_table.'&curId=new&curPage=0&field=all';
	               		$re[1] = '<img src="'.ADMIN_URL.t('src_new').'" /> Ajouter <b>'.$this->trad('un_'.$fk_table).'</b>';
	               	break;
	
	               	case 'del':
	
	              	 	$re[0] = $aurl.'curTable='.$this->table.'&delId='.$this->id.'';
	               		$re[1] = '<img src="'.ADMIN_URL.t('src_delete').'" /> Supprimer  <b>'.$this->trad('ce_'.$this->table).'</b>';
	               		$re[2] = 'Voulez vous vraiment supprimer  '.$this->trad('ce_'.$this->table).'';
	
	
	               	break;
	
	               	case 'getup':
	               	        $re[0] = $aurl.'curTable='.$this->table.'&getUp=1&curId='.$this->id.'';
	               		$re[1] = '<img src="'.ADMIN_URL.t('src_up').'" /> '.t('monter').' <b>'.$this->trad('ce_'.$this->table).'</b>';
	
	               	break;
	
	               	case 'getdown':
				$re[0] = $aurl.'curTable='.$this->table.'&getDown=1&curId='.$this->id.'';
	               		$re[1] = '<img src="'.ADMIN_URL.t('src_down').'" /> '.t('descendre').' <b>'.$this->trad('ce_'.$this->table).'</b>';
	
	               	break;
	
	               	case 'edit':
	               		$field = $v[1] ;
	               	        $field_nom = $field;
				$infoMod = ' <b>'.$this->trad($field_nom).'</b> de <b>'.$this->trad('ce_'.$this->table).'</b>';
				$txt = t('modifier').$infoMod;
				if(is_array($field)) {
					$field_nom = $field[0];
					$fields = $field;
					$field = implode("_-_",$field);
	
					$infoMod = ' <b>'.$this->trad('ce_'.$this->table).'</b>';
					$txt =  t('modifier').$infoMod;
				} else if($field == "all") {
					$infoMod = ' <b>'.$this->trad('ce_'.$this->table).'</b>';
					$txt =  t('modifier').$infoMod;
				}
	
				$re[0] = $this->aurl.'curPage=0&amp;curTable='.$this->table.'&curId='.$this->id.'&field='.$field;
				$re[1] = '<img src="'.ADMIN_URL.t('src_editer').'" />'.$txt;
	
	               	break;
	
	
	
	
	  	    }
	
	         	return $re;
	
	    }
	
	    function endField() {
	        if(!$this->authorized || !$this->frontModif) {
	            return ;
	        }
	        $ret = "";
	        $ret .= ('</div>');
	        if($this->printIt)
	            p($ret);
	        else
	            return $ret;
	    }
	
	}

}
?>
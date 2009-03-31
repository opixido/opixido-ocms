<?php

    /*
     *
     * GenOrder
     */

class GenOrder {

    function GenOrder($table,$id=0,$fk_id=0,$fk_champ='') {

        global $orderFields;
        $this->table = $table;
        $this->id = $id;
        $this->fk_id = $fk_id;

        if(is_array($orderFields[$this->table])) {
            $this->DoIt = true;
        } else  {
            $this->DoIt = false;

            return;
        }




        $this->orderField = $orderFields[$this->table][0];
        if($fk_champ == '') {
        	$this->fk_champ = $orderFields[$this->table][1];
        } else {
        	$this->fk_champ = $fk_champ;
        }
        $this->pk = GetPrimaryKey($this->table);

        if(($this->id > 0 ) && strlen($this->fk_champ)) {
            $sql = 'SELECT '.$this->fk_champ.','.$this->orderField.' FROM '.$this->table.' WHERE '.$this->pk.' = "'.$this->id.'"';
            $row = GetSingle($sql);
            //debug($row);
            $this->fk_id = $row[$this->fk_champ];
            $this->curOrderValue = $row[$this->orderField];
        }



    }


    function GetUp() {


        if(!$this->DoIt)
            return;


		logAction('get_up',$this->table,$_REQUEST['curId']);

        $sql = 'SELECT '.$this->orderField.','.$this->pk.'
        	FROM '.$this->table .'
        	WHERE '.$this->orderField.' < '.$this->curOrderValue.'
        	AND '.$this->fk_champ.' = "'.$this->fk_id.'"
        	'.$this->specialClause().'
        	ORDER BY '.$this->orderField.' DESC LIMIT 0,1';


        $row = GetSingle($sql);
        //debug($row[$this->pk] ." -> ".$this->curOrderValue);
        //debug($this->id ." -> ".$row[$this->orderField]);
        if(count($row)) {

            $sql1 = 'UPDATE '.$this->table .' SET '.$this->orderField.' = '.$this->curOrderValue.' WHERE '.$this->pk.' = '.$row[$this->pk];
            $sql2 = 'UPDATE '.$this->table .' SET '.$this->orderField.' = '.$row[$this->orderField].' WHERE '.$this->pk.' = '.$this->id;

            DoSql($sql1);
            DoSql($sql2);
        }
    }



    function GetDown() {

        if(!$this->DoIt)
            return;

		logAction('get_down',$this->table,$_REQUEST['curId']);


        $sql = 'SELECT '.$this->orderField.','.$this->pk.'
        	FROM '.$this->table .'
        	WHERE '.$this->orderField.' > '.$this->curOrderValue.'
        	AND '.$this->fk_champ.' = "'.$this->fk_id.'"
        	'.$this->specialClause().'
        	ORDER BY '.$this->orderField.' ASC LIMIT 0,1';

        $row = GetSingle($sql);


        //debug($row[$this->pk] ." -> ".$this->curOrderValue);
        //debug($this->id ." -> ".$row[$this->orderField]);


        if(count($row)) {


            $sql1 = 'UPDATE '.$this->table .' SET '.$this->orderField.' = '.$this->curOrderValue.' WHERE '.$this->pk.' = '.$row[$this->pk];
            $sql2 = 'UPDATE '.$this->table .' SET '.$this->orderField.' = '.$row[$this->orderField].' WHERE '.$this->pk.' = '.$this->id;

            DoSql($sql1);
            DoSql($sql2);
        }
    }

    function ReOrder() {
        /*
         * Reorder the whole table
         */

        if(!$this->DoIt)
            return;

        if($this->fk_id) {

            $sql = 'SELECT * FROM '.$this->table.' WHERE 1 ';

            if(strlen($this->fk_champ)) {

                $sql .= ' AND '.$this->fk_champ.' = "'.$this->fk_id.'"';

            }

	    $sql .= ' '.$this->specialClause().' ';

            $sql .= ' ORDER BY '.$this->orderField.' , '.GetTitleFromTable($this->table," , ");

            $res = GetAll($sql);


            /*
             * On parcourt le groupe d'enregistrements en question
             */

             $this->reorderRes($res);
        }
    }



    private function specialClause() {

	if($this->table == 's_rubrique') {
		return ' '.sqlRubriqueOnlyReal().' ';
	} else {
		return '';
	}

    }

    function ReorderRes($res,$start=1) {

        if(!$this->DoIt)
            return;

            foreach($res as $row) {

                /* Si l'ordre ne correspond pas on le change */
                if($row[$this->orderField] != $normalOrder) {

                    $sql = 'UPDATE '.$this->table.' SET '.$this->orderField.' = '.$start.' WHERE '.$this->pk.' = "'.$row[$this->pk].'"';

                    if(strlen($this->fk_champ)) {
                        $sql .= ' AND '.$this->fk_champ.' = "'.$this->fk_id.'"';
                    }

                    $res = DoSql($sql);

                }

                $start++;

            }

    }


    function OrderAfterInsert() {


          if(!$this->DoIt)
            return;

            $sql = 'SELECT MAX('.$this->orderField.') AS MAXI FROM '.$this->table.' WHERE 1 ';

            if(strlen($this->fk_champ)) {

                $sql .= ' AND '.$this->fk_champ.' = "'.$this->fk_id.'"';
            }

            $sql .= $this->specialClause();

            $sql .= 'LIMIT 0,1';

            $max = GetSingle($sql);
            $max = $max['MAXI']+1;


            $sql = 'SELECT * FROM '.$this->table.' WHERE '.$this->orderField.' = 0';

            if(strlen($this->fk_champ)) {

                $sql .= ' AND '.$this->fk_champ.' = "'.$this->fk_id.'"';
            }


	    $sql .= $this->specialClause();


            $sql .= ' ORDER BY '.GetPrimaryKey($this->table);



            $res = GetAll($sql);


            $this->reorderRes($res,$max);

    }

}

?>
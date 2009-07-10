<?php

$e = $this->editMode;
if($_REQUEST['curId'] == 'new') {
	$this->gen('trad_id');
} else {
	$this->editMode = true;
	$this->gen('trad_id');
	$this->editMode =$e;
	echo '<br/>';
}
$this->genlg('trad');


?>
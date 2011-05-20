<div id="login_box">
<h2>@@titre@@</h2>
<form id="login_form" method="post">

	<p>
		<label for="login">@@login@@</label>
		<input  class="text" type="text" name="login" id="login" value="<?=$_REQUEST['login']?>" />
	</p>
	<p>
	<label for="passwd">@@password@@</label>
	<input class="text" type="password" name="passwd" id="passwd" />
	</p>	
	<div class="submit">
	<input  type="submit" id="submitlogin"  value="@@submit@@" />	
	</div>
</form>

</div>
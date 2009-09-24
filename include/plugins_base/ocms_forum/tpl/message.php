<div class="forum_message">

<div class="forum_header">

	<IF_CANREPLY>
		<a class="forum_btn" href="@@url_reply@@">@@forum_reply@@</a>
	</IF_CANREPLY>
	
	<IF_ISLOGGED>
		<a class="forum_btn" href="@@url_watch@@">@@forum_watch@@</a>
		
	</IF_ISLOGGED>	
			
	<a class="forum_btnleft" href="@@url_retour@@">@@forum_retour_themes@@</a>
	
</div>


<MESSAGE>	
	
	<div class="message" id="m@@id@@">
		
		<div class="content">
		
			<div class="top">
			
				<div class="user">
				
					<span>@@username@@</span>
					@@usermessages@@
				
				</div>
				
				
				<div class="titre">
				
					@@titre@@
					<span class="date">
						@@date@@
					</span>
				
				</div>
				
				<div class="quote">
					<IF_CANREPLY>
						<a href="@@url_quote@@">@@quote@@</a>
					</IF_CANREPLY>
					<IF_CANEDIT>
						&nbsp; <a href="@@url_edit@@">@@edit@@</a>
					</IF_CANEDIT>	
					<IF_ISLOGGED>
						&nbsp; 	<a href="@@url_report@@">@@report@@</a>
					</IF_ISLOGGED>	

				</div>

			
			</div>
			
			<div class="clearer"></div>
			
			<div class="avatar">
				@@useravatar@@
			</div>

			<div class="texte">
				
					<IF_ISMODO>
					<div class="admin">
						@@select@@
						<a href="@@url_edit@@">@@edit@@</a>
						<a onclick='return confirm(<?=alt(t('confirm_delete'))?>)' href="@@url_delete@@">@@delete@@</a>
						<a onclick="return doblank(this)" class="quote" href="@@url_admin@@">@@forum_admin@@</a>
					</div>
					</IF_ISMODO>
				
				
				@@texte@@
				
				@@signature@@
			</div>
			
			
		</div>
	
		
	<div class="clearer">&nbsp;</div>

	
	</div>
	

</MESSAGE>

<div class="forum_header">
	<IF_CANREPLY2>
		<a class="forum_btn" href="@@url_reply@@">@@forum_reply@@</a>
	</IF_CANREPLY2>
	
	<a class="forum_btn" href="@@url_retour@@">@@forum_retour_themes@@</a>
</div>

</div>
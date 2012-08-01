<script type="text/template" id="plugin-messages-create-modal-template">
	<hgroup class="page-title cf">
		<div class="page-h1 col_9">
			<h1>New message</h1>
		</div>
		<div class="page-actions col_3">
			<h2 class="close">
				<a href="#">
					<span class="icon"></span>
					Close
				</a>
			</h2>
		</div>
	</hgroup>
	<div class="modal-body create-new">
		<form>
			<input type="hidden" id="message-url" value="<?php echo $link_create; ?>" />
			<input type="hidden" id="lookup-url" value="<?php echo $link_lookup; ?>" />
			<h2>Recipient</h2>
			<div class="field">
				<input type="text" id="message-recipient" placeholder="The account to send the message to" name="recipient" class="name" value="<%= recipient %>" />
			</div>
			<ul id="recipient-lookup">
			</ul>
			<h2>Subject</h2>
			<div class="field">
				<input type="text" id="message-subject" placeholder="The subject of the message" name="subject" class="name" value="<%= subject %>" />
			</div>
			<h2>Message</h2>
			<div class="field">
				<textarea id="message-body" name="body" rows="6" cols="80"></textarea>
			</div>
			<div class="field">
				<p class="button-blue send"><a href="#">Send</a></p>
				<p class="message-status"></p>
			</div>
		</form>
	</div>
</script>

<script type="text/template" id="plugin-messages-lookup-result-template">
	<!--<a class="avatar-wrap"><img src="<%= avatar %>" /></span></a><%= name %>-->
	<a id="<%= account_path %>" tabindex="-1"><%= name %></a>
</script>

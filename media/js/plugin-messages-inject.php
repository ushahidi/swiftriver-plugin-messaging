<?php header("content-type: application/x-javascript"); ?>
$(function(){
	$("hgroup.user-title div.user-summary.col_9").append('<p class="button-blue button-small button-pm"><a href="#">Send Message</a></p>');
	$('.button-pm a').click(function(){
		modalShow(new PluginMessagesCreateModal({<?php echo 'recipient: "'.$_GET['r'].'"'; ?>}).render().el);
		return false;
	});
});

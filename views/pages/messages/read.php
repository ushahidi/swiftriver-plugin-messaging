<div id="content" class="messages message drop-full center cf" align="center">
	<div class="base">
		<section class="drop-source cf">
			<p class="metadata"><?php echo date('F jS, Y, h:m A', strtotime($message->timestamp)); ?> (<?php echo $message->relative_time(); ?>)</p>
			<a href="<?php echo URL::site($message->sender->account->account_path); ?>" class="avatar-wrap">
				<img src="<?php echo Swiftriver_Users::gravatar($message->sender->email, 50) ?>">
			</a>
			<div class="byline">
				<h2><?php echo $message->sender->name; ?></h2>
				<p>
					to <a href="<?php echo URL::site($message->recipient->account->account_path); ?>"><?php echo $message->recipient->name; ?></a>
				</p>
			</div>
		</section>
		<div class="drop-body">
			<h2><?php echo $message->subject; ?></h2>
			<?php echo nl2br($message->message); ?>
		</div><?php if (isset($reply)): ?>
		<div class="drop-actions cf">
			<p class="button-blue reply share has-icon">
				<a href="#"><span class="icon"></span>Reply</a>
			</p>
		</div><?php endif; ?>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$('.reply a').click(function(){
			modalShow(new PluginMessagesCreateModal({<?php echo 'recipient: "'.str_replace("\"", "\\\"", $message->sender->account->account_path).'", subject: "Re: '.str_replace("\"", "\\\"", $message->subject).'"'; ?>}).render().el);
			return false;
		});
	});
</script>

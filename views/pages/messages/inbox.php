<script type="text/javascript">
	$(function() {
		$('.messages .item').click(function(){
			window.location.href = '<?php echo $link_inbox; ?>/'+$(this).attr('id');
		});
	});
</script>
<div id="content" class="messages inbox center cf"><?php if (count($messages) > 0): foreach ($messages as $m): ?>
	<div id="<?php echo $m->id; ?>" class="item <?php echo $m->read ? 'read' : 'unread'; ?>">
		<a class="avatar-wrap"><img src="<?php echo Swiftriver_Users::gravatar($m->sender->email, 45) ?>" /></a>
		<div class="details">
			<span class="name"><?php echo $m->sender->name; ?></span>
			<span class="time"><?php echo $m->relative_time(); ?></span><br />
			<span class="subject"><?php echo $m->subject; ?></span>
			<span class="preview"> - <?php echo Text::limit_chars($m->message, 200, '...', TRUE); ?></span>
		</div>
	</div><?php endforeach; else: ?>
	<article class="container base">
		<div class="alert-message blue">
			<p>
				<strong><?php echo __("Empty Inbox"); ?></strong>
				<?php echo __("There are no messages in your inbox"); ?>
			</p>
		</div>
	</article><?php endif; ?>
</div>

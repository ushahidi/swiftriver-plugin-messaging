<hgroup class="page-title bucket-title messages-title cf">
	<div class="center">
		<div class="page-h1 col_9">
			<h1><?php echo __("Messages"); ?> <em><?php echo __($active); ?></em></h1>
			<div class="rundown-people">
				<h2><?php echo $unread ?></h2>
				<span> new message<?php echo ($unread == 1) ? '' : 's'; ?> </span>
			</div>
		</div>
		<div class="page-actions col_3">
			<h2 class="back">
				<a href="<?php echo $link_index; ?>">
					<span class="icon"></span>
					<?php echo __("Return to dashboard"); ?>
				</a>
			</h2>
			<h2 class="add">
				<a href="#" class="create">
					<span class="icon"></span>
					<?php echo __("Send a message"); ?>
				</a>
			</h2>
		</div>
	</div>
</hgroup>

<nav class="page-navigation cf">
	<div class="center">
		<div id="page-views" class="settings touchcarousel col_12">
			<ul class="touchcarousel-container"><?php foreach ($nav as $n => $u): ?>
				<li class="touchcarousel-item<?php if ($n == $active) echo ' active'; ?>">
					<a href="<?php echo $u; ?>"><?php echo $n; ?></a>
				</li><?php endforeach; ?>
			</ul>
		</div>
	</div>
</nav>



<?php echo $body; ?>

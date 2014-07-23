<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', "Wanton Wicked an Online World of Darkness Roleplaying Game"); ?>
<?php $this->set('header_for_layout', 'Home'); ?>

<div class="tinymce-content">
    <?php echo $content; ?>
</div>
<h2>Log In OOC</h2>
<form method="post" action="/chat/index.php">
    Name: <input type="text" name="username"/>
    <input type="submit" value="Log in">
</form>

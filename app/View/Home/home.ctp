<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', "Wanton Wicked an Online World of Darkness Roleplaying Game"); ?>
<?php $this->set('header_for_layout', 'Home'); ?>

<div class="tinymce-content">
    <?php echo $content; ?>
</div>
<div class="row">
    <div class="small-12 medium-4 column">
        <h2>Log In OOC</h2>
        <form method="post" action="/chat/index.php">
            Name: <input type="text" name="username"/>
            <button type="submit" class="button">Log in</button>
        </form>
    </div>
</div>


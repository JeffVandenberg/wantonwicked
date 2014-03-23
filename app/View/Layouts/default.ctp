<?php /* @var View $this */ ?>
<?php /* @var string $title_for_layout */ ?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $title_for_layout; ?>
    </title>
    <?php echo $this->Html->meta('icon'); ?>
    <META NAME="copyright" content="(c) 2013 Jeff Vandenberg">
    <META NAME="ROBOTS" CONTENT="noimageindex,follow">
    <?php
    echo $this->Html->css(array(
        'wanton/jquery-ui-1.10.3.custom.min',
        'wanton/jquery.ui.menubar',
        'gaming-sandbox',
        'ww4_v2',
    ));

    echo $this->Html->script(array(
        'jquery-1.9.1',
        'jquery-ui-1.10.3.custom.min',
        'jquery.watermark.min',
        'jquery.ui.menubar',
        'wanton',
        'gaming-sandbox'
    ));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>
<body>
<div id="gs-header-bar">
    <div id="gs-header-inner">
        <div id="gs-header-logo">
            <a href="/" id="gs-header-logo-nav">
                Wanton Wicked
            </a>
        </div>
        <div id="gs-header-games">
            <label for="gs-game-selector">
                <select id="gs-game-selector">
                    <option value="www">Home</option>
                    <option value="ragnarok">Ragnarok NYC</option>
                    <option value="wantonwicked" selected>Wanton Wicked</option>
                </select>
            </label>
        </div>
    </div>
</div>
<div id="wrapper">
    <div id="login">
        <div id="logina">
            <div id="loginb">
                <?php echo $this->UserPanel->Create($this->Html->url()); ?>
                <span id="server-time"></span>
            </div>
        </div>
    </div>
    <div id="logo">
    </div>
    <div id="nav">
        <?php echo $this->MainMenu->Create($menu); ?>
    </div>
    <div id="content">
        <div id="pagetitle">
            <?php if(isset($header_for_layout)): ?>
                <?php echo $header_for_layout; ?>
            <?php else: ?>
                <?php echo $title_for_layout; ?>
            <?php endif; ?>
        </div>
        <div id="contenta">
            <div id="contentb" class="contentbox">
                <?php echo $this->Session->flash(); ?>

                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
    </div>
    <div id="content">
        <div id="contenta">
            <div id="contentb" class="contentbox">
                <div style="font-size: 9px;text-align: center;">World of Darkness, Changeling: The Lost, Geist: The Sin-Eaters, Mage: The Awakening,
                    Vampire: The Requiem, Werewolf: The Forsaken <br>
                    &copy;2013-2014 <a href="http://www.white-wolf.com/fansites/termsofuse.php" target="_blank">White
                        Wolf Publishing, Inc.</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="footer-bar">
    <div id="footer-inner">
        <div id="right-footer">
        Layout and Design by jarden
        Produced by Jeff Vandenberg
        Copyright <?php echo date('Y'); ?>
        </div>
    </div>
</div>
</body>
</html>
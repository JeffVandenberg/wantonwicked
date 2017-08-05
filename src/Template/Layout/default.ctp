<?php
use App\View\AppView;
use Cake\Core\Configure;

/* @var AppView $this */
/* @var string $title_for_layout */
/* @var string $buildNumber */
?>

<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $title_for_layout; ?>
    </title>
    <?php echo $this->Html->meta('icon'); ?>
    <META NAME="copyright" content="(c) <?php echo date('Y'); ?> Jeff Vandenberg">
    <META NAME="ROBOTS" CONTENT="noimageindex,follow">
    <?php
    if (Configure::read('debug')) {
        echo $this->Html->css([
            'app',
            'wanton/jquery.toast'
        ]);
        echo $this->Html->script([
            'jquery',
            'jquery.autocomplete',
            'jquery.toast',
            'server_time',
            'foundation',
            'tinymce/tinymce.min',
            'wanton',
        ]);
    } else {
        $this->Shrink->css(array(
            'app.css',
            'wanton/jquery.toast.min.js'
        ));
        echo $this->Shrink->fetch('css');

        $this->Shrink->js([
            'jquery.min.js',
            'jquery.autocomplete.min.js',
            'jquery.toast.min.js',
            'server_time.js',
            'foundation.min.js',
            'tinymce/tinymce.min.js',
            'wanton.js',
        ]);
        echo $this->Shrink->fetch('js');
    }

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
    <link href="https://fonts.googleapis.com/css?family=Marcellus+SC" rel="stylesheet">
    <script type="application/javascript">
        wantonWickedTime.serverTime = <?php echo $serverTime; ?>;
    </script>
</head>
<body>
<div id="header">
    <div class="widthsetter">
        <div id="logo"></div>
        <div id="userpanel"><?php echo $this->UserPanel->Create($this->Url->build()); ?></div>
        <div id="nav" data-sticky-container>
            <div class="title-bar" data-responsive-toggle="main-menu" data-hide-for="large">
                <button class="menu-icon" type="button" data-toggle="main-menu"></button>
                <div class="title-bar-title">Menu</div>
            </div>

            <div class="top-bar" data-sticky data-options="marginTop:0;" style="width:100%"
                 data-top-anchor="main-content">
                <div class="top-bar-left">
                    <?php echo $this->MainMenu->Create($menu); ?>
                </div>
                <div class="top-bar-right">
                </div>
            </div>
        </div>
    </div>
</div>
<div id="main-content" class="widthsetter">
    <div id="content">
        <div id="pagetitle">
            <?php if (isset($header_for_layout)): ?>
                <?php echo $header_for_layout; ?>
            <?php else: ?>
                <?php echo $title_for_layout; ?>
            <?php endif; ?>
        </div>
        <div id="contenta" class="contentbox">
            <?php if (isset($submenu)): ?>
                <?php echo $this->SubMenu->Create($submenu); ?>
            <?php endif; ?>
            <?php echo $this->Flash->render(); ?>

            <?php echo $this->fetch('content'); ?>
        </div>
    </div>
</div>
<div id="footer">
    <div class="row">
        <div class="small-12 column text-center">
            <div style="font-size: 9px;">The Storytelling System, Beast the Primordial, Changeling
                the Lost, Chronicles of Darkness, Demon the Descent, Mage the Awakening, Vampire the Requiem, and
                Werewolf the Forsaken
                &copy;2014-2016 CCP hf and published by <a href="http://theonyxpath.com/" target="_blank">Onyx Path
                    Publishing</a>.<br>
                Produced by Jeff Vandenberg. Layout and Design by Jill Arden &copy;<?php echo date('Y'); ?>
                Build # <?php echo $buildNumber; ?>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->Html->image(
    'indicator.gif',
    array('id' => 'busy-indicator')
);
?>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?php echo $title_for_layout; ?></title>
</head>
<body>
<div id="background" style="background: #ffffff url(http://wantonwicked.gamingsandbox.com/img/main/bg1.jpg);">
    <img src="http://wantonwicked.gamingsandbox.com/img/main/ww_banner.png"  alt="Wanton Wicked Banner" />
    <div id="body" style="background-color: #dddddd;margin: 5px;padding: 5px;border: 1px solid #000000;border-radius: 5px;">
        <?php echo $this->fetch('content'); ?>
    </div>
</div>
</body>
</html>

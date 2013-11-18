<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 7/22/13
 * Time: 10:12 AM
 * To change this template use File | Settings | File Templates.
 */
    $userName = (isset($_POST['username'])) ? htmlspecialchars($_POST['username']) : 'Unknown User ' . mt_rand(10000, 99999);
?>
<html>
<head>
    <title>
        GamingSandbox Chat
    </title>
    <script language="JavaScript" type="text/javascript">
        <!--
        var sourceBase="http://host5.chatblazer.com/";
        var siteID = "CBS496";
        var tagName = "script";
        document.write('<'+tagName+' language="JavaScript" type="text/javascript" src="'+sourceBase+'chatblazer.js"></'+tagName+'>');
        //-->
    </script>
</head>
<body>
<script language="JavaScript" type="text/javascript">
    <!--
    var mainConfig="CBS496/config.xml";
    var mainLang= "";
    var mainSkin= "";

    // username and password used for direct login only
    var directUsername	= "<?php echo $userName; ?>";
    var directPassword	= "";
    var roomPassword	= "";
    var roomID		= "1301";
    var roomName		= "";
    var privateChatCID	= "";

    // logoPath should be swf/png/jpg/gif, approximately 200x30
    // bgPath should be swf/png/jpg/gif, size will be stretched to the full background
    // bgColor if no background is desired
    var logoPath		= "";
    var bgPath		= "";
    var bgColor		= "";
    var barColor		= "";	// #336699
    var textColor		= "";	// #FFFFFF

    // Size of ChatBlazer application in % or pixels
    var chatWidth="100%";
    var chatHeight="100%";

    // path of chat
    var flashPath="ChatBlazer8"+(mainSkin!=""?"_"+mainSkin:"")+".swf?cb=1";

    function addParam(pname,pval) {
        if (typeof pval!="undefined" && pval) { flashPath = flashPath + "&"+pname+"=" + encodeURIComponent(pval); }
    }

    addParam("lang",mainLang);
    addParam("config",mainConfig);
    addParam("skin",mainSkin);
    addParam("username",directUsername);
    addParam("password",directPassword);
    addParam("roompass",roomPassword);
    addParam("roomid",roomID);
    addParam("roomname",roomName);
    addParam("privatechatcid",privateChatCID);
    addParam("logo",logoPath);
    addParam("bgpath",bgPath);
    addParam("bgcolor",bgColor);
    addParam("barcolor",barColor);

    if (navigator.appVersion.indexOf("MSIE") != -1) {
        addParam("isIE","1");
    }

    embedFlash(flashPath,chatWidth,chatHeight,"cb8",sourceBase, "#000000");
    //-->
</script>
</body>
</html>

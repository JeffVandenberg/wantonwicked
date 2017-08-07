/*
 * play sound
 *
 */

function doSound(sfx) {
    if(sfx) {
        var playSound = false;
        if(document.getElementById('soundsID').checked==true && sfx == 'beep_high.mp3')
        {
            playSound = true;
        }

        if(document.getElementById('entryExitID').checked==true && (sfx == 'doorbell.mp3' || sfx == 'door_close.mp3'))
        {
            playSound = true;
        }

        if(document.getElementById('sfxID').checked==true && mySFX.toString().lastIndexOf(sfx) == -1 && (sfx != 'doorbell.mp3' && sfx != 'door_close.mp3' && sfx != 'beep_high.mp3'))
        {
            playSound = true;
        }


        if(playSound) {
            var flashvars = {};
            flashvars.sndfilename = sfx;
            var params = {};
            params.play = "true";
            params.loop = "false";
            params.menu = "false";
            params.scale = "noscale";
            // params.wmode = "transparent";
            params.height = "200";
            params.width = "200";
            params.bgcolor = "#FFFFFF";
            var attributes = {};
            attributes.align = "top";
            swfobject.embedSWF("swf/playSnd.swf", "playSndDiv", "100%", "1", "9.0.0", "swf/expressInstall.swf", flashvars, params, attributes);
        }
    }
}

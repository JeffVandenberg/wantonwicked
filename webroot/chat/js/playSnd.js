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
            var sound = new Howl({
                src: '/chat/sounds/' + sfx,
                autoplay: true,
                onend: function() {
                    console.log('done playing: ' + sfx);
                }
            });
            sound.play();
        }
    }
}

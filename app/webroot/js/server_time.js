/**
 * Created by JeffVandenberg on 2/7/2015.
 */
var wantonWickedTime =  {
    difference: 0,
    serverTime: 0,
    serverTimeElementId: '',

    runClock: function(elementId) {
        this.serverTimeElementId = elementId;

        var local = new Date();
        wantonWicked.difference = (local.getTime() - (local.getTimezoneOffset() *60000)) - wantonWicked.serverTime;
        this.updateTime();

    },
    updateTime: function() {
        setTimeout("wantonWicked.updateTime();", 60000);
        $(this.serverTimeElementId).html(this.makeTime());
    },
    makeTime: function() {
        var timer = new Date(new Date().getTime() - wantonWicked.difference);

        var hhN = timer.getHours();
        var hh, AP;
        if (hhN > 12) {
            hh = String(hhN - 12);
            AP = "pm";
        }
        else if (hhN == 12) {
            hh = "12";
            AP = "pm";
        }
        else if (hhN == 0) {
            hh = "12";
            AP = "am";
        }
        else {
            hh = String(hhN);
            AP = "am";
        }
        var mm = String(timer.getMinutes());
        var ss = String(timer.getSeconds());
        return "Server Time: " + hh + ((mm < 10) ? ":0" : ":") + mm + ((ss < 10) ? ":0" : ":") + ss + AP;
    }
};

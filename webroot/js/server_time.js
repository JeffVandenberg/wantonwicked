/**
 * Created by JeffVandenberg on 2/7/2015.
 */
let wantonWickedTime = {
    difference: 0,
    serverTime: 0,
    serverTimeElementId: '',

    runClock: function (elementId) {
        this.serverTimeElementId = elementId;

        let local = new Date();
        this.difference = (local.getTime() - (local.getTimezoneOffset() * 60000)) - this.serverTime;
        this.updateTime();

    },
    updateTime: function () {
        setTimeout("wantonWickedTime.updateTime();", 60000);
        $(this.serverTimeElementId).html(this.makeTime());
    },

    renderTime: function (timer) {
        let hhN = timer.getHours(),
            hh, AP;
        if (hhN > 12) {
            hh = String(hhN - 12);
            AP = "pm";
        } else if (hhN === 12) {
            hh = "12";
            AP = "pm";
        } else if (hhN === 0) {
            hh = "12";
            AP = "am";
        } else {
            hh = String(hhN);
            AP = "am";
        }
        let mm = String(timer.getMinutes());

        return hh + ((mm < 10) ? ":0" : ":") + mm + AP;
    },

    renderDate: function (timer) {
        return timer.getFullYear() + '-' + ((timer.getMonth() < 10) ? '0' : '') + (parseInt(timer.getMonth(), 10) + 1) + '-' + timer.getDate();
    },

    makeTime: function () {
        let timer = new Date(new Date().getTime() - this.difference);

        return "Server Time: " + this.renderTime(timer);
    }
};

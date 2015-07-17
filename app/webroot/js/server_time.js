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
        this.difference = (local.getTime() - (local.getTimezoneOffset() *60000)) - this.serverTime;
        this.updateTime();

    },
    updateTime: function() {
        setTimeout("wantonWickedTime.updateTime();", 60000);
        $(this.serverTimeElementId).html(this.makeTime());
    },

    renderTime : function (timer) {
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
        return hh + ((mm < 10) ? ":0" : ":") + mm + AP;
    },

    renderDate: function(timer) {
        return timer.getFullYear() + '-' + (parseInt(timer.getMonth()) + 1) + '-' + timer.getDate();
    },

    makeTime: function() {
        var timer = new Date(new Date().getTime() - this.difference);
        return "Server Time: " + this.renderTime(timer);
    }
};

$(function() {
    $('.server-time, #server-time')
        .attr('title', 'Show Local Time')
        .addClass('clickable')
        .tooltip({
            content: function() {
                var time = $(this).text(),
                    serverTime = new Date(),
                    timeResult = time.match(/(\d{1,2}):(\d{1,2})[\s]*([aApP][mM])/),
                    dateResult = time.match(/(\d{4})-(\d{1,2})-(\d{1,2})/),
                    timePart = '',
                    datePart = '';

                if(timeResult !== null) {
                    var hours = parseInt(timeResult[1]) + ((timeResult[3].toLowerCase() === 'am') ? 0 : 12);
                    serverTime.setHours(hours);
                    serverTime.setMinutes(timeResult[2]);
                }
                if(dateResult !== null) {
                    // we have date component to render
                    serverTime.setYear(dateResult[1]);
                    serverTime.setMonth(parseInt(dateResult[2])-1);
                    serverTime.setDate(dateResult[3]);
                }

                var localTime = new Date(serverTime.getTime() + wantonWickedTime.difference);
                return 'Local Time: <br />' + wantonWickedTime.renderDate(localTime) + ' ' + wantonWickedTime.renderTime(localTime);
            }
        });
});
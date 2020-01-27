import moment from "moment";
import Vue from "vue";

Vue.filter('dateTime', (value) => {
    if (value) {
        return moment(String(value)).format('MM/DD/YYYY hh:mma')
    }
});

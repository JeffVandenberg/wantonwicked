/**
 * Created by jvandenberg on 12/15/15.
 */
function Bot(preprocessSelectors, selectors) {
    this.preprocessSteps = preprocessSelectors;
    this.processSteps = selectors;
    this.currentStep = 0;
    this.currentDelay = 0;
    this.isRunning = false;
    this.stepTimeout = 0;
    this.loopCount = 0;
    this.loopTimeout = 0;
    this.loopVariance = 0;
    this.loopVarianceMod = 0;
}

Bot.prototype = {
    getDelayForNextAction: function () {
        if (this.currentStep == 0) {
            this.currentDelay = this.loopTimeout + Math.round(Math.random() * this.loopVariance) + this.loopVarianceMod;
        } else {
            this.currentDelay = 1;
        }
        return this.currentDelay;
    },

    incrementStep: function () {
        if (this.currentStep++ >= this.processSteps.length) {
            this.currentStep = 0;
            this.loopCount++;
        }
    },

    performLoop: function () {
        if (this.isRunning && this.processSteps.length > 0) {
            //console.log("processing step: " + this.currentStep + ' finding: ' + this.processSteps[this.currentStep]);
            if ($(this.processSteps[this.currentStep]).length > 0) {
                //console.log('found:' + this.processSteps[this.currentStep]);
                $(this.processSteps[this.currentStep]).click();
                this.incrementStep();
            } else {
                //console.log('did not find:' + this.processSteps[this.currentStep]);
            }
            this.getDelayForNextAction();
            //console.log('setting delay of: ' + this.currentDelay);
            var me = this;
            setTimeout(function () {
                me.performLoop();
            }, this.currentDelay * 1000);
        } else {
            console.log('ending main execution');
        }
    },

    performPreprocessSteps: function () {
        console.log('starting preprocess');
        if (this.isRunning && this.preprocessSteps.length > 0) {
            //console.log("processing step: " + this.currentStep + ' finding: ' + this.processSteps[this.currentStep]);
            if ($(this.preprocessSteps[this.currentStep]).length > 0) {
                //console.log('found:' + this.processSteps[this.currentStep]);
                $(this.preprocessSteps[this.currentStep]).click();
                this.incrementStep();
            } else {
                //console.log('did not find:' + this.processSteps[this.currentStep]);
            }
            //console.log('setting delay of: ' + this.currentDelay);
            var me = this;
            setTimeout(function () {
                me.performPreprocessSteps();
            }, 1000);
        } else {
            console.log('ending preprocess');
        }
    },
    start: function () {
        this.isRunning = true;
        this.currentStep = 0;
        this.performPreprocessSteps();
        this.loopCount = 0;
        this.currentStep = 0;
        this.performLoop();
    },

    stop: function () {
        this.isRunning = false;
    }
};


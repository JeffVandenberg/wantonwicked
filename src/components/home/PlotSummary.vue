<template>
    <div>
        <h3 class="float-left">Current Plots</h3>
        <div class="button-group float-right" v-if="this.showNew">
            <a class="button small" href="/plots/add">New</a>
        </div>
        <div class="clearfix" v-if="plots == null">
            Loading...
        </div>
        <div class="clearfix" v-else-if="plots.length === 0">
            No current plots. Staff is slacking!
        </div>
        <table class="stack" v-else>
            <thead>
            <tr>
                <th>
                    Title
                </th>
                <th>
                    Run By
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="plot in plots" :key="plot.id">
                <td>
                    <a href="/plots/view/{{ plot.slug }}">{{plot.name}}</a>
                </td>
                <td>
                    {{ plot.run_by.username }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "PlotSummary",
        data: function () {
            return {
                plots: null
            }
        },
        methods: {
            async fetchRequestSummary() {
                const result = await axios.get('/plots/summary.json');
                this.plots = result.data.plots;
            }
        },
        async mounted() {
            await this.fetchRequestSummary();
        },
        props: ['showNew']
    }
</script>

<style scoped>

</style>
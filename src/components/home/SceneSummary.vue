<template>
    <div>
        <h3 class="float-left" style="clear: both;">Scenes</h3>
        <div class="button-group float-right" v-if="this.isLoggedIn">
            <a class="button small" href="/scenes/add">New</a>
        </div>
        <div v-if="scenes === null" class="clearfix">
            Loading...
        </div>
        <div v-else-if="scenes.length === 0" class="clearfix">
            No Upcoming Scenes
        </div>
        <table class="stack" v-else>
            <thead>
            <tr>
                <th>Scene</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="scene in scenes" :key="scene.id">
                <td>
                    <a href="/scenes/view/{{ scene.slug }}">{{ scene.name }}</a>
                </td>
                <td>
                    {{ scene.run_on_date | dateTime }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from 'axios';
    import dateTimeFilter from '../../filters/DateTimeFilter'

    export default {
        name: "SceneSummary",
        data: function () {
            return {
                scenes: null
            }
        },
        methods: {
            async fetchSceneSummary() {
                const result = await axios.get('/scenes/summary.json');
                this.scenes = result.data.scenes;
            }
        },
        async mounted() {
            await this.fetchSceneSummary();
        },
        props: {
            isLoggedIn: Boolean
        }
    }
</script>

<style scoped>

</style>
<template>
    <div>
        <div v-if="requests.length === 0">
            <div class="grid-x clearfix">
                <div class="small-12 cell">
                    Loading...
                </div>
            </div>
        </div>
        <table class="stack" v-if="requests.length > 0">
            <thead>
            <tr>
                <th>Request</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="request in requests" :key="request.id">
                <td>
                    <a href="/requests/view/{{ request.id }}">{{request.title}}</a>
                </td>
                <td>
                    {{ request.request_status.name}}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "RequestsSummary",
        data: function () {
            return {
                requests: []
            }
        },
        methods: {
            async fetchRequestSummary() {
                const result = await axios.get('/requests/summary.json');
                this.requests = result.data.requests;
            }
        },
        async mounted() {
            await this.fetchRequestSummary();
        },
    }
</script>

<style scoped>

</style>
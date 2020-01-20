<template>
    <div>
        <h3 class="float-left">Requests</h3>
        <div class="button-group float-right" v-if="this.isLoggedIn">
            <a class="button small" href="/requests/add">New</a>
        </div>
        <div v-if="!this.isLoggedIn" class="clearfix">
            You need to <a href="/forum/ucp.php?mode=login&redirect=/">Sign in</a> or <a
                href="/forum/ucp.php?mode=register&redirect=/">Register</a>.
        </div>
        <div v-else>
            <div v-if="requests === null" class="clearfix">
                Loading...
            </div>
            <table class="stack" v-else>
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
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "RequestsSummary",
        data: function () {
            return {
                requests: null
            }
        },
        methods: {
            async fetchRequestSummary() {
                const result = await axios.get('/requests/summary.json');
                this.requests = result.data.requests;
            }
        },
        async mounted() {
            console.log(typeof this.isLoggedIn);
            if(this.isLoggedIn) {
                await this.fetchRequestSummary();
            }
        },
        props: {
            isLoggedIn: Boolean
        }
    }
</script>

<style scoped>

</style>
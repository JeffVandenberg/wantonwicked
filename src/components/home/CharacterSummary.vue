<template>
    <div v-if="this.isLoggedIn" id="character-summary">
        <h3 class="float-left">
            Characters
        </h3>
        <div class="button-group float-right">
            <a class="button small" href="/characters/add">New</a>
        </div>
        <table>
            <thead>
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Actions
                </th>
            </tr>
            </thead>
            <tbody>

            <tr v-for="character in characters" :key="character.id">
                <td>
                    <div class="badge has-tip" data-tooltip title="New" v-if="character.character_status_id === 1">
                        <i class="fi-x"/>
                    </div>
                    <div class="success badge has-tip" data-tooltip title="Sanctioned (Active)"
                         v-if="character.character_status_id === 2">
                        <i class="fi-check"/>
                    </div>
                    <div class="warning badge has-tip" data-tooltip title="Sanctioned (Idle)"
                         v-if="character.character_status_id === 6">
                        <i class="fi-check"/></div>
                    <div class="secondary badge has-tip" data-tooltip
                         title="Sanctioned (Inactive)" v-if="character.character_status_id === 4">
                        <i class="fi-check"/>
                    </div>
                    <div class="alert badge has-tip" data-tooltip title="Desanctioned"
                         v-if="character.character_status_id === 3">
                        <i class="fi-x"/>
                    </div>
                    {{ character.character_name }}
                </td>
                <td>
                    <div class="button-group float-right">
                        <a :href="'/character.php?action=interface&character_id=' + character.id "
                           target="_blank" class="button float-right">Interface
                        </a>
                        <button class="dropdown button arrow-only" type="button"
                                :data-toggle="character.id +'-dropdown'">
                            <span class="show-for-sr">Show menu</span>
                        </button>
                        <div class="dropdown-pane bottom right"
                             :id="character.id +'-dropdown'"
                             data-dropdown data-auto-focus="true">
                            <ul class="vertical menu">
                                <li>
                                    <a :href="'/characters/viewOwn/' + character.slug">Sheet</a>
                                </li>
                                <li v-if="[2,4,6].includes(character.character_status_id)">
                                    <a :href="'/characters/beats/' + character.slug">
                                        Beats
                                    </a>
                                </li>
                                <li>
                                    <a :href="'/requests/character/' + character.slug">Requests</a>
                                </li>
                                <li>
                                    <a :href="'/dieroller.php?action=character&character_id=' + character.id">Diceroller</a>
                                </li>
                                <li>
                                    <a :href="'/chat?character_id=' + character.id" target="_blank">Chat</a>
                                </li>
                                <li>
                                    <a :href="'/wiki/Players/' + character.character_name.replace(/[^A-Za-z0-9]/g, '')">
                                        Profile
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "CharacterSummary",
        data: function () {
            return {
                characters: null
            }
        },
        methods: {
            async fetchRequestSummary() {
                const result = await axios.get('/characters/summary.json');
                this.characters = result.data.characters;
            }
        },
        async mounted() {
            await this.fetchRequestSummary();
        },
        updated() {
            this.characters.forEach(c => {
                new Foundation.Dropdown($('#' + c.id + '-dropdown'));
            });
            console.log('updated ui');
        },
        props: {
            isLoggedIn: Boolean
        }
    }
</script>

<style scoped>

</style>
<?php
$page_title = 'Create V2 Character';

ob_start();
?>
    <div id="character-sheet-tabs">
        <ul>
            <li><a href="#main">Bio</a></li>
            <li><a href="#aspirations">Aspirations</a></li>
            <li><a href="#powers">Powers</a></li>
            <li><a href="#stats">Stats</a></li>
            <li><a href="#morality">Morality</a></li>
            <li><a href="#history">History</a></li>
            <li><a href="#equipment">Equipment</a></li>
            <li><a href="#conditions">Conditions</a></li>
        </ul>
        <div id="main">
            Main Info Here
        </div>
        <div id="aspirations">
            <strong>These are taggable for Beats</strong>
            <table>
                <tr>
                    <td>First Aspiration</td>
                    <td><a href="">Remove</a></td>
                </tr>
                <tr>
                    <td>Second Aspiration</td>
                    <td><a href="">Remove</a>
                </tr>
                <tr>
                    <td>Third Aspiration</td>
                    <td><a href="">Remove</a>
                </tr>
                <tr>
                    <td><a href="#">Add Aspiration</a></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div id="stats">
            Attributes & Skills Here
        </div>
        <div id="powers">
            Backgrounds & Powers Here
        </div>
        <div id="morality">
            <div><strong>Integrity:</strong> 10</div>
            <br />
            <div>
                <strong>Breaking Points</strong>
                <dl>
                    <dt>What is the worst thing your character has ever done?</dt>
                    <dd>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet, aperiam.</dd>
                    <dt>What is the worst thing your character can imagine himself doing?</dt>
                    <dd>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam, ullam.</dd>
                    <dt>What is the worst thing your character can imagine someone else doing?</dt>
                    <dd>Deserunt dolor labore nostrum quia quisquam, recusandae repudiandae totam unde.</dd>
                    <dt>What has the character forgotten?</dt>
                    <dd>Accusantium animi assumenda blanditiis enim est impedit sint voluptas voluptate.</dd>
                    <dt>What is the most traumatic thing that has ever happened to your character?</dt>
                    <dd>Esse eum harum laborum magnam modi numquam quis sint! Deleniti?</dd>
                    <dt>Other</dt>
                    <dd>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo, sint!</dd>
                </dl>
            </div>
        </div>
        <div id="history">
            History
        </div>
        <div id="equipment">
            <table>
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Effect</th>
                </tr>
                </thead>
                <tr>
                    <td>Gun</td>
                    <td>+4L</td>
                </tr>
                <tr>
                    <td>Computer</td>
                    <td>+2</td>
                </tr>
                <tr><td colspan="2"><a href="#">Add Equipment</a></td></tr>
            </table>
        </div>
        <div id="conditions">
            <table>
                <thead>
                <tr>
                    <th>Condition</th>
                    <th>Resolution</th>
                    <th>Beat</th>
                    <th></th>
                </tr>
                </thead>
                <tr>
                    <td>Amnesia (Persistent)</td>
                    <td>Regain memory</td>
                    <td>Something problematic arises</td>
                    <td>Claim Beat</td>
                </tr>
                <tr>
                    <td>Bonded</td>
                    <td>Bonded animal dies or leaves the character</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr><td colspan="4"><a href="#">Add Condition</a></td></tr>
            </table>
        </div>
    </div>
    <script>
        $(function () {
            $("#character-sheet-tabs").tabs();
        });
    </script>
<?php
$page_content = ob_get_clean();

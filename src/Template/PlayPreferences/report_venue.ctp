<?php
use App\View\AppView;

/* @var AppView $this */
/* @var array $report */
/* @var string $venue */

$this->set('title_for_layout', 'Venue Player Preference Report');
?>
<div class="row">
    <div class="small-6 column">
        <label for="character_type" style="display: inline;">Character Type</label>
        <?php echo $this->Form->select('character_type', $characterTypes, [
                'value' => ucfirst($venue),
                'empty' => false,
                'id' => 'character_type',
            ]
        ); ?>
    </div>
    <div class="small-6 column">
        <label for="play_preference_id" style="display: inline;">Preference</label>
        <?php echo $this->Form->select('play_preference_id', $playPreferences, [
                'value' => $playPreferenceId,
                'empty' => false,
                'id' => 'play_preference_id',
            ]
        ); ?>
    </div>
</div>
<table class="stack">
    <thead>
    <tr>
        <th>
            Venue
        </th>
        <th>
            Preference
        </th>
        <th>
            Percent Interested
        </th>
        <th>

        </th>
    </tr>
    </thead>
    <?php foreach($report as $row): ?>
        <tr>
            <td>
                <?php echo $row['character_type']; ?>
            </td>
            <td>
                <?php echo $row['name']; ?>
            </td>
            <td>
                <?php if($row['total'] > 0): ?>
                    <?php echo (int) (($row['hits'] / $row['total']) * 100); ?>
                    (
                    <?php echo $row['total']; ?>
                    Responses
                    )
                <?php else: ?>
                    No Responses
                <?php endif; ?>
            </td>
            <td>
                <?php echo $this->Html->link('View Players', [
                        'action' => 'report_venue_players',
                        $row['character_type'],
                        $row['slug']
                    ]); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $(function() {
        $("#character_type").change(function() {
            document.location = '/play-preferences/report-venue/' + $(this).val().toLowerCase() + '/' +
                    $("#play_preference_id").val();
        });
        $("#play_preference_id").change(function() {
            document.location = '/play-preferences/report-venue/' + $('#character_type').val().toLowerCase() + '/' +
                    $(this).val();
        });
    });
</script>

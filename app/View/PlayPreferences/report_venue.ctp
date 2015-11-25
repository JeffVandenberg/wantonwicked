<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Venue Player Preference Report'); ?>
<?php /* @var array $report */ ?>

<div style="text-align: center;padding: 10px 0;">
    <strong>Filters:</strong>
    <label for="character_type" style="display: inline;">Character Type</label>
    <?php echo $this->Form->select('character_type', $characterTypes, array('value' => ucfirst($venue),
            'empty' => false,
        )
    ); ?>
    <label for="play_preference_id" style="display: inline;">Preference</label>
    <?php echo $this->Form->select('play_preference_id', $playPreferences, array('value' => $playPreferenceId,
            'empty' => false,
        )
    ); ?>
</div>
<table>
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
                <?php echo $row['C']['character_type']; ?>
            </td>
            <td>
                <?php echo $row['PP']['name']; ?>
            </td>
            <td>
                <?php if($row[0]['total'] > 0): ?>
                    <?php echo (int) (($row[0]['hits'] / $row[0]['total']) * 100); ?>
                    (
                    <?php echo $row[0]['total']; ?>
                    Responses
                    )
                <?php else: ?>
                    No Responses
                <?php endif; ?>
            </td>
            <td>
                <?php echo $this->Html->link('View Players', [
                        'action' => 'report_venue_players',
                        $row['C']['character_type'],
                        $row['PP']['name']
                    ]); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script>
    $(function() {
        $("#character_type").change(function() {
            document.location = '/play_preferences/report_venue/' + $(this).val().toLowerCase() + '/' +
                    $("#play_preference_id").val();
        });
        $("#play_preference_id").change(function() {
            document.location = '/play_preferences/report_venue/' + $('#character_type').val().toLowerCase() + '/' +
                    $(this).val();
        });
    });
</script>

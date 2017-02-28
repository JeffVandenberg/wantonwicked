<?php
use classes\character\data\Character;
use classes\character\data\CharacterBeat;
use classes\character\data\CharacterBeatRecord;

/* @var View $this */
/* @var Character $character */
/* @var array $beatList */
/* @var CharacterBeatRecord $currentBeatStatus */
/* @var CharacterBeat[] $pastBeats */
$this->set('title_for_layout', 'Beats for ' . $character->CharacterName);
?>
<form method="post">
    <div class="row align-top">
        <div class="small-12 column subheader">
            New Beat
        </div>
        <div class="small-12 medium-3 column">
            <label>
                Type
                <?php echo $this->Form->select('beat_type_id', $beatList, [
                    'empty' => false
                ]); ?>
            </label>
        </div>
        <div class="small-12 medium-9 column">
            <label>
                Note
                <?php echo $this->Form->textarea('note', [
                    'rows' => 5
                ]); ?>
            </label>
        </div>
        <div class="small-12 column text-center">
            <button type="submit" value="save" class="button">Save Beat</button>
        </div>
    </div>
</form>
<div class="row">
    <div class="small-12 column">
        Beat Summary for month: <strong><?php echo date('F, Y', strtotime($currentBeatStatus->RecordMonth)); ?></strong>
        XP Earned: <strong><?php echo (float)$currentBeatStatus->ExperienceEarned; ?></strong>
        <?php if ($currentBeatStatus->ExperienceEarned >= 2): ?>
            <strong>At Max Experience for the month!</strong>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="small-12 column subheader">
        Past Beats
    </div>
    <div class="smal-12 column">
        <table class="stack">
            <thead>
            <tr>
                <th>
                    Type
                </th>
                <th>
                    Note
                </th>
                <th>
                    Awarded By
                </th>
                <th>
                    Awarded On
                </th>
                <th>
                    Status
                </th>
            </tr>
            </thead>
            <?php foreach ($pastBeats as $characterBeat): ?>
                <tr class="clickable beat-detail" data-beat-id="<?php echo $characterBeat->Id; ?>"
                    title="Click for Detail">
                    <td>
                        <div>
                            <?php echo $characterBeat->BeatType->Name; ?>
                        </div>
                    </td>
                    <td>
                        <?php if (strlen($characterBeat->Note) > 40): ?>
                            <?php echo substr($characterBeat->Note, 0, 40); ?>...
                        <?php else: ?>
                            <?php echo $characterBeat->Note; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $characterBeat->CreatedBy->Username; ?></td>
                    <td><?php echo $characterBeat->Created; ?></td>
                    <td><?php echo $characterBeat->BeatStatus->Name; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<div class="reveal" id="detail-modal" data-reveal>
    <div id="detail-modal-content"></div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<script>
    $(function () {
        $(".beat-detail").click(function () {
            var $modal = $("#detail-modal");

            $.get('/beats/viewDetails/' + $(this).data().beatId, function (response) {
                $("#detail-modal-content").html(response);
                $modal.foundation('open');
            });
        });
    })
</script>

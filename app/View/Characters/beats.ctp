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
        <?php if($currentBeatStatus->ExperienceEarned >= 2): ?>
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
                    Awarded By
                </th>
                <th>
                    Awarded On
                </th>
                <th>
                    Status
                </th>
                <th>
                    Applied On
                </th>
                <th>
                    Beats Awarded
                </th>
            </tr>
            </thead>
            <?php foreach($pastBeats as $characterBeat): ?>
                <tr>
                    <td>
                        <div class="clickable" data-toggle="beat-note-<?php echo $characterBeat->Id; ?>" title="Click for Note">
                            <?php echo $characterBeat->BeatType->Name; ?>
                        </div>
                        <div class="hide" id="beat-note-<?php echo $characterBeat->Id; ?>" data-toggler=".hide">
                            <strong>Note:</strong> <?php echo $characterBeat->Note; ?>
                        </div>
                    </td>
                    <td><?php echo $characterBeat->CreatedBy->Username; ?></td>
                    <td><?php echo $characterBeat->Created; ?></td>
                    <td><?php echo $characterBeat->BeatStatus->Name; ?></td>
                    <td><?php echo $characterBeat->AppliedOn; ?></td>
                    <td><?php echo $characterBeat->BeatsAwarded; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

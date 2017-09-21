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
            <button type="submit" value="save" class="button" id="save-beat-button">Save Beat</button>
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
    <div id="beat-content" class="small-12 column">
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
                <?php /* @var \App\Model\Entity\CharacterBeat $characterBeat */?>
                <tr class="clickable beat-detail" data-beat-id="<?php echo $characterBeat->id; ?>"
                    title="Click for Detail">
                    <td>
                        <div>
                            <?php echo $characterBeat->beat_type->name; ?>
                        </div>
                    </td>
                    <td>
                        <?php if (strlen($characterBeat->note) > 40): ?>
                            <?php echo substr($characterBeat->note, 0, 40); ?>...
                        <?php else: ?>
                            <?php echo $characterBeat->note; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $characterBeat->created_by->username; ?></td>
                    <td><?php echo $characterBeat->created; ?></td>
                    <td><?php echo $characterBeat->beat_status->name; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="paginator small callout">
            <ul class="pagination">
                <?php if ($this->Paginator->hasPrev()): ?>
                    <?= $this->Paginator->first('<< ' . __('First')) ?>
                    <?= $this->Paginator->prev('< ' . __('Previous')) ?>
                <?php endif; ?>
                <?= $this->Paginator->numbers() ?>
                <?php if ($this->Paginator->hasNext()): ?>
                    <?= $this->Paginator->next(__('Next') . ' >') ?>
                    <?= $this->Paginator->last(__('Last') . ' >>') ?>
                <?php endif; ?>
            </ul>
            <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
        </div>
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
        $("form").submit(function() {
            $("#save-beat-button").attr('disabled', true);
        });
        $(".beat-detail").click(function () {
            var $modal = $("#detail-modal");

            $.get('/beats/viewDetails/' + $(this).data().beatId, function (response) {
                $("#detail-modal-content").html(response);
                $modal.foundation('open');
            });
        });

        $(document).on('click', '.pagination a, #content-table thead a', function () {
            var target = $(this).attr('href');

            $.get(target, function (data) {
                $('#beat-content').html($(data).find("#beat-content").html());
                var state = {html: 'doTo'};
                window.history.pushState(state, 'Character Beats', target);

            }, 'html');

            return false;
        });

    })
</script>

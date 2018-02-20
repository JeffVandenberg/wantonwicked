<div class="row">
    <div class="small-12 columns">
        <label>Title:</label>
        <?= $request->title; ?>
    </div>
    <div class="small-12 medium-4 columns">
        <label>Group:</label>
        <?= $request->group->name; ?>
    </div>
    <div class="small-12 medium-4 columns">
        <label>Request Type:</label>
        <?= $request->request_type->name; ?>
    </div>
    <div class="small-12 medium-4 columns">
        <label>Request Status:</label>
        <?= $request->request_status->name; ?>
    </div>
    <div class="small-12 columns">
        <label>Request:</label>
        <div class="tinymce-content">
            <?= $request->body; ?>
        </div>
    </div>
    <div class="small-12 medium-4 columns">
        <label>Created On:</label>
        <?= $this->Time->format($request->created_on); ?>
    </div>
    <div class="small-12 medium-4 columns">
        <label>Updated On:</label>
        <?= $this->Time->format($request->updated_on); ?>
    </div>
    <div class="small-12 medium-4 columns">
        <label>Updated By:</label>
        <?= $request->updated_by->username; ?>
    </div>
</div>
<div class="row">
    <?php if (count($request->request_characters) > 0): ?>
        <div class="small-12 columns">
            <h4>Attached Characters</h4>
            <div class="row align-top">
                <?php foreach ($request->request_characters as $character): ?>
                    <div class="small-12 medium-6 large-4 columns">
                        <label>Character</label>
                        <?php if ($character->is_primary): ?>
                            <strong><?= $character->character->character_name; ?></strong>
                        <?php else: ?>
                            <?= $character->character->character_name; ?>
                        <?php endif; ?>
                        <br/>
                        <label>Note</label>
                        <div class="tinymce-content">
                            <?php if ($character->note): ?>
                                <?= $character->note; ?>
                            <?php else: ?>
                                None
                            <?php endif; ?>
                            <br/>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    <?php endif; ?>
    <?php if (count($request->request_rolls)): ?>
        <div class="small-12 medium-4 columns">
            <h4>Supporting Rolls</h4>
            <?php foreach ($request->request_rolls as $roll): ?>
                <?= $this->Html->link(
                    $roll->roll->Description . ' (' . $roll->roll->Num_of_Successes . ' Successes)',
                    '/dieroller.php?action=view_roll&r=' . $roll->roll_id,
                    [
                        'class' => 'ajax-link'
                    ]
                ); ?>
                <br/>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (count($request->request_requests) > 0): ?>
        <div class="small-12 medium-4 columns">
            <h4>Supporting Requests</h4>
            <?php foreach ($request->request_requests as $linkedRequest): ?>
                <?= $this->Html->link(
                    $linkedRequest->from_request->title,
                    ['action' => 'view', $linkedRequest->from_request->id],
                    [
                        'class' => 'ajax-link'
                    ]
                ); ?>
                <br/>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (count($request->request_bluebooks) > 0): ?>
        <div class="small-12 medium-4 columns">
            <h4>Supporting Bluebooks</h4>
            <?php foreach ($request->request_bluebooks as $bluebook): ?>
                <?= $this->Html->link(
                    $bluebook->bluebook->title,
                    '/bluebook.php?action=view&bluebook_id=' . $bluebook->bluebook_id,
                    [
                        'class' => 'ajax-link'
                    ]
                ); ?>
                <br/>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (count($request->scene_requests) > 0): ?>
        <div class="small-12 medium-4 columns">
            <h4>Supporting Scenes</h4>
            <?php foreach ($request->scene_requests as $scene): ?>
                <?= $this->Html->link(
                    $scene->scene->name,
                    ['controller' => 'scenes', 'action' => 'view', $scene->scene->slug],
                    [
                        'class' => 'ajax-link'
                    ]
                ); ?>
                <div class="tinymce-content">
                    <?= $scene->note; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="small-12 columns">
        <h3>
            Notes
        </h3>
        <?php if (count($request->request_notes) > 0): ?>
            <?php foreach ($request->request_notes as $note): ?>
                <strong>
                    <?= $note->created_by->username; ?>
                    wrote on
                    <?= $this->Time->format($note->created_on); ?>
                </strong>
                <div class="tinymce-content">
                    <?= $note->note; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            No notes for this request.
        <?php endif; ?>
    </div>
</div>
<div id="modal-subview" class="reveal" data-reveal>
    <div id="modal-subview-content"></div>
    <button class="close-button" data-close aria-label="Close" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

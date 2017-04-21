<?php
use App\View\AppView;
use classes\character\data\CharacterBeat;

/* @var AppView $this */
/* @var CharacterBeat $beat */
?>

<?php if (isset($message)): ?>
    <div class="error"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (isset($beat)): ?>
    <div class="row">
        <div class="small-4 column">
            <strong>Beat Type</strong>
        </div>
        <div class="small-8 column">
            <?php echo $beat->BeatType->Name; ?>
        </div>
        <div class="small-12 column">
            <strong>Note</strong><br>
            <?php echo $beat->Note; ?>
        </div>
        <div class="small-4 column">
            <strong>Status</strong>
        </div>
        <div class="small-8 column">
            <?php echo $beat->BeatStatus->Name; ?>
        </div>
        <div class="small-4 column">
            <strong>Awarded By</strong>
        </div>
        <div class="small-8 column">
            <?php echo $beat->CreatedBy->Username; ?>
        </div>
        <div class="small-4 column">
            <strong>Awarded On</strong>
        </div>
        <div class="small-8 column">
            <?php echo $beat->Created; ?>
        </div>
        <div class="small-4 column">
            <strong>Beats Awarded</strong>
        </div>
        <div class="small-8 column">
            <?php echo $beat->BeatsAwarded; ?>
        </div>
    </div>
<?php endif; ?>

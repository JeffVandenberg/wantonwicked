<?php
use classes\character\data\Character;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/12/15
 * Time: 11:27 PM
 * @property HtmlHelper Html
 * @property FormHelper Form
 * @property LanguageHelper Language
 */
class CharacterHelper extends AppHelper
{
    public $helpers = [
        'Html',
        'Form',
        'Language'
    ];

    public function edit(Character $character = null)
    {
        if ($character == null) {
            $character = new Character();
        }

        $character->CharacterType = ($character->CharacterType) ? strtolower($character->CharacterType) : "mortal";
        $bio = $this->buildBioEdit($character);


        $sheet = <<<HTML
<ul class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true">
    <li class="accordion-item is-active">
        $bio
    </li>
</ul>
HTML;
        return $sheet;
    }

    private function buildBioEdit(Character $character)
    {
        $characterTypes = [
            'changeling' => 'Changeling',
            'fae-touched' => 'Fae-Touched',
            'mage' => 'Mage',
            'mortal' => 'Mortal',
            'vampire' => 'Vampire',
            'ghoul' => 'Ghoul',
            'werewolf' => 'Werewolf',
            'wolfblooded' => 'Wolfblooded'
        ];

        ob_start();
        ?>
<a href="#csheet-bio" role="tab" class="accordion-title" id="csheet-bio-heading" aria-controls="csheet-bio">Character
    Dossier</a>
<div id="csheet-bio" class="accordion-content" role="tabpanel" data-tab-content aria-labelledby="csheet-bio-heading">
    <div class="row">
        <div class="medium-12 columns subheader">Demographics</div>
    </div>
    <div class="row">
        <div class="medium-1 columns">
            <label for="character_name">Name</label>
        </div>
        <div class="medium-7 columns">
            <?php echo $this->Form->input('character_name', ['value' => $character->CharacterName, 'label' => false]); ?>
        </div>
        <div class="medium-1 columns">
            <label for="character_type">Type</label>
        </div>
        <div class="medium-3 columns">
            <?php echo $this->Form->select(
                'character_type',
                $characterTypes,
                [
                    'value' => $character->CharacterType,
                    'empty' => false,
                    'label' => false,
                    'div' => false
                ]);
            ; ?>
        </div>
    </div>
    <div class="row">
        <div class="medium-1 columns">
            <label for="chronicle">Chronicle</label>
        </div>
        <div class="medium-3 columns">
            <select id="chronicle">
                <option value="ww5">Portland, OR</option>
            </select>
        </div>
        <div class="medium-1 columns medium-offset-4">
            <label for="character_age">Age</label>
        </div>
        <div class="medium-3 columns">
            <input id="character_age" type="text" placeholder="Age (Apparent)">
        </div>
    </div>
    <div class="row">
        <div class="medium-1 columns">
            <label for="character_concept">Concept</label>
        </div>
        <div class="medium-11 columns">
            <input id="character_concept" type="text" placeholder="Character Concept">
        </div>
    </div>
    <div class="row">
        <div class="medium-12 columns subheader">Faction Affiliations</div>
    </div>
    <div class="row">
        <div class="medium-1 columns">
            <label for="splat1">
                <?php echo $this->Language->translate('splat1', $character->CharacterType); ?>
            </label>
        </div>
        <div class="medium-3 columns">
            <?php echo $this->Form->input(
                'splat1',
                [
                    'value' => $character->Splat1,
                    'placeholder' => 'Guild',
                    'label' => false,
                    'div' => false
                ]
            ); ?>
        </div>
        <div class="medium-8 columns"></div>
    </div>
    <div class="row">
        <div class="medium-12 columns subheader">Character Background</div>
    </div>
    <div class="row">
        <div class="medium-12 columns">
            <label for="history">Biography</label>
            <?php echo $this->Form->textarea(
                'history',
                [
                    'rows' => 6,
                    'placeholder' => 'None',
                    'label' => 'Biography',
                    'value' => $character->History,
                ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="medium-12 columns">
            <label>Notes</label>
            <?php echo $this->Form->textarea(
                'notes',
                [
                    'rows' => 6,
                    'placeholder' => "At Character Creation this section should include a list of all traits or bonuses provided by purchase of applicable merits, abilities or by character type choices. Those bonuses will be added to the sheet by the sanctioning staff.",
                    'label' => 'Notes',
                    'value' => $character->CharacterNotes
                ]
            );
            ?>
        </div>
    </div>
</div>
<?php
        return ob_get_clean();
    }
}

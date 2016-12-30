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

    private $maxDots = 9;
    private $skills = [
        'mental' => [
            'academics' => 'Academics',
            'computer' => 'Computer',
            'crafts' => 'Crafts',
            'investigation' => 'Investigation',
            'medicine' => 'Medicine',
            'occult' => 'Occult',
            'politics' => 'Politics',
            'science' => 'Science'
        ],
        'physical' => [
            'athletics' => 'Athletics',
            'brawl' => 'Brawl',
            'drive' => 'Drive',
            'firearms' => 'Firearms',
            'larceny' => 'Larceny',
            'stealth' => 'Stealth',
            'survival' => 'Survival',
            'weaponry' => 'Weaponry'
        ],
        'social' => [
            'animalken' => 'Animal Ken',
            'empathy' => 'Empathy',
            'expression' => 'Expression',
            'intimidation' => 'Intimidation',
            'persuasion' => 'Persuasion',
            'socialize' => 'Socialize',
            'streetwise' => 'Streetwise',
            'subterfuge' => 'Subterfuge'
        ]
    ];

    function __construct(View $view, $settings = [])
    {
        parent::__construct($view, $settings);
        $list = array_merge($this->skills['mental'], $this->skills['physical'], $this->skills['social']);
        $keys = array_values($list);
        $this->skillList = array_combine($keys, $list);
    }

    public function edit(Character $character = null)
    {
        if ($character == null) {
            $character = new Character();
            $character->initializeNew();
        }

        $character->CharacterType = ($character->CharacterType) ? strtolower($character->CharacterType) : "mortal";
        $bio = $this->buildBioEdit($character);
        $stats = $this->buildStatEdit($character);
        $abilities = $this->buildAbilitiesSection($character);

        $sheet = <<<HTML
<ul id="character-form-edit" class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true">
    <li class="accordion-item">
        $bio
    </li>
    <li class="accordion-item">
        $stats
    </li>
    <li class="accordion-item">
        $abilities
    </li>
    <li class="accodion-item">
        <a href="#csheet-equipment" role="tab" class="accordion-title" id="csheet-equipment-heading"
           aria-controls="csheet-equipment">Equipment</a>
        <div id="csheet-equipment" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-equipment-heading">
        </div>
    </li>
    <li class="accodion-item">
        <a href="#csheet-conditions" role="tab" class="accordion-title" id="csheet-conditions-heading"
           aria-controls="csheet-conditions">Conditions</a>
        <div id="csheet-conditions" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-conditions-heading">
        </div>
    </li>
    <li class="accodion-item">
        <a href="#csheet-experiences" role="tab" class="accordion-title" id="csheet-experiences-heading"
           aria-controls="csheet-experiences">Experiences</a>
        <div id="csheet-experiences" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-experiences-heading">
        </div>
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
        <a href="#csheet-bio" role="tab" class="accordion-title" id="csheet-bio-heading" aria-controls="csheet-bio">Dossier</a>
        <div id="csheet-bio" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-bio-heading">
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
                        ]);; ?>
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
                    <?php echo $this->Form->input('character_age', [
                        'value' => $character->Age,
                        'placeholder' => 'Age (Apparent)',
                        'label' => false,
                        'empty' => false
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="medium-1 columns">
                    <label for="character_concept">Concept</label>
                </div>
                <div class="medium-11 columns">
                    <?php
                    echo $this->Form->input('character_concept', [
                        'value' => $character->Concept,
                        'placeholder' => 'Character Concept',
                        'label' => false
                    ]);
                    ?>
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
                <div class="small-12 columns subheader">
                    Anchors
                </div>
            </div>
            <div class="row">
                <div class="medium-1 column">
                    <label for="virtue">Virtue</label>
                </div>
                <div class="medium-3 column">
                    <?php
                    echo $this->Form->input('virtue', [
                        'value' => $character->Virtue,
                        'label' => false,
                        'placeholder' => 'Virtue'
                    ]);
                    ?>
                </div>
                <div class="medium-1 column">
                    <label for="vice">Vice</label>
                </div>
                <div class="medium-3 column">
                    <?php
                    echo $this->Form->input('vice', [
                        'value' => $character->Vice,
                        'label' => false,
                        'placeholder' => 'Vice'
                    ]);
                    ?>
                </div>
                <div class="medium-4 column">&nbsp;</div>
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
                            'label' => 'Notes',
                            'value' => $character->CharacterNotes,
                            'aria-describedby' => 'notes-help-text'
                        ]
                    );
                    ?>
                    <p class="help-text" id="notes-help-text">At Character Creation this section should include a list
                        of all traits or bonuses provided by purchase of applicable merits, abilities or by character
                        type choices. Those bonuses will be added to the sheet by the sanctioning staff.</p>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function buildStatEdit(Character $character)
    {
        ob_start();
        ?>
        <a href="#csheet-stats" role="tab" class="accordion-title" id="csheet-stats-heading"
           aria-controls="csheet-stats">Stats</a>
        <div id="csheet-stats" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-stats-heading">
            <div class="row">
                <div class="small-12 column subheader">
                    Attributes
                </div>
            </div>
            <div class="row">
                <div class="small-3 medium-3 column">
                    <label for="attributeIntelligence">
                        Intelligence
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.intelligence', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'intelligence')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeStrength">
                        Strength
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.strength', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'strength')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributePresence">
                        Presence
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.presence', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'presence')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="small-3 medium-3 column">
                    <label for="attributeWits">
                        Wits
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.wits', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'wits')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeDexterity">
                        Dexterity
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.dexterity', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'dexterity')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeManipulation">
                        Manipulation
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.manipulation', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'manipulation')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="small-3 medium-3 column">
                    <label for="attributeResolve">
                        Resolve
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.resolve', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'resolve')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeStamina">
                        Stamina
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.stamina', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'stamina')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeComposure">
                        Composure
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php echo $this->Form->select('attribute.composure', range(0, $this->maxDots), [
                        'value' => $character->getPowerByTypeAndName('attribute', 'composure')->PowerLevel,
                        'empty' => false
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="small-12 medium-7 column">
                    <div class="row">
                        <div class="small-12 medium-4 column">
                            <div class="row">
                                <div class="small-12 column subheader">
                                    Mental Skills
                                </div>
                            </div>
                            <div class="row">
                                <?php foreach ($this->skills['mental'] as $key => $label): ?>
                                    <div class="small-7 column">
                                        <label for="skill<?php echo ucfirst($key); ?>"><?php echo $label; ?></label>
                                    </div>
                                    <div class="small-5 column">
                                        <?php echo $this->Form->select('skill.' . $key, range(0, $this->maxDots), [
                                            'value' => $character->getPowerByTypeAndName('skill', $key)->PowerLevel,
                                            'empty' => false
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="small-12 medium-4 column">
                            <div class="row">
                                <div class="small-12 column subheader">
                                    Physical Skills
                                </div>
                            </div>
                            <div class="row">
                                <?php foreach ($this->skills['physical'] as $key => $label): ?>
                                    <div class="small-7 column">
                                        <label for="skill<?php echo ucfirst($key); ?>"><?php echo $label; ?></label>
                                    </div>
                                    <div class="small-5 column">
                                        <?php echo $this->Form->select('skill.' . $key, range(0, $this->maxDots), [
                                            'value' => $character->getPowerByTypeAndName('skill', $key)->PowerLevel,
                                            'empty' => false
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="small-12 medium-4 column">
                            <div class="row">
                                <div class="small-12 column subheader">
                                    Social Skills
                                </div>
                            </div>
                            <div class="row">
                                <?php foreach ($this->skills['social'] as $key => $label): ?>
                                    <div class="small-7 column">
                                        <label for="skill<?php echo ucfirst($key); ?>"><?php echo $label; ?></label>
                                    </div>
                                    <div class="small-5 column">
                                        <?php echo $this->Form->select('skill.' . $key, range(0, $this->maxDots), [
                                            'value' => $character->getPowerByTypeAndName('skill', $key)->PowerLevel,
                                            'empty' => false
                                        ]); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="specialty-column" class="small-12 medium-5 column">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Specialties
                            <div class="badge success clickable" id="add-specialty"><i class="fi-plus"></i></div>
                        </div>
                    </div>
                    <?php foreach ($character->getPowersByType('specialty') as $i => $specialty): ?>
                        <div class="row">
                            <div class="small-5 medium-5 column">
                                <?php echo $this->Form->select('specialty.' . '.skill', $this->skillList, [
                                    'value' => $specialty->PowerName
                                ]); ?>
                            </div>
                            <div class="small-6 medium-6 column">
                                <?php echo $this->Form->hidden('specialty.' . '.id', [
                                    'value' => $specialty->Id
                                ]); ?>
                                <?php echo $this->Form->input('speciality.' . '.specialty', [
                                    'value' => $specialty->PowerNote,
                                    'label' => false
                                ]); ?>
                            </div>
                            <div class="small-1 medium-1 column">
                                <div class="badge alert clickable remove-specialty"><i class="fi-minus"></i></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function buildAbilitiesSection(Character $character)
    {
        switch (strtolower($character->CharacterType)) {
            case 'mortal':
                return $this->buildMortalTemplateSection($character);
                break;
            default:
                return 'Unknown Character Type: ' . $character->CharacterType;
        }
    }

    public function buildMortalTemplateSection(Character $character)
    {
        ob_start();
        ?>
        <a href="#csheet-template" role="tab" class="accordion-title" id="csheet-template-heading"
           aria-controls="csheet-template">Abilities</a>
        <div id="csheet-template" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-template-heading">
            <div class="row">
                <div class="small-6 medium-3 column">
                    <label for="willpower">Willpower</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->select(
                        'willpower_perm',
                        range(0, $this->maxDots),
                        [
                            'value' => $character->WillpowerPerm,
                            'empty' => false
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="integrity">Integrity</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->select(
                        'morality',
                        range(0, $this->maxDots),
                        [
                            'value' => $character->Morality,
                            'empty' => false
                        ]
                    ); ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

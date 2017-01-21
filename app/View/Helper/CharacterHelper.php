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

    private $yesNoOptions = [
        'N' => 'No',
        'Y' => 'Yes'
    ];

    private $statuses = [
        "Ok" => "Ok",
        "Imprisoned" => "Imprisoned",
        "Hospitalized" => "Hospitalized",
        "Torpored" => "Torpored",
        "Dead" => "Dead"
    ];

    private $games = [
        'portland' => 'Portland, OR'
    ];

    private $options = [
        'edit_mode' => 'none',
        'show_admin' => 'false'
    ];


    function __construct(View $view, $settings = [])
    {
        parent::__construct($view, $settings);
        $list = array_merge($this->skills['mental'], $this->skills['physical'], $this->skills['social']);
        $keys = array_values($list);
        $this->skillList = array_combine($keys, $list);
    }

    public function render(Character $character = null, $options = null)
    {
        if ($character == null) {
            $character = new Character();
            $character->initializeNew();
        }

        $this->options = array_merge($this->options, $options);


        $character->CharacterType = ($character->CharacterType) ? strtolower($character->CharacterType) : "mortal";
        $bio = $this->buildBioEdit($character);
        $stats = $this->buildStatEdit($character);
        $powers = $this->buildPowersSection($character);
        $derived = $this->buildDerivedSection($character);
        $equipment = $this->buildEquipmentSection($character);
        $admin = ($this->options['show_admin']) ? $this->buildAdminSection($character) : '';

        ob_start();
        ?>
        <ul id="character-form-edit" class="accordion" data-accordion data-multi-expand="true"
            data-allow-all-closed="true">
            <li class="accordion-item">
                <?php echo $bio; ?>
            </li>
            <li class="accordion-item">
                <?php echo $stats; ?>
            </li>
            <li class="accordion-item">
                <?php echo $powers; ?>
            </li>
            <li class="accordion-item">
                <?php echo $derived; ?>
            </li>
            <li class="accordion-item">
                <?php echo $equipment; ?>
            </li>
            <li class="accordion-item">
                <a href="#csheet-conditions" role="tab" class="accordion-title" id="csheet-conditions-heading"
                   aria-controls="csheet-conditions">Conditions</a>
                <div id="csheet-conditions" class="accordion-content" role="tabpanel" data-tab-content
                     aria-labelledby="csheet-conditions-heading">
                    <div class="row">
                        <div class="small-12 column">
                            List of your conditions will be here sometime soon.
                        </div>
                    </div>
                </div>
            </li>
            <?php if ($this->options['show_admin']): ?>
                <li class="accordion-item">
                    <?php echo $admin; ?>
                </li>
            <?php endif; ?>
        </ul>
        <div class="row callout">
            <div class="medium-2 column">Current XP</div>
            <div class="medium-2 column"><?php echo $character->CurrentExperience; ?></div>
            <div class="medium-2 column">Current Beats</div>
            <div class="medium-2 column"></div>
            <div class="medium-2 column">Last Login</div>
            <div class="medium-2 column"></div>
            <?php echo $this->Form->hidden('character_id', ['value' => $character->Id]); ?>
        </div>
        <?php
        return ob_get_clean();
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
                    <?php echo $this->Form->input('character_name', [
                        'value' => $character->CharacterName,
                        'label' => false,
                        'required',
                        'div' => false,
                        'data-validator' => 'character_name'
                    ]); ?>
                    <span class="form-error">
                        Character Names are required.
                    </span>
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
                    <?php echo $this->Form->select('city', $this->games, [
                        'label' => false,
                        'value' => $character->City,
                        'empty' => false
                    ]); ?>
                </div>
                <div class="medium-1 columns medium-offset-4">
                    <label for="apparent_age">Age</label>
                </div>
                <div class="medium-3 columns">
                    <?php echo $this->Form->input('apparent_age', [
                        'value' => $character->Age,
                        'placeholder' => 'Age (Apparent)',
                        'label' => false,
                        'empty' => false
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="medium-1 columns">
                    <label for="concept">Concept</label>
                </div>
                <div class="medium-11 columns">
                    <?php
                    echo $this->Form->input('concept', [
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
                <div class="small-12 column subheader">
                    Aspirations
                    <div class="success badge clickable" id="add-aspiration"><i class="fi-plus"></i></div>
                </div>
            </div>
            <div class="row">
                <div class="small-12 column">
                    <table class="stack" id="aspirations">
                        <?php foreach ($character->getPowerList('aspiration') as $i => $power): ?>
                            <tr>
                                <td>
                                    <?php echo $this->Form->input('aspiration.' . $i . '.name', [
                                        'label' => false,
                                        'value' => $power->PowerName
                                    ]); ?>
                                    <?php echo $this->Form->hidden('aspiration.' . $i . '.id', [
                                        'value' => $power->Id
                                    ]); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
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
                    <?php foreach ($character->getPowerList('specialty') as $i => $specialty): ?>
                        <div class="row">
                            <div class="small-5 medium-5 column">
                                <?php echo $this->Form->select('specialty.' . $i . '.name', $this->skillList, [
                                    'value' => $specialty->PowerName
                                ]); ?>
                            </div>
                            <div class="small-6 medium-6 column">
                                <?php echo $this->Form->hidden('specialty.' . $i . '.id', [
                                    'value' => $specialty->Id
                                ]); ?>
                                <?php echo $this->Form->input('specialty.' . $i . '.note', [
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

    private function buildPowersSection(Character $character)
    {
        switch (strtolower($character->CharacterType)) {
            case 'mortal':
                return $this->buildMortalPowersSection($character);
                break;
            default:
                return 'Unknown Character Type: ' . $character->CharacterType;
        }
    }

    public function buildMortalPowersSection(Character $character)
    {
        ob_start();
        ?>
        <a href="#csheet-template" role="tab" class="accordion-title" id="csheet-template-heading"
           aria-controls="csheet-template">Abilities</a>
        <div id="csheet-template" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-template-heading">
            <div class="row">
                <div class="small-12 medium-6 column">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Merits
                            <div class="success badge clickable" id="add-merit"><i class="fi-plus"></i></div>
                        </div>
                    </div>
                    <div class="row">
                        <table id="merits" class="stack">
                            <thead>
                            <tr>
                                <th>Merit</th>
                                <th>Note</th>
                                <th>Level</th>
                                <th>Public</th>
                            </tr>
                            </thead>
                            <?php foreach ($character->getPowerList('merit') as $i => $power): ?>
                                <tr>
                                    <td>
                                        <?php echo $this->Form->input('merit.' . $i . '.name', [
                                            'value' => $power->PowerName,
                                            'placeholder' => 'Merit Name',
                                            'label' => false,
                                            'data-powertype' => 'merit',
                                            'class' => 'character-autocomplete'
                                        ]); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('merit.' . $i . '.note', [
                                            'label' => false,
                                            'value' => $power->PowerNote,
                                            'placeholder' => 'Merit Note'
                                        ]); ?>
                                    </td>
                                    <td>
                                        <label class="hide-for-large-only">Level</label>
                                        <?php echo $this->Form->select('merit.' . $i . '.level', range(0, $this->maxDots, [
                                            'label' => false,
                                            'value' => $power->PowerLevel
                                        ])); ?>
                                        <?php echo $this->Form->hidden('merit.' . $i . '.id', [
                                            'value' => $power->Id
                                        ]); ?>
                                    </td>
                                    <td>
                                        <label class="show-for-small-only">Is Public</label>
                                        <?php echo $this->Form->checkbox('merit.' . $i . '.is_public', [
                                            'label' => false,
                                            'value' => $power->IsPublic
                                        ]); ?>
                                        <div class="alert badge clickable remove-merit"><i class="fi-minus"></i></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <table id="removed-merits" class="hide"></table>
                    </div>
                </div>
                <div class="small-12 medium-6 column">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Misc Abilities
                            <div class="success badge clickable" id="add-misc-power"><i class="fi-plus"></i></div>
                        </div>
                    </div>
                    <div class="row">
                        <table id="misc-abilities" class="stack">
                            <thead>
                            <tr>
                                <th>Misc Ability</th>
                                <th>Note</th>
                                <th>Level</th>
                                <th>Public</th>
                            </tr>
                            </thead>
                            <?php foreach ($character->getPowerList('miscPower') as $i => $power): ?>
                                <tr>
                                    <td>
                                        <?php echo $this->Form->input('misc_power.' . $i . '.name', [
                                            'value' => $power->PowerName,
                                            'label' => false,
                                            'placeholder' => 'Misc Name',
                                            'data-powertype' => 'misc-power',
                                            'class' => 'character-autocomplete'
                                        ]); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->input('misc_power.' . $i . '.note', [
                                            'value' => $power->PowerNote,
                                            'label' => false,
                                            'placeholder' => 'Misc Note'
                                        ]); ?>
                                    </td>
                                    <td>
                                        <label class="hide-for-large-only">Level</label>
                                        <?php echo $this->Form->input('misc_power.' . $i . '.level', [
                                            'placeholder' => 'Misc Level',
                                            'label' => false,
                                            'value' => $power->PowerLevel
                                        ]); ?>
                                        <?php echo $this->Form->hidden('misc_power.' . $i . '.id', [
                                            'value' => $power->Id
                                        ]); ?>
                                    </td>
                                    <td>
                                        <label class="hide-for-large-only">Is Public</label>
                                        <?php echo $this->Form->checkbox('misc_power.' . $i . '.is_public', [
                                            'label' => false,
                                            'value' => $power->IsPublic
                                        ]); ?>
                                        <div class="alert badge clickable remove-misc-power"><i class="fi-minus"></i>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <table id="removed-misc-abilities" class="hide"></table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function buildEquipmentSection(Character $character)
    {
        ob_start();
        ?>
        <a href="#csheet-equipment" role="tab" class="accordion-title" id="csheet-equipment-heading"
           aria-controls="csheet-equipment">
            Equipment
        </a>
        <div id="csheet-equipment" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-equipment-heading">
            <a id="add-equipment-button" class="success button" href="#"><i class="fi-plus"></i> Add Equipment</a>
            <table id="equipment" class="stack">
                <thead>
                <tr>
                    <th>Equipment</th>
                    <th>Bonus</th>
                    <th>Note</th>
                    <th>Public</th>
                    <th></th>
                </tr>
                </thead>
                <?php foreach ($character->getPowerList('equipment') as $i => $power): ?>
                    <tr>
                        <td>
                            <?php echo $this->Form->input('equipment.' . $i . '.name', [
                                'label' => false,
                                'placeholder' => 'Equipment',
                                'value' => $power->PowerName
                            ]); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->input('equipment.' . $i . '.bonus', [
                                'label' => false,
                                'placeholder' => 'Bonus',
                                'value' => $power->Extra['bonus']
                            ]); ?>
                        </td>
                        <td>
                            <?php echo $this->Form->input('equipment.' . $i . '.note', [
                                'label' => false,
                                'width' => 100,
                                'placeholder' => 'Note',
                                'value' => $power->PowerNote
                            ]); ?>
                        </td>
                        <td>
                            <label class="hide-for-large-only">Is Public</label>
                            <?php echo $this->Form->checkbox('equipment.' . $i . '.is_public', [
                                'label' => false,
                                'value' => 1,
                                'checked' => $power->IsPublic
                            ]); ?>
                            <?php echo $this->Form->hidden('equipment.' . $i . '.id', [
                                'value' => $power->Id
                            ]); ?>
                        </td>
                        <td>
                            <div class="badge alert clickable remove-equipment"><i class="fi-minus"></i></div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <table class="hide" id="removed-equipment"></table>
        </div>
        <?php
        return ob_get_clean();
    }

    private function buildDerivedSection(Character $character)
    {
        ob_start();
        ?>
        <a href="#csheet-derived" role="tab" class="accordion-title" id="csheet-derived-heading"
           aria-controls="csheet-derived">
            Secondary Stats
        </a>
        <div id="csheet-derived" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-derived-heading">
            <div class="row">
                <div class="small-6 medium-3 column">
                    <label for="willpower">Max WP</label>
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
                    <label for="health">Current WP</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->select(
                        'willpower_temp',
                        range(0, $this->maxDots),
                        [
                            'value' => $character->WillpowerTemp,
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
            <div class="row">
                <div class="small-6 medium-3 column">
                    <label for="size">Size</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->input(
                        'size',
                        [
                            'value' => $character->Size,
                            'label' => false
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="speed">Speed</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->input(
                        'speed',
                        [
                            'value' => $character->Speed,
                            'label' => false
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="defense">Defense</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->input(
                        'defense',
                        [
                            'value' => $character->Defense,
                            'label' => false
                        ]
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class="small-6 medium-3 column">
                    <label for="health">Health</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->select(
                        'health',
                        range(0, $this->maxDots),
                        [
                            'value' => $character->Health,
                            'empty' => false
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="initiative_mod">Init Mod</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->input(
                        'initiative_mod',
                        [
                            'value' => $character->InitiativeMod,
                            'label' => false
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="armor">Armor</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->input(
                        'armor',
                        [
                            'value' => $character->Armor,
                            'label' => false
                        ]
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class="small-12 column subheader">
                    Wounds
                </div>
            </div>
            <div class="row">
                <div class="small-6 medium-3 column">
                    <label for="wounds_bashing">Bashing</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->input(
                        'wounds_bashing',
                        [
                            'value' => $character->WoundsBashing,
                            'label' => false
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="wounds_lethal">Lethal</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->input(
                        'wounds_lethal',
                        [
                            'value' => $character->WoundsLethal,
                            'label' => false
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="wounds_agg">Aggravated</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->input(
                        'wounds_agg',
                        [
                            'value' => $character->WoundsAgg,
                            'label' => false
                        ]
                    ); ?>
                </div>
            </div>
            <div class="row">
                <div class="small-12 column subheader">
                    Break Points
                </div>
            </div>
            <div class="row">
                <?php foreach ($character->getPowerList('break_point') as $i => $power): ?>
                    <div class="small-12 column">
                        <div>
                            <?php echo $this->Language->translate('break_point' . $i, $character->CharacterType); ?>
                        </div>
                        <?php echo $this->Form->hidden('break_point.' . $i . '.name', ['value' => 'break_point' . $i]); ?>
                        <?php echo $this->Form->hidden('break_point.' . $i . '.id', ['value' => $power->Id]); ?>
                        <?php echo $this->Form->textarea('break_point.' . $i . '.explanation', [
                            'value' => $power->Extra['explanation'],
                            'label' => false,
                            'rows' => 3
                        ]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function buildAdminSection(Character $character)
    {
        ob_start();
        ?>
        <a href="#csheet-admin" role="tab" class="accordion-title" id="csheet-admin-heading"
           aria-controls="csheet-admin">Admin</a>
        <div id="csheet-admin" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-admin-heading">
            <div class="row">
                <div class="small-6 medium-1 column">
                    <label for="is_npc">NPC</label>
                </div>
                <div class="small-6 medium-3 column">
                    <?php echo $this->Form->select(
                        'is_npc',
                        $this->yesNoOptions,
                        [
                            'value' => ($character->IsNpc) ? $character->IsNpc : 'N',
                            'empty' => false
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-1 column">
                    <label for="is_npc">Status</label>
                </div>
                <div class="small-6 medium-3 column">
                    <?php echo $this->Form->select(
                        'status',
                        $this->statuses,
                        [
                            'value' => ($character->Status) ? $character->IsNpc : 'N',
                            'empty' => true
                        ]
                    ); ?>
                </div>
                <div class="small-6 medium-1 column">
                </div>
                <div class="small-6 medium-3 column">
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }
}

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
    /**
     * @var array
     */
    public $helpers = [
        'Html',
        'Form',
        'Language'
    ];

    /**
     * @var int
     */
    private $maxDots = 9;

    /**
     * @var array
     */
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

    /**
     * @var array
     */
    private $characterTypes = [
        'changeling' => 'Changeling',
        'fae-touched' => 'Fae-Touched',
        'mage' => 'Mage',
        'mortal' => 'Mortal',
        'vampire' => 'Vampire',
        'ghoul' => 'Ghoul',
        'werewolf' => 'Werewolf',
        'wolfblooded' => 'Wolfblooded'
    ];

    /**
     * @var array
     */
    private $yesNoOptions = [
        'N' => 'No',
        'Y' => 'Yes'
    ];

    /**
     * @var array
     */
    private $statuses = [
        "Ok" => "Ok",
        "Imprisoned" => "Imprisoned",
        "Hospitalized" => "Hospitalized",
        "Torpored" => "Torpored",
        "Dead" => "Dead"
    ];

    /**
     * @var array
     */
    private $games = [
        'portland' => 'Portland, OR'
    ];

    /**
     * @var array
     */
    private $options = [
        'edit_mode' => 'none',
        'show_admin' => 'false'
    ];

    /**
     * @var array
     */
    private $sanctionStatuses = [
        '' => 'New',
        'N' => 'Desanctioned',
        'Y' => 'Sanctioned'
    ];

    /**
     * @var array
     */
    private $skillList;

    /**
     * @var array
     */
    private $sheetFields = [
        'splat1' => false,
        'splat2' => false,
        'break_points' => false,
        'touchstone' => false,
        'power_stat' => false,
        'power_points' => false
    ];

    /**
     * @var array
     */
    private $icons;


    /**
     * CharacterHelper constructor.
     * @param View $view
     * @param array $settings
     */
    function __construct(View $view, $settings = [])
    {
        parent::__construct($view, $settings);
        $list = array_merge($this->skills['mental'], $this->skills['physical'], $this->skills['social']);
        $keys = array_values($list);
        $this->skillList = array_combine($keys, $list);
        sort($this->skillList);
    }

    /**
     * @param Character $character
     * @param array $icons
     * @param array|null $options
     * @return string
     */
    public function render(Character $character, $icons, $options = null)
    {
        $this->icons = $icons;
        $this->options = array_merge($this->options, $options);
        $this->setupSheetOptions($character->CharacterType);

        $bio = $this->buildBioEdit($character);
        $stats = $this->buildStatEdit($character);
        $powers = $this->buildPowersSection($character);
        $derived = $this->buildDerivedSection($character);
        $equipment = $this->buildEquipmentSection($character);
        $conditions = $this->buildConditionsSection($character);
        $admin = ($this->options['show_admin']) ? $this->buildAdminSection($character) : '';

        ob_start();
        ?>
        <ul id="character-form-edit" class="accordion" data-accordion data-multi-expand="true"
            data-allow-all-closed="true">
            <li class="accordion-item is-active">
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
                <?php echo $conditions; ?>
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

    /**
     * @param Character $character
     * @return string
     */
    private function buildBioEdit(Character $character)
    {
        $characterName = $character->CharacterName;
        $characterType = $this->characterTypes[$character->CharacterType];
        $city = $character->City;
        $age = $character->Age;
        $concept = $character->Concept;
        $splat1 = $character->Splat1;
        $splat2 = $character->Splat2;
        $virtue = $character->Virtue;
        $vice = $character->Vice;
        $history = str_replace("\n", "<br />", $character->History);
        $characterNotes = str_replace("\n", "<br />", $character->CharacterNotes);
        $icon = $this->icons[$character->Icon];

        if ($this->mayEditOpen()) {
            $characterName = $this->Form->input('character_name', [
                'value' => $character->CharacterName,
                'label' => false,
                'required',
                'div' => false,
                'data-validator' => 'character_name'
            ]);
            $characterType = $this->Form->select(
                'character_type',
                $this->characterTypes,
                [
                    'value' => $character->CharacterType,
                    'empty' => false,
                    'label' => false,
                    'div' => false
                ]);
            $city = $this->Form->select('city', $this->games, [
                'label' => false,
                'value' => $character->City,
                'empty' => false
            ]);
            $age = $this->Form->input('age', [
                'value' => $character->Age,
                'placeholder' => 'Age (True)',
                'label' => false,
                'type' => 'number',
                'empty' => false
            ]);
            $concept = $this->Form->input('concept', [
                'value' => $character->Concept,
                'placeholder' => 'Character Concept',
                'label' => false
            ]);
            $splat1 = $this->Form->input(
                'splat1',
                [
                    'value' => $character->Splat1,
                    'placeholder' => $this->Language->translate('splat1', $character->CharacterType),
                    'label' => false,
                    'div' => false
                ]
            );
            $splat2 = $this->Form->input(
                'splat1',
                [
                    'value' => $character->Splat1,
                    'placeholder' => $this->Language->translate('splat2', $character->CharacterType),
                    'label' => false,
                    'div' => false
                ]
            );
            $virtue = $this->Form->input('virtue', [
                'value' => $character->Virtue,
                'label' => false,
                'placeholder' => $this->Language->translate('virtue', $character->CharacterType)
            ]);
            $vice = $this->Form->input('vice', [
                'value' => $character->Vice,
                'label' => false,
                'placeholder' => $this->Language->translate('vice', $character->CharacterType)
            ]);
            $history = $this->Form->textarea(
                'history',
                [
                    'rows' => 6,
                    'placeholder' => 'None',
                    'label' => 'Biography',
                    'value' => $character->History,
                ]);
            $characterNotes = $this->Form->textarea(
                'notes',
                [
                    'rows' => 6,
                    'label' => 'Notes',
                    'value' => $character->CharacterNotes,
                    'aria-describedby' => 'notes-help-text'
                ]
            );
        }
        if ($this->mayEditLimited()) {
            $icon = $this->Form->select('icon', $this->icons, [
                'label' => false,
                'value' => $character->Icon,
                'empty' => false
            ]);
        }
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
                    <?php echo $characterName ?>
                    <span class="form-error">
                        Character Names are required.
                    </span>
                </div>
                <div class="medium-1 columns">
                    <label for="character_type">Type</label>
                </div>
                <div class="medium-3 columns">
                    <?php echo $characterType; ?>
                </div>
            </div>
            <div class="row">
                <div class="medium-1 columns">
                    <label for="chronicle">Chronicle</label>
                </div>
                <div class="medium-3 columns">
                    <?php echo $city; ?>
                </div>
                <div class="medium-1 columns">
                    <label for="icon">Icon</label>
                </div>
                <div class="medium-3 columns">
                    <?php echo $icon; ?>
                </div>
                <div class="medium-1 columns">
                    <label for="apparent_age">Age</label>
                </div>
                <div class="medium-3 columns">
                    <?php echo $age; ?>
                </div>
            </div>
            <div class="row">
                <div class="medium-1 columns">
                    <label for="concept">Concept</label>
                </div>
                <div class="medium-11 columns">
                    <?php echo $concept;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="medium-12 columns subheader">Faction Affiliations</div>
            </div>
            <div class="row">
                <div class="medium-1 columns">
                    <?php if ($this->sheetFields['splat1']): ?>
                        <label for="splat1">
                            <?php echo $this->Language->translate('splat1', $character->CharacterType); ?>
                        </label>
                    <?php endif; ?>
                </div>
                <div class="medium-3 columns">
                    <?php if ($this->sheetFields['splat1']) echo $splat1; ?>
                </div>
                <div class="medium-1 columns">
                    <?php if ($this->sheetFields['splat2']): ?>
                        <label for="splat2">
                            <?php echo $this->Language->translate('splat2', $character->CharacterType); ?>
                        </label>
                    <?php endif; ?>
                </div>
                <div class="medium-3 columns">
                    <?php if ($this->sheetFields['splat2']) echo $splat2; ?>
                </div>
                <div class="medium-4 columns"></div>
            </div>
            <div class="row">
                <div class="small-12 columns subheader">
                    Anchors
                </div>
            </div>
            <div class="row">
                <div class="medium-1 column">
                    <label for="virtue">
                        <?php echo $this->Language->translate('virtue', $character->CharacterType); ?>
                    </label>
                </div>
                <div class="medium-3 column">
                    <?php echo $virtue; ?>
                </div>
                <div class="medium-1 column">
                    <label for="vice">
                        <?php echo $this->Language->translate('vice', $character->CharacterType); ?>
                    </label>
                </div>
                <div class="medium-3 column">
                    <?php echo $vice; ?>
                </div>
                <div class="medium-4 column">&nbsp;</div>
            </div>
            <div class="row">
                <div class="small-12 column subheader">
                    Aspirations
                    <?php if ($this->mayEditLimited()): ?>
                        <div class="success badge clickable" id="add-aspiration"><i class="fi-plus"></i></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="small-12 column">
                    <table class="stack" id="aspirations">
                        <?php foreach ($character->getPowerList('aspiration') as $i => $power): ?>
                            <tr>
                                <td>
                                    <?php if ($this->mayEditLimited()): ?>
                                        <?php echo $this->Form->input('aspiration.' . $i . '.name', [
                                            'label' => false,
                                            'value' => $power->PowerName
                                        ]); ?>
                                        <?php echo $this->Form->hidden('aspiration.' . $i . '.id', [
                                            'value' => $power->Id
                                        ]); ?>
                                    <?php else: ?>
                                        <?php echo $power->PowerName; ?>
                                    <?php endif; ?>
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
                    <?php echo $history; ?>
                </div>
            </div>
            <div class="row">
                <div class="medium-12 columns">
                    <label>Notes</label>
                    <?php echo $characterNotes; ?>
                    <?php if ($character->IsSanctioned === ''): ?>
                        <p class="help-text" id="notes-help-text">
                            At Character Creation this section should include a list of all traits or bonuses provided
                            by purchase of applicable merits, abilities or by character type choices. Those bonuses will
                            be added to the sheet by the sanctioningstaff.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * @param Character $character
     * @return string
     */
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
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.intelligence', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'intelligence')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'intelligence')->PowerLevel; ?>
                    <?php endif; ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeStrength">
                        Strength
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.strength', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'strength')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'strength')->PowerLevel; ?>
                    <?php endif; ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributePresence">
                        Presence
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.presence', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'presence')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'presence')->PowerLevel; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="small-3 medium-3 column">
                    <label for="attributeWits">
                        Wits
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.wits', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'wits')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'wits')->PowerLevel; ?>
                    <?php endif; ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeDexterity">
                        Dexterity
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.dexterity', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'dexterity')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'dexterity')->PowerLevel; ?>
                    <?php endif; ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeManipulation">
                        Manipulation
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.manipulation', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'manipulation')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'manipulation')->PowerLevel; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="small-3 medium-3 column">
                    <label for="attributeResolve">
                        Resolve
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.resolve', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'resolve')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'resolve')->PowerLevel; ?>
                    <?php endif; ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeStamina">
                        Stamina
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.stamina', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'stamina')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'stamina')->PowerLevel; ?>
                    <?php endif; ?>
                </div>
                <div class="small-3 medium-3 column">
                    <label for="attributeComposure">
                        Composure
                    </label>
                </div>
                <div class="small-9 medium-1 column">
                    <?php if ($this->mayEditOpen()): ?>
                        <?php echo $this->Form->select('attribute.composure', range(0, $this->maxDots), [
                            'value' => $character->getPowerByTypeAndName('attribute', 'composure')->PowerLevel,
                            'empty' => false
                        ]); ?>
                    <?php else: ?>
                        <?php echo $character->getPowerByTypeAndName('attribute', 'composure')->PowerLevel; ?>
                    <?php endif; ?>
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
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->select('skill.' . $key, range(0, $this->maxDots), [
                                                'value' => $character->getPowerByTypeAndName('skill', $key)->PowerLevel,
                                                'empty' => false
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $character->getPowerByTypeAndName('skill', $key)->PowerLevel; ?>
                                        <?php endif; ?>
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
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->select('skill.' . $key, range(0, $this->maxDots), [
                                                'value' => $character->getPowerByTypeAndName('skill', $key)->PowerLevel,
                                                'empty' => false
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $character->getPowerByTypeAndName('skill', $key)->PowerLevel; ?>
                                        <?php endif; ?>
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
                                    <div class="small-7 column" style="white-space: nowrap;">
                                        <label for="skill<?php echo ucfirst($key); ?>"><?php echo $label; ?></label>
                                    </div>
                                    <div class="small-5 column">
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->select('skill.' . $key, range(0, $this->maxDots), [
                                                'value' => $character->getPowerByTypeAndName('skill', $key)->PowerLevel,
                                                'empty' => false
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $character->getPowerByTypeAndName('skill', $key)->PowerLevel; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="specialties" class="small-12 medium-5 column">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Specialties
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="badge success clickable add-foundation-row" data-target-table="specialties">
                                    <i class="fi-plus"></i></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php foreach ($character->getPowerList('specialty') as $i => $specialty): ?>
                        <div class="row">
                            <div class="small-5 medium-5 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->select('specialty.' . $i . '.name', $this->skillList, [
                                        'value' => $specialty->PowerName
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo $this->skillList[$specialty->PowerName]; ?>
                                <?php endif; ?>
                            </div>
                            <div class="small-6 medium-6 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->hidden('specialty.' . $i . '.id', [
                                        'value' => $specialty->Id
                                    ]); ?>
                                    <?php echo $this->Form->input('specialty.' . $i . '.note', [
                                        'value' => $specialty->PowerNote,
                                        'label' => false
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo $specialty->PowerNote; ?>
                                <?php endif; ?>
                            </div>
                            <div class="small-1 medium-1 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <div class="badge alert clickable remove-specialty"><i class="fi-minus"></i></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * @param Character $character
     * @return string
     */
    private function buildPowersSection(Character $character)
    {
        switch (strtolower($character->CharacterType)) {
            case 'mortal':
                return $this->buildMortalPowersSection($character);
            case 'vampire':
                return $this->buildVampirePowersSection($character);
            case 'mage':
                return $this->buildMagePowersSection($character);
            default:
                return $this->buildMortalPowersSection($character);
        }
    }

    /**
     * @param Character $character
     * @return string
     */
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
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable" id="add-merit"><i class="fi-plus"></i></div>
                            <?php endif; ?>
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
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->input('merit.' . $i . '.name', [
                                                'value' => $power->PowerName,
                                                'placeholder' => 'Merit Name',
                                                'label' => false,
                                                'data-powertype' => 'merit',
                                                'class' => 'character-autocomplete'
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $power->PowerName; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->input('merit.' . $i . '.note', [
                                                'label' => false,
                                                'value' => $power->PowerNote,
                                                'placeholder' => 'Merit Note'
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $power->PowerNote; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <label class="hide-for-large-only">Level</label>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->select(
                                                'merit.' . $i . '.level',
                                                range(0, $this->maxDots),
                                                [
                                                    'label' => false,
                                                    'value' => $power->PowerLevel
                                                ]
                                            ); ?>
                                            <?php echo $this->Form->hidden('merit.' . $i . '.id', [
                                                'value' => $power->Id
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $power->PowerLevel; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <label class="show-for-small-only">Is Public</label>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->checkbox('merit.' . $i . '.is_public', [
                                                'label' => false,
                                                'value' => 1,
                                                'checked' => $power->IsPublic
                                            ]); ?>
                                            <div class="alert badge clickable remove-merit"><i class="fi-minus"></i>
                                            </div>
                                        <?php else: ?>
                                            <?php echo $power->IsPublic ? 'Yes' : 'No'; ?>
                                        <?php endif; ?>
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
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable" id="add-misc-power"><i class="fi-plus"></i></div>
                            <?php endif; ?>
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
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->input('misc_power.' . $i . '.name', [
                                                'value' => $power->PowerName,
                                                'label' => false,
                                                'placeholder' => 'Misc Name',
                                                'data-powertype' => 'misc-power',
                                                'class' => 'character-autocomplete'
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $power->PowerName; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->input('misc_power.' . $i . '.note', [
                                                'value' => $power->PowerNote,
                                                'label' => false,
                                                'placeholder' => 'Misc Note'
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $power->PowerNote; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <label class="hide-for-large-only">Level</label>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->input('misc_power.' . $i . '.level', [
                                                'placeholder' => 'Misc Level',
                                                'label' => false,
                                                'value' => $power->PowerLevel
                                            ]); ?>
                                            <?php echo $this->Form->hidden('misc_power.' . $i . '.id', [
                                                'value' => $power->Id
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $power->PowerLevel; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <label class="hide-for-large-only">Is Public</label>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->checkbox('misc_power.' . $i . '.is_public', [
                                                'label' => false,
                                                'value' => 1,
                                                'checked' => $power->IsPublic
                                            ]); ?>
                                            <div class="alert badge clickable remove-misc-power"><i
                                                        class="fi-minus"></i>
                                            </div>
                                        <?php else: ?>
                                            <?php echo $power->IsPublic ? 'Yes' : 'No'; ?>
                                        <?php endif; ?>
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

    /**
     * @param Character $character
     * @return string
     */
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
            <?php if ($this->mayEditOpen()): ?>
                <a id="add-equipment-button" class="success button" href="#"><i class="fi-plus"></i> Add Equipment</a>
            <?php endif; ?>
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
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->input('equipment.' . $i . '.name', [
                                    'label' => false,
                                    'placeholder' => 'Equipment',
                                    'value' => $power->PowerName
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->PowerName; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->input('equipment.' . $i . '.bonus', [
                                    'label' => false,
                                    'placeholder' => 'Bonus',
                                    'value' => $power->Extra['bonus']
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->Extra['bonus']; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->input('equipment.' . $i . '.note', [
                                    'label' => false,
                                    'width' => 100,
                                    'placeholder' => 'Note',
                                    'value' => $power->PowerNote
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->PowerNote; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <label class="hide-for-large-only">Is Public</label>
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->checkbox('equipment.' . $i . '.is_public', [
                                    'label' => false,
                                    'value' => 1,
                                    'checked' => $power->IsPublic
                                ]); ?>
                                <?php echo $this->Form->hidden('equipment.' . $i . '.id', [
                                    'value' => $power->Id
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->IsPublic ? 'Yes' : 'No'; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="badge alert clickable remove-equipment"><i class="fi-minus"></i></div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <table class="hide" id="removed-equipment"></table>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * @param Character $character
     * @return string
     */
    private function buildDerivedSection(Character $character)
    {
        $willpowerPerm = $character->WillpowerPerm;
        $willpowerTemp = $character->WillpowerTemp;
        $morality = $character->Morality;
        $size = $character->Size;
        $speed = $character->Speed;
        $defense = $character->Defense;
        $health = $character->Health;
        $initMod = $character->InitiativeMod;
        $armor = $character->Armor;
        $woundsBashing = $character->WoundsBashing;
        $woundsLethal = $character->WoundsLethal;
        $woundsAgg = $character->WoundsAgg;
        $powerStat = $character->PowerStat;
        $powerPoints = $character->PowerPoints;

        if ($this->mayEditOpen()) {
            $powerStat = $this->Form->select(
                'power_stat',
                range(0, $this->maxDots),
                [
                    'value' => $character->PowerStat,
                    'empty' => false
                ]
            );
            $willpowerPerm = $this->Form->select(
                'willpower_perm',
                range(0, $this->maxDots),
                [
                    'value' => $character->WillpowerPerm,
                    'empty' => false
                ]
            );
            $morality = $this->Form->select(
                'morality',
                range(0, 10),
                [
                    'value' => $character->Morality,
                    'empty' => false
                ]
            );
            $size = $this->Form->input(
                'size',
                [
                    'value' => $character->Size,
                    'label' => false
                ]
            );
            $speed = $this->Form->input(
                'speed',
                [
                    'value' => $character->Speed,
                    'label' => false
                ]
            );
            $defense = $this->Form->input(
                'defense',
                [
                    'value' => $character->Defense,
                    'label' => false
                ]
            );
            $health = $this->Form->select(
                'health',
                range(0, $this->maxDots),
                [
                    'value' => $character->Health,
                    'empty' => false
                ]
            );
            $initMod = $this->Form->input(
                'initiative_mod',
                [
                    'value' => $character->InitiativeMod,
                    'label' => false
                ]
            );
            $armor = $this->Form->input(
                'armor',
                [
                    'value' => $character->Armor,
                    'label' => false
                ]
            );
        }

        if ($this->mayEditLimited()) {
            $willpowerTemp = $this->Form->select(
                'willpower_temp',
                range(0, $this->maxDots),
                [
                    'value' => $character->WillpowerTemp,
                    'empty' => false
                ]
            );
            $powerPoints = $this->Form->input(
                'power_points',
                [
                    'value' => $character->PowerPoints,
                    'label' => false,
                    'type' => 'number'
                ]
            );
            $woundsBashing = $this->Form->input(
                'wounds_bashing',
                [
                    'value' => $character->WoundsBashing,
                    'label' => false
                ]
            );
            $woundsLethal = $this->Form->input(
                'wounds_lethal',
                [
                    'value' => $character->WoundsLethal,
                    'label' => false
                ]
            );
            $woundsAgg = $this->Form->input(
                'wounds_agg',
                [
                    'value' => $character->WoundsAgg,
                    'label' => false
                ]
            );
        }
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
                    <?php echo $willpowerPerm; ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="health">Current WP</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $willpowerTemp; ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="integrity"><?php echo $this->Language->translate('morality', $character->CharacterType); ?></label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $morality; ?>
                </div>
            </div>
            <div class="row">
                <div class="small-6 medium-3 column">
                    <label for="size">Size</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $size; ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="speed">Speed</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $speed; ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="defense">Defense</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $defense; ?>
                </div>
            </div>
            <div class="row">
                <div class="small-6 medium-3 column">
                    <label for="health">Health</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $health; ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="initiative_mod">Init Mod</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $initMod; ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="armor">Armor</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $armor; ?>
                </div>
            </div>
            <?php if ($this->sheetFields['power_points'] || $this->sheetFields['power_stat']): ?>
                <div class="row">
                    <?php if ($this->sheetFields['power_stat']): ?>
                        <div class="small-6 medium-3 column">
                            <label for="power_stat">
                                <?php echo $this->Language->translate(
                                    'powerstat',
                                    $character->CharacterType
                                ); ?>
                            </label>
                        </div>
                        <div class="small-6 medium-1 column">
                            <?php echo $powerStat; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->sheetFields['power_points']): ?>
                        <div class="small-6 medium-3 column">
                            <label for="power_points">
                                <?php echo $this->Language->translate(
                                    'powerpoints',
                                    $character->CharacterType
                                ); ?>
                            </label>
                        </div>
                        <div class="small-6 medium-1 column end">
                            <?php echo $powerPoints; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
                    <?php echo $woundsBashing; ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="wounds_lethal">Lethal</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $woundsLethal; ?>
                </div>
                <div class="small-6 medium-3 column">
                    <label for="wounds_agg">Aggravated</label>
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $woundsAgg; ?>
                </div>
            </div>
            <?php if ($this->sheetFields['break_points']): ?>
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
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->hidden('break_point.' . $i . '.name', ['value' => 'break_point' . $i]); ?>
                                <?php echo $this->Form->hidden('break_point.' . $i . '.id', ['value' => $power->Id]); ?>
                                <?php echo $this->Form->textarea('break_point.' . $i . '.explanation', [
                                    'value' => $power->Extra['explanation'],
                                    'label' => false,
                                    'rows' => 3
                                ]); ?>
                            <?php else: ?>
                                <?php echo str_replace("\n", "<br />", $power->Extra['explanation']); ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($this->sheetFields['touchstone']): ?>
                <div class="row">
                    <div class="small-12 column subheader">
                        Touchstones
                        <?php if ($this->mayEditOpen()): ?>
                            <div class="success badge clickable add-foundation-row" data-target-table="touchstones"><i
                                        class="fi-plus"></i></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="touchstones">
                    <div class="row">
                        <?php foreach ($character->getPowerList('touchstone') as $i => $power): ?>
                            <div class="small-12 medium-2 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->select('touchstone.' . $i . '.level', range(0, 10), [
                                        'value' => $power->PowerLevel,
                                        'label' => false
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo $power->PowerLevel; ?>
                                <?php endif; ?>
                            </div>
                            <div class="small-12 medium-10 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->hidden('touchstone.' . $i . '.id', ['value' => $power->Id]); ?>
                                    <?php echo $this->Form->input('touchstone.' . $i . '.name', [
                                        'value' => $power->PowerName,
                                        'label' => false
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo $power->PowerName; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * @param Character $character
     * @return string
     */
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
                    Sanctioned
                </div>
                <div class="small-6 medium-3 column">
                    <?php echo $this->Form->select(
                        'is_sanctioned',
                        $this->sanctionStatuses,
                        [
                            'value' => $character->IsSanctioned,
                            'empty' => false
                        ]);
                    ?>
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * @return bool
     */
    private function mayEditLimited()
    {
        return in_array($this->options['edit_mode'], ['open', 'limited']);
    }

    private function mayEditOpen()
    {
        return in_array($this->options['edit_mode'], ['open']);
    }

    private function setupSheetOptions($characterType)
    {
        switch ($characterType) {
            case 'mortal':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['break_points'] = true;
                break;
            case 'vampire':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['splat2'] = true;
                $this->sheetFields['touchstone'] = true;
                $this->sheetFields['power_stat'] = true;
                $this->sheetFields['power_points'] = true;
                break;
            case 'ghoul':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['power_points'] = true;
                break;
            case 'werewolf':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['splat2'] = true;
                $this->sheetFields['power_stat'] = true;
                $this->sheetFields['power_points'] = true;
                break;
            case 'wolfblooded':
                break;
            case 'mage':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['splat2'] = true;
                $this->sheetFields['power_stat'] = true;
                $this->sheetFields['power_points'] = true;
                break;
            case 'changeling':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['splat2'] = true;
                $this->sheetFields['power_stat'] = true;
                $this->sheetFields['power_points'] = true;
                break;
            case 'fae-touched':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['power_points'] = true;
                break;
        }
    }

    private function buildVampirePowersSection(Character $character)
    {
        $meritTable = $this->buildTable($character, 'merit', 'merits');
        $miscPowerTable = $this->buildTable($character, 'misc_power', 'misc-abilities', ['level' => ['type' => 'text']]);
        $icDiscTable = $this->buildTable($character, 'icdisc', 'icdiscs');
        $oocDiscTable = $this->buildTable($character, 'oocdisc', 'oocdiscs');
        $devotionTable = $this->buildTable($character, 'devotion', 'devotions');

        ob_start();
        ?>
        <a href="#csheet-template" role="tab" class="accordion-title" id="csheet-template-heading"
           aria-controls="csheet-template">Abilities</a>
        <div id="csheet-template" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-template-heading">
            <div class="row">
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Merits
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="merits"><i
                                            class="fi-plus"></i></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $meritTable; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            In Clan Disciplines
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="icdiscs">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $icDiscTable; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Out of Clan Disciplines
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="oocdiscs">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $oocDiscTable; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Devotions
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="devotions">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $devotionTable; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Misc Abilities
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row"
                                     data-target-table="misc-abilities">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $miscPowerTable; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function buildMagePowersSection(Character $character)
    {
        $meritTable = $this->buildTable($character, 'merit', 'merits');
        $miscPowerTable = $this->buildTable($character, 'misc_power', 'misc-abilities', ['level' => ['type' => 'text']]);
        $rotesTable = $this->buildTable($character, 'rote', 'rotes', ['level' => ['type' => 'text']]);

        $arcanaTypes = [
            'Ruling' => 'Ruling',
            'Common' => 'Common',
            'Inferior' => 'Inferior'
        ];
        ob_start();
        ?>
        <a href="#csheet-template" role="tab" class="accordion-title" id="csheet-template-heading"
           aria-controls="csheet-template">Abilities</a>
        <div id="csheet-template" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-template-heading">
            <div class="row">
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Merits
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="merits"><i
                                            class="fi-plus"></i></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $meritTable; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Arcana
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="arcana">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <table id="arcana" class="stack">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Level</th>
                                <th>Public</th>
                            </tr>
                            </thead>
                            <?php foreach ($character->getPowerList('arcana') as $i => $power): ?>
                                <tr>
                                    <td>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->input('arcana' . '.' . $i . '.name', [
                                                'value' => $power->PowerName,
                                                'placeholder' => $this->Language->translate('arcana', $character->CharacterType) . ' Name',
                                                'label' => false,
                                                'data-powertype' => 'arcana',
                                                'class' => 'character-autocomplete'
                                            ]); ?>
                                        <?php else: ?>
                                            <?php echo $power->PowerName; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->select(
                                                'arcana' . '.' . $i . '.type',
                                                $arcanaTypes,
                                                [
                                                    'label' => false,
                                                    'empty' => false,
                                                    'value' => $power->Extra['type'],
                                                    'placeholder' => $this->Language->translate('arcana', $character->CharacterType) . ' Type'
                                                ]);
                                            ?>
                                        <?php else: ?>
                                            <?php echo $power->Extra['type']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td style="width: 70px;">
                                        <label class="hide-for-large-only">Level</label>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->select(
                                                'arcana' . '.' . $i . '.level',
                                                range(0, $this->maxDots),
                                                [
                                                    'label' => false,
                                                    'value' => $power->PowerLevel
                                                ]
                                            ); ?>
                                        <?php else: ?>
                                            <?php echo $power->PowerLevel; ?>
                                        <?php endif; ?>
                                        <?php echo $this->Form->hidden('arcana' . '.' . $i . '.id', [
                                            'value' => $power->Id
                                        ]); ?>
                                    </td>
                                    <td>
                                        <label class="show-for-small-only">Is Public</label>
                                        <?php if ($this->mayEditOpen()): ?>
                                            <?php echo $this->Form->checkbox('arcana' . '.' . $i . '.is_public', [
                                                'label' => false,
                                                'value' => 1,
                                                'checked' => $power->IsPublic
                                            ]); ?>
                                            <div class="alert badge clickable remove-character-row" data-target-table="arcana">
                                                <i class="fi-minus"></i>
                                            </div>
                                        <?php else: ?>
                                            <?php echo $power->IsPublic ? 'Yes' : 'No'; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <table id="removed-arcana" class="hide"></table>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Rotes
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="rotes">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $rotesTable; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Misc Abilities
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row"
                                     data-target-table="misc-abilities">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $miscPowerTable; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function buildTable(Character $character, $powerType, $tableId, $options = [])
    {
        $inputOptions = [
            'level' => [
                'type' => 'select'
            ]
        ];

        $inputOptions = array_merge($inputOptions, $options);
        ob_start();
        ?>
        <table id="<?php echo $tableId; ?>" class="stack">
            <thead>
            <tr>
                <th>Name</th>
                <th>Note</th>
                <th>Level</th>
                <th>Public</th>
            </tr>
            </thead>
            <?php foreach ($character->getPowerList(lcfirst(Inflector::camelize($powerType))) as $i => $power): ?>
                <tr>
                    <td>
                        <?php if ($this->mayEditOpen()): ?>
                            <?php echo $this->Form->input($powerType . '.' . $i . '.name', [
                                'value' => $power->PowerName,
                                'placeholder' => $this->Language->translate($powerType, $character->CharacterType) . ' Name',
                                'label' => false,
                                'data-powertype' => $powerType,
                                'class' => 'character-autocomplete'
                            ]); ?>
                        <?php else: ?>
                            <?php echo $power->PowerName; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($this->mayEditOpen()): ?>
                            <?php echo $this->Form->input($powerType . '.' . $i . '.note', [
                                'label' => false,
                                'value' => $power->PowerNote,
                                'placeholder' => $this->Language->translate($powerType, $character->CharacterType) . ' Note'
                            ]); ?>
                        <?php else: ?>
                            <?php echo $power->PowerNote; ?>
                        <?php endif; ?>
                    </td>
                    <td style="width: 70px;">
                        <label class="hide-for-large-only">Level</label>
                        <?php if ($this->mayEditOpen()): ?>
                            <?php if ($inputOptions['level']['type'] == 'select'): ?>
                                <?php echo $this->Form->select(
                                    $powerType . '.' . $i . '.level',
                                    range(0, $this->maxDots),
                                    [
                                        'label' => false,
                                        'value' => $power->PowerLevel
                                    ]
                                ); ?>
                            <?php elseif (in_array($inputOptions['level']['type'], ['text', 'number'])): ?>
                                <?php echo $this->Form->input(
                                    $powerType . '.' . $i . '.level',
                                    [
                                        'type' => $inputOptions['level']['type'],
                                        'label' => false,
                                        'value' => $power->PowerLevel
                                    ]
                                ); ?>
                            <?php else: ?>
                                Unknown level option: <?php echo $inputOptions['level']['type']; ?>
                            <?php endif; ?>
                            <?php echo $this->Form->hidden($powerType . '.' . $i . '.id', [
                                'value' => $power->Id
                            ]); ?>
                        <?php else: ?>
                            <?php echo $power->PowerLevel; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <label class="show-for-small-only">Is Public</label>
                        <?php if ($this->mayEditOpen()): ?>
                            <?php echo $this->Form->checkbox($powerType . '.' . $i . '.is_public', [
                                'label' => false,
                                'value' => 1,
                                'checked' => $power->IsPublic
                            ]); ?>
                            <div class="alert badge clickable remove-character-row" data-target-table="<?php echo $tableId; ?>"><i class="fi-minus"></i>
                            </div>
                        <?php else: ?>
                            <?php echo $power->IsPublic ? 'Yes' : 'No'; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <table id="removed-<?php echo $tableId; ?>" class="hide"></table>
        <?php
        return ob_get_clean();
    }

    private function buildConditionsSection(Character $character)
    {
        $conditions = $character->getPowerList('conditions');
        $conditions = $conditions[0];
        ob_start();
        ?>
        <a href="#csheet-conditions" role="tab" class="accordion-title" id="csheet-conditions-heading"
           aria-controls="csheet-conditions">Conditions</a>
        <div id="csheet-conditions" class="accordion-content" role="tabpanel" data-tab-content
             aria-labelledby="csheet-conditions-heading">
            <div class="row">
                <div class="small-12 column">
                    <?php if ($this->mayEditLimited()): ?>
                        <?php echo $this->Form->hidden('conditions.0.id', ['value' => $conditions->Id]); ?>
                        <?php echo $this->Form->hidden('conditions.0.name', ['value' => 'conditions']); ?>
                        <?php echo $this->Form->textarea(
                            'conditions.0.conditions',
                            [
                                'rows' => 6,
                                'value' => $conditions->Extra['conditions']
                            ]
                        ); ?>
                    <?php else: ?>
                        <?php echo str_replace("\n", '<br />', $conditions->Extra['conditions']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

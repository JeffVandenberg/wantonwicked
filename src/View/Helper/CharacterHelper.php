<?php

namespace App\View\Helper;

use Cake\View\Helper\FormHelper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\View;
use classes\character\data\Character;
use classes\character\data\CharacterPower;
use Exception;

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
        'wolf_touchstone' => false,
        'changeling_touchstone' => false,
        'obsession' => false,
        'power_stat' => false,
        'power_points' => false,
        'pledge' => false,
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
        ksort($this->skillList);
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
            <div class="small-3 column">Current XP</div>
            <div class="small-1 column"><?php echo $character->CurrentExperience; ?></div>
            <div class="small-3 column">Total XP</div>
            <div class="small-1 column"><?php echo $character->TotalExperience; ?></div>
            <div class="small-2 column">Updated By</div>
            <div class="small-2 column"><?php echo $character->UpdatedBy->Username; ?></div>
            <div class="small-2 column">Last Login</div>
            <div class="small-2 column"></div>
            <div class="small-3 column">Bonus XP</div>
            <div class="small-1 column"><?php echo $character->BonusReceived; ?></div>
            <div class="small-1 column">On</div>
            <div class="small-3 column"><?php echo $character->UpdatedOn; ?></div>
            <?php echo $this->Form->hidden('character_id', ['value' => $character->Id, 'id' => 'character_id']); ?>
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
        $description = $character->Description;
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
        $friends = $character->Friends;

        if ($this->mayEditOpen()) {
            $characterName = $this->Form->text('character_name', [
                'value' => $character->CharacterName,
                'label' => false,
                'required',
                'div' => false,
                'maxlength' => 45,
                'data-validator' => 'character_name'
            ]);
            $friends = $this->Form->control('friends', [
                'value' => $character->Friends,
                'label' => false,
                'div' => false,
                'maxlength' => 255,
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
                'empty' => false,
                'id' => 'city',
            ]);
            $age = $this->Form->control('age', [
                'value' => $character->Age,
                'placeholder' => 'Age (True)',
                'label' => false,
                'type' => 'number',
                'empty' => false,
                'maxlength' => 6,
            ]);
            $concept = $this->Form->control('concept', [
                'value' => $character->Concept,
                'placeholder' => 'Character Concept',
                'label' => false,
                'maxlength' => 255,

            ]);
            $splat1 = $this->Form->control(
                'splat1',
                [
                    'value' => $character->Splat1,
                    'placeholder' => $this->Language->translate('splat1', $character->CharacterType),
                    'label' => false,
                    'div' => false,
                    'maxlength' => 20,
                ]
            );
            $splat2 = $this->Form->control(
                'splat1',
                [
                    'value' => $character->Splat1,
                    'placeholder' => $this->Language->translate('splat2', $character->CharacterType),
                    'label' => false,
                    'div' => false,
                    'maxlength' => 30,
                ]
            );
            $virtue = $this->Form->control('virtue', [
                'value' => $character->Virtue,
                'label' => false,
                'placeholder' => $this->Language->translate('virtue', $character->CharacterType),
                'maxlength' => 100,
            ]);
            $vice = $this->Form->control('vice', [
                'value' => $character->Vice,
                'label' => false,
                'placeholder' => $this->Language->translate('vice', $character->CharacterType),
                'maxlength' => 100,
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
            $splat1 = $this->Form->control(
                'splat1',
                [
                    'value' => $character->Splat1,
                    'placeholder' => $this->Language->translate('splat1', $character->CharacterType),
                    'label' => false,
                    'div' => false,
                    'maxlength' => 20,
                ]
            );
            $splat2 = $this->Form->control(
                'splat2',
                [
                    'value' => $character->Splat2,
                    'placeholder' => $this->Language->translate('splat2', $character->CharacterType),
                    'label' => false,
                    'div' => false,
                    'maxlength' => 30,
                ]
            );
            $icon = $this->Form->select('icon', $this->icons, [
                'label' => false,
                'value' => $character->Icon,
                'empty' => false
            ]);
            $description = $this->Form->textarea('description',
                [
                    'value' => $character->Description,
                    'label' => false,
                    'rows' => 6
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
                <div class="medium-1 columns">
                    <?php if ($this->sheetFields['friends']): ?>
                        <label for="friends">
                            <?php echo $this->Language->translate('friends', $character->CharacterType); ?>
                        </label>
                    <?php endif; ?>
                </div>
                <div class="medium-3 columns">
                    <?php if ($this->sheetFields['friends']) echo $friends; ?>
                </div>
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
                                        <?php echo $this->Form->control('aspiration.' . $i . '.name', [
                                            'label' => false,
                                            'value' => $power->PowerName,
                                            'maxlength' => 255,

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
                <div class="medium-12 columns subheader">Character</div>
                <div class="small-12 columns">
                    <label for="history">Description</label>
                    <?php echo $description; ?>
                </div>
                <div class="small-12 columns">
                    <label for="history">Biography</label>
                    <?php echo $history; ?>
                </div>
                <div class="small-12 columns">
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
                                    <?php echo $this->Form->control('specialty.' . $i . '.note', [
                                        'value' => $specialty->PowerNote,
                                        'label' => false,
                                        'maxlength' => 255,

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
            case 'ghoul':
                return $this->buildVampirePowersSection($character);
            case 'mage':
                return $this->buildMagePowersSection($character);
            case 'werewolf':
                return $this->buildWerewolfPowersSection($character);
            case 'changeling':
            case 'fae-touched':
                return $this->buildChangelingPowersSection($character);
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
        $meritTable = $this->buildTable($character, 'merit', 'merits');
        $miscPowerTable = $this->buildTable($character, 'misc_power', 'misc-abilities',
            ['name', 'note', 'leveltext', 'public']);
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
                        <?php echo $meritTable; ?>
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
                        <?php echo $miscPowerTable; ?>
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
                                <?php echo $this->Form->control('equipment.' . $i . '.name', [
                                    'label' => false,
                                    'placeholder' => 'Equipment',
                                    'value' => $power->PowerName,
                                    'maxlength' => 255,
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->PowerName; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->control('equipment.' . $i . '.bonus', [
                                    'label' => false,
                                    'placeholder' => 'Bonus',
                                    'value' => $power->Extra['bonus'],
                                    'maxlength' => 255,
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->Extra['bonus']; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->control('equipment.' . $i . '.note', [
                                    'label' => false,
                                    'width' => 100,
                                    'placeholder' => 'Note',
                                    'value' => $power->PowerNote,
                                    'maxlength' => 255,
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
            $size = $this->Form->control(
                'size',
                [
                    'value' => $character->Size,
                    'label' => false
                ]
            );
            $speed = $this->Form->control(
                'speed',
                [
                    'value' => $character->Speed,
                    'label' => false
                ]
            );
            $defense = $this->Form->control(
                'defense',
                [
                    'value' => $character->Defense,
                    'label' => false
                ]
            );
            $health = $this->Form->select(
                'health',
                range(0, 20),
                [
                    'value' => $character->Health,
                    'empty' => false
                ]
            );
            $initMod = $this->Form->control(
                'initiative_mod',
                [
                    'value' => $character->InitiativeMod,
                    'label' => false
                ]
            );
            $armor = $this->Form->control(
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
            $powerPoints = $this->Form->control(
                'power_points',
                [
                    'value' => $character->PowerPoints,
                    'label' => false,
                    'type' => 'number'
                ]
            );
            $woundsBashing = $this->Form->control(
                'wounds_bashing',
                [
                    'value' => $character->WoundsBashing,
                    'label' => false
                ]
            );
            $woundsLethal = $this->Form->control(
                'wounds_lethal',
                [
                    'value' => $character->WoundsLethal,
                    'label' => false
                ]
            );
            $woundsAgg = $this->Form->control(
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
                                    <?php echo $this->Form->control('touchstone.' . $i . '.name', [
                                        'value' => $power->PowerName,
                                        'label' => false,
                                        'maxlength' => 255,
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo $power->PowerName; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->sheetFields['wolf_touchstone']): ?>
                <div class="row">
                    <div class="small-12 column subheader">
                        Touchstones
                    </div>
                </div>
                <div id="touchstones">
                    <div class="row">
                        <?php foreach ($character->getPowerList('touchstone') as $i => $power): ?>
                            <div class="small-12 medium-2 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->hidden('touchstone.' . $i . '.name',
                                        [
                                            'value' => $power->PowerName,
                                            'label' => false
                                        ]
                                    ); ?>
                                <?php endif; ?>
                                <?php echo $power->PowerName; ?>
                            </div>
                            <div class="small-12 medium-10 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->hidden('touchstone.' . $i . '.id', ['value' => $power->Id]); ?>
                                    <?php echo $this->Form->control('touchstone.' . $i . '.note', [
                                        'value' => $power->PowerNote,
                                        'label' => false,
                                        'maxlength' => 255,
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo $power->PowerNote; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->sheetFields['changeling_touchstone']): ?>
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
                            <div class="small-12 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->hidden('touchstone.' . $i . '.id', ['value' => $power->Id]); ?>
                                    <?php echo $this->Form->control('touchstone.' . $i . '.name', [
                                        'value' => $power->PowerName,
                                        'label' => false,
                                        'maxlength' => 255,
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo $power->PowerName; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="small-12 column subheader">
                        Triggers
                        <?php if ($this->mayEditOpen()): ?>
                            <div class="success badge clickable add-foundation-row" data-target-table="triggers"><i
                                        class="fi-plus"></i></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="triggers">
                    <div class="row">
                        <?php foreach ($character->getPowerList('trigger') as $i => $power): ?>
                            <div class="small-12 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->hidden('trigger.' . $i . '.id', ['value' => $power->Id]); ?>
                                    <?php echo $this->Form->control('trigger.' . $i . '.name', [
                                        'value' => $power->PowerName,
                                        'label' => false,
                                        'maxlength' => 255,
                                    ]); ?>
                                <?php else: ?>
                                    <?php echo $power->PowerName; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->sheetFields['pledge']): ?>
                <div class="row">
                    <div class="small-12 column subheader">
                        Pledges
                    </div>
                    <div class="small-12 column">
                        <?php foreach ($character->getPowerList('pledge') as $i => $power): ?>
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->hidden('pledge.' . $i . '.id', ['value' => $power->Id]); ?>
                                <?php echo $this->Form->hidden('pledge.' . $i . '.name', ['value' => 'pledges']); ?>
                                <?php echo $this->Form->textarea('pledge.' . $i . '.pledge', [
                                    'rows' => 4,
                                    'value' => $power->Extra['pledge'],
                                    'label' => false
                                ]); ?>
                            <?php else: ?>
                                <?php echo str_replace("\n", "<br />", $power->Extra['pledge']); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($this->sheetFields['obsession']): ?>
                <div class="row">
                    <div class="small-12 column subheader">
                        Nimbus
                    </div>
                </div>
                <div class="row">
                    <?php foreach ($character->getPowerList('nimbus') as $i => $power): ?>
                        <div class="small-12 column">
                            Long Term
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->hidden('nimbus.' . $i . '.id', ['value' => $power->Id]); ?>
                                <?php echo $this->Form->control('nimbus.' . $i . '.name', [
                                    'value' => $power->PowerName,
                                    'label' => false,
                                    'maxlength' => 255,
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->PowerName; ?>
                            <?php endif; ?>
                        </div>
                        <div class="small-12 column">
                            Immediate
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->control('nimbus.' . $i . '.immediate', [
                                    'value' => $power->Extra['immediate'],
                                    'label' => false,
                                    'maxlength' => 255,
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->Extra['immediate']; ?>
                            <?php endif; ?>
                        </div>
                        <div class="small-12 column">
                            Signature
                            <?php if ($this->mayEditOpen()): ?>
                                <?php echo $this->Form->control('nimbus.' . $i . '.signature', [
                                    'value' => $power->Extra['signature'],
                                    'label' => false,
                                    'maxlength' => 255,
                                ]); ?>
                            <?php else: ?>
                                <?php echo $power->Extra['signature']; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row">
                    <div class="small-12 column subheader">
                        Obsessions
                        <?php if ($this->mayEditOpen()): ?>
                            <div class="success badge clickable add-foundation-row" data-target-table="obsessions"><i
                                        class="fi-plus"></i></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="obsessions">
                    <div class="row">
                        <?php foreach ($character->getPowerList('obsession') as $i => $power): ?>
                            <div class="small-12 column">
                                <?php if ($this->mayEditOpen()): ?>
                                    <?php echo $this->Form->hidden('obsession.' . $i . '.id', ['value' => $power->Id]); ?>
                                    <?php echo $this->Form->control('obsession.' . $i . '.name', [
                                        'value' => $power->PowerName,
                                        'label' => false,
                                        'maxlength' => 255,
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
                <div class="small-6 medium-2 column">
                    Spend XP
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->control('xp_spent', [
                        'label' => false,
                        'value' => 0
                    ]); ?>
                </div>
                <div class="small-6 medium-2 column">
                    Grant XP
                </div>
                <div class="small-6 medium-1 column">
                    <?php echo $this->Form->control('xp_gained', [
                        'label' => false,
                        'value' => 0
                    ]); ?>
                </div>
                <div class="small-3 medium-1 column">
                    Reason
                </div>
                <div class="small-9 medium-5 column">
                    <?php echo $this->Form->control('xp_note', [
                        'value' => '',
                        'label' => false
                    ]); ?>
                </div>
                <div class="small-12 medium-6 column">
                    ST Note
                    <?php echo $this->Form->textarea('st_note', [

                    ]); ?>
                </div>
                <div class="small-12 medium-6 column">
                    <label>Last ST Note</label>
                    <?php if ($character->getLastStNote()): ?>
                        <?php echo str_replace("\n", "<br />", $character->getLastStNote()->Note); ?><br/>
                        By <?php echo $character->getLastStNote()->User->Username; ?>
                        On <?php echo $character->getLastStNote()->Created; ?>
                        <div class="text-center">
                            <a href="/characters/notes/<?php echo $character->Slug; ?>" target="_blank">View Previous
                                Notes</a>
                        </div>
                    <?php else: ?>
                        None
                    <?php endif; ?>
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
                $this->sheetFields['friends'] = true;
                break;
            case 'ghoul':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['power_points'] = true;
                $this->sheetFields['break_points'] = true;
                $this->sheetFields['friends'] = true;
                break;
            case 'werewolf':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['splat2'] = true;
                $this->sheetFields['power_stat'] = true;
                $this->sheetFields['power_points'] = true;
                $this->sheetFields['wolf_touchstone'] = true;
                $this->sheetFields['friends'] = true;
                break;
            case 'wolfblooded':
                $this->sheetFields['break_points'] = true;
                break;
            case 'mage':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['splat2'] = true;
                $this->sheetFields['power_stat'] = true;
                $this->sheetFields['power_points'] = true;
                $this->sheetFields['obsession'] = true;
                $this->sheetFields['friends'] = true;
                break;
            case 'changeling':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['splat2'] = true;
                $this->sheetFields['power_stat'] = true;
                $this->sheetFields['power_points'] = true;
                $this->sheetFields['changeling_touchstone'] = true;
                $this->sheetFields['friends'] = true;
                $this->sheetFields['pledge'] = true;
                break;
            case 'fae-touched':
                $this->sheetFields['splat1'] = true;
                $this->sheetFields['power_points'] = true;
                $this->sheetFields['break_points'] = true;
                break;
        }
    }

    private function buildVampirePowersSection(Character $character)
    {
        $meritTable = $this->buildTable($character, 'merit', 'merits');
        $miscPowerTable = $this->buildTable($character, 'misc_power', 'misc-abilities',
            ['name', 'note', 'leveltext', 'public']);
        $icDiscTable = $this->buildTable($character, 'icdisc', 'icdiscs');
        $oocDiscTable = $this->buildTable($character, 'oocdisc', 'oocdiscs');
        $devotionTable = $this->buildTable($character, 'devotion', 'devotions',
            ['name', 'note', 'leveltext', 'public']);

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
        $miscPowerTable = $this->buildTable($character, 'misc_power', 'misc-abilities',
            ['name', 'note', 'leveltext', 'public']);
        $rotesTable = $this->buildTable($character, 'rote', 'rotes',
            ['name', 'note', 'leveltext', 'public']);
        $praxisTable = $this->buildTable($character, 'praxis', 'praxes',
            ['name', 'note', 'leveltext', 'public']);
        $attainmentTable = $this->buildTable($character, 'attainment', 'attainments');

        $arcanaTypes = [
            'Ruling' => 'Ruling',
            'Common' => 'Common',
            'Inferior' => 'Inferior'
        ];
        $arcanaTable = $this->buildTable($character, 'arcana', 'arcana',
            [
                'name',
                'type' => [
                    'header' => 'Type',
                    'extra' => [
                    ],
                    'inputs' => [
                        [
                            'type' => 'select',
                            'name' => 'type',
                            'value' => 'Extra.type',
                            'range' => $arcanaTypes,
                        ],
                    ]
                ],
                'levelselect',
                'public'
            ]);
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
                        <?php echo $arcanaTable; ?>
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
                            Attainments
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="attainments">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $attainmentTable; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Praxes
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="praxes">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $praxisTable; ?>
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


    private function buildWerewolfPowersSection(Character $character)
    {
        $renowns = [
            'Cunning' => 'Cunning',
            'Glory' => 'Glory',
            'Honor' => 'Honor',
            'Purity' => 'Purity',
            'Wisdom' => 'Wisdom',
        ];
        $meritTable = $this->buildTable($character, 'merit', 'merits');
        $miscPowerTable = $this->buildTable($character, 'misc_power', 'misc-abilities',
            ['name', 'note', 'leveltext', 'public']);
        $moonGifts = $this->buildTable($character, 'moongift', 'moongifts');
        $shadowGifts = $this->buildTable($character, 'shadowgift', 'shadowgifts', [
            'name',
            'facet' => [
                'header' => 'Facet',
                'inputs' => [
                    [
                        'type' => 'select',
                        'name' => 'type',
                        'value' => 'Extra.type',
                        'range' => $renowns,
                    ]
                ]
            ],
            'public'
        ]);
        $wolfGifts = $this->buildTable($character, 'wolfgift', 'wolfgifts', [
                'name',
                'facet' => [
                    'header' => 'Facet',
                    'inputs' => [
                        [
                            'type' => 'select',
                            'name' => 'type',
                            'value' => 'Extra.type',
                            'range' => $renowns,
                        ]
                    ]
                ],
                'public'
            ]
        );

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
                    <div class="subheader">
                        Renown
                    </div>
                    <div class="row">
                        <table class="stack">
                            <?php echo $this->makeWolfRenownRow($character, 0, 'Cunning', 'cunning'); ?>
                            <?php echo $this->makeWolfRenownRow($character, 1, 'Glory', 'glory'); ?>
                            <?php echo $this->makeWolfRenownRow($character, 2, 'Honor', 'honor'); ?>
                            <?php echo $this->makeWolfRenownRow($character, 3, 'Purity', 'purity'); ?>
                            <?php echo $this->makeWolfRenownRow($character, 4, 'Wisdom', 'wisdom'); ?>
                        </table>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Moon Gifts
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="moongifts">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $moonGifts; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Shadow Gifts
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="shadowgifts">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $shadowGifts; ?>
                    </div>
                </div>
                <div class="small-12 medium-6 column float-left">
                    <div class="row">
                        <div class="small-12 column subheader">
                            Wolf Gifts
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="wolfgifts">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $wolfGifts; ?>
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


    private function buildChangelingPowersSection(Character $character)
    {
        $meritTable = $this->buildTable($character, 'merit', 'merits');
        $contractsTable = $this->buildTable($character, 'contract', 'contracts');
        $miscPowerTable = $this->buildTable($character, 'misc_power', 'misc-abilities',
            ['name', 'note', 'leveltext', 'public']);
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
                            Contracts
                            <?php if ($this->mayEditOpen()): ?>
                                <div class="success badge clickable add-character-row" data-target-table="contracts">
                                    <i class="fi-plus"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $contractsTable; ?>
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

    private function buildTable(Character $character, $powerType, $tableId, $columns = null)
    {
        $fields = [
            'name' => [
                'header' => 'Name',
                'inputs' => [
                    [
                        'name' => 'name',
                        'type' => 'text',
                        'extra' => [
                            'data-powertype' => $powerType,
                            'class' => 'character-autocomplete'
                        ],
                        'value' => 'PowerName'
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'id',
                        'value' => 'Id',
                    ]
                ]
            ],
            'note' => [
                'header' => 'Note',
                'inputs' => [
                    [
                        'type' => 'text',
                        'name' => 'note',
                        'value' => 'PowerNote'
                    ]
                ]
            ],
            'levelselect' => [
                'header' => 'Level',
                'extra' => [
                    'class' => 'power-level-column',
                    'html_before' => '<label class="hide-for-large-only">Level</label>'
                ],
                'inputs' => [
                    [
                        'type' => 'select',
                        'name' => 'level',
                        'value' => 'PowerLevel',
                        'range' => range(0, $this->maxDots),
                    ]
                ]
            ],
            'leveltext' => [
                'header' => 'Level',
                'extra' => [
                    'class' => 'power-level-column',
                    'html_before' => '<label class="hide-for-large-only">Level</label>'
                ],
                'inputs' => [
                    [
                        'type' => 'text',
                        'name' => 'level',
                        'value' => 'PowerLevel',
                    ]
                ]
            ],
            'public' => [
                'header' => 'Public',
                'translate' => function ($val) {
                    return ($val) ? 'Yes' : 'No';
                },
                'extra' => [
                    'html_after' => '<div class="alert badge clickable remove-character-row" data-target-table=' . $tableId . '><i class="fi-minus"></i></div>',
                ],
                'inputs' => [
                    [
                        'type' => 'checkbox',
                        'name' => 'is_public',
                        'value' => 'IsPublic',
                    ]
                ]
            ],
        ];
        $defaultFields = ['name', 'note', 'levelselect', 'public'];
        $displayFields = [];
        if (!$columns) {
            foreach ($defaultFields as $field) {
                $displayFields[$field] = $fields[$field];
            }
        } else {
            foreach ($columns as $column => $options) {
                if (is_array($options)) {
                    if (isset($fields[$column])) {
                        $displayFields[$column] = array_merge($fields[$column], $options);
                    } else {
                        $displayFields[$column] = $options;
                    }
                } else {
                    if (isset($fields[$options])) {
                        $displayFields[$options] = $fields[$options];
                    } else {
                        throw new Exception('Unknown field type: ' . $options);
                    }
                }
            }
        }


        if (!count($displayFields)) {
            throw new Exception('No fields to display for: ' . $powerType);
        }

        ob_start();
        ?>
        <table id="<?php echo $tableId; ?>" class="stack">
            <thead>
            <tr>
                <?php foreach ($displayFields as $field): ?>
                    <th><?php echo $field['header']; ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <?php foreach ($character->getPowerList($powerType) as $i => $power): ?>
                <tr>
                    <?php foreach ($displayFields as $field): ?>
                        <td <?php if (isset($field['extra']['class'])) {
                            echo 'class="' . $field['extra']['class'] . '"';
                        } ?>>
                            <?php if (isset($field['extra']['html_before'])): ?>
                                <?php echo $field['extra']['html_before']; ?>
                            <?php endif; ?>
                            <?php foreach ($field['inputs'] as $input): ?>
                                <?php echo $this->renderInput($field, $powerType, $power, $i, $input); ?>
                            <?php endforeach; ?>
                            <?php if (isset($field['extra']['html_after'])): ?>
                                <?php echo $field['extra']['html_after']; ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
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

    private function renderInput($field, $powerType, CharacterPower $power, $i, $input)
    {
        switch ($input['type']) {
            case 'text':
                return $this->renderTextInput($field, $powerType, $power, $i, $input);
                break;
            case 'select':
                return $this->renderSelectInput($field, $powerType, $power, $i, $input);
                break;
            case 'hidden':
                return $this->renderHiddenInput($field, $powerType, $power, $i, $input);
                break;
            case 'checkbox':
                return $this->renderCheckboxInput($field, $powerType, $power, $i, $input);
                break;
            default:
                return 'Unknown Type: ' . $input['type'];
        }
    }

    private function renderTextInput($field, $powerType, CharacterPower $power, $i, $input)
    {
        $value = $this->getValueFromPower($power, $input['value']);
        if ($this->mayEditOpen()) {
            $inputOptions = [
                'value' => $value,
                'label' => false,
                'type' => $input['type'],
                'maxlength' => 255,
            ];
            if (isset($input['extra']) && is_array($input['extra'])) {
                $inputOptions = array_merge($inputOptions, $input['extra']);
            }
            return $this->Form->control($powerType . '.' . $i . '.' . $input['name'], $inputOptions);
        } else {
            if ($field['translate']) {
                $value = $field['translate']($value);
            }
            return $value;
        }
    }

    private function renderSelectInput($field, $powerType, $power, $i, $input)
    {
        $value = $this->getValueFromPower($power, $input['value']);
        if ($this->mayEditOpen()) {
            $inputOptions = [
                'value' => $value,
                'label' => false,
                'empty' => false
            ];
            if (isset($input['extra']) && is_array($input['extra'])) {
                $inputOptions = array_merge($inputOptions, $input['extra']);
            }
            return $this->Form->select(
                $powerType . '.' . $i . '.' . $input['name'],
                $input['range'],
                $inputOptions
            );
        } else {
            if ($field['translate']) {
                $value = $field['translate']($value);
            }
            return $value;
        }
    }

    private function renderHiddenInput($field, $powerType, $power, $i, $input)
    {
        $value = $this->getValueFromPower($power, $input['value']);
        if ($this->mayEditOpen()) {
            $inputOptions = [
                'value' => $value,
                'type' => 'hidden'
            ];
            if (isset($input['extra']) && is_array($input['extra'])) {
                $inputOptions = array_merge($inputOptions, $input['extra']);
            }
            return $this->Form->control(
                $powerType . '.' . $i . '.' . $input['name'],
                $inputOptions
            );
        } else {
            return '';
        }
    }

    private function renderCheckboxInput($field, $powerType, $power, $i, $input)
    {
        $value = $this->getValueFromPower($power, $input['value']);
        if ($this->mayEditOpen()) {
            $inputOptions = [
                'value' => $value,
                'type' => 'checkbox',
                'checked' => ($value > 0),
                'label' => false,
                'div' => false
            ];
            if (isset($input['extra']) && is_array($input['extra'])) {
                $inputOptions = array_merge($inputOptions, $input['extra']);
            }
            return $this->Form->control(
                $powerType . '.' . $i . '.' . $input['name'],
                $inputOptions
            );
        } else {
            return '';
        }
    }

    private function getValueFromPower(CharacterPower $power, $property)
    {
        if (strpos($property, '.') !== false) {
            $parts = explode('.', $property);
            return $power->{$parts[0]}[$parts[1]];
        } else {
            return $power->$property;
        }
    }

    private function makeWolfRenownRow(Character $character, $index, $renownLabel, $renownKey)
    {
        ob_start();
        ?>
        <tr>
            <td>
                <?php echo $renownLabel; ?>
                <?php if ($this->mayEditOpen()): ?>
                    <?php echo $this->Form->control('renown.' . $index . '.name', [
                        'value' => $renownKey,
                        'type' => 'hidden'
                    ]); ?>
                    <?php echo $this->Form->control('renown.' . $index . '.id', [
                        'type' => 'hidden',
                        'value' => $character->getPowerByTypeAndName('renown', $renownKey)->PowerLevel
                    ]); ?>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($this->mayEditOpen()): ?>
                    <?php echo $this->Form->select(
                        'renown.' . $index . '.level',
                        range(0, 5),
                        [
                            'value' => $character->getPowerByTypeAndName('renown', $renownKey)->PowerLevel,
                            'label' => false,
                            'empty' => false
                        ]
                    );
                    ?>
                <?php else: ?>
                    <?php echo $character->getPowerByTypeAndName('renown', $renownKey)->PowerLevel + 0; ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }
}

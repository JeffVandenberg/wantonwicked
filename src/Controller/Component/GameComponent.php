<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use OAuth\Common\Exception\Exception;

/**
 * Game component
 */
class GameComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    private $gameList = [
        '1' => [
            'name' => 'Wanton Wicked',
            'city' => 'miami',
        ],
        '2' => [
            'name' => 'Side Game',
            'city' => 'Side Game',
        ],
    ];

    public function setGame($gameId): ?int
    {
        $gameId = (int)$gameId;
        $this->getController()->request->getSession()->write(
            'Auth.Game.id',
            $gameId
        );
        return $gameId;
    }

    public function getGame(): ?int
    {
        return $this->getController()->request->getSession()->read('Auth.Game.id') ?? 1;
    }

    public function listGames(): array
    {
        $list = [];
        foreach($this->gameList as $key => $game) {
            $list[$key] = $game['name'];
        }

        return $list;
    }

    public function getGameInfo($gameId): array
    {
        $gameId = (int) $gameId;
        if(isset($this->gameList[$gameId])) {
            return $this->gameList[$gameId];
        }

        throw new Exception('Unknown Game ID: ' . $gameId);
    }
}

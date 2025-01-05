<?php declare(strict_types=1);
/**
 * Handles Themes
 * @link https://github.com/KornineQNetwork/Xevmium
 * @package kornineqnetwork\Xevmium
 */

namespace kornineqnetwork\Xevmium;
use kornineqnetwork\Xevmium\XevmiumException;

class ThemeManager {
    private string $currentTheme = 'blue';
    private XevmiumException $XevmiumException;
    private const THEMES = [
        'blue' => ['start' => '#4a90e2', 'end' => '#357abd'],
        'green' => ['start' => '#2ecc71', 'end' => '#27ae60'],
        'purple' => ['start' => '#9b59b6', 'end' => '#8e44ad'],
        'orange' => ['start' => '#e67e22', 'end' => '#d35400'],
        'red' => ['start' => '#e74c3c', 'end' => '#c0392b'],
        'teal' => ['start' => '#1abc9c', 'end' => '#16a085'],
        'pink' => ['start' => '#ff6f61', 'end' => '#ff3b30'],
        'yellow' => ['start' => '#f1c40f', 'end' => '#f39c12'],
        'gray' => ['start' => '#95a5a6', 'end' => '#7f8c8d'],
        'indigo' => ['start' => '#3f51b5', 'end' => '#303f9f'],
        'cyan' => ['start' => '#00bcd4', 'end' => '#0097a7'],
        'amber' => ['start' => '#ffc107', 'end' => '#ffa000'],
        'lime' => ['start' => '#cddc39', 'end' => '#afb42b'],
        'brown' => ['start' => '#795548', 'end' => '#5d4037'],
        'deepPurple' => ['start' => '#673ab7', 'end' => '#512da8']
    ];

    public function __construct() {
        $this->XevmiumException = new XevmiumException();
    }

    public function setTheme(string $themeName): void {
        $themeName = htmlspecialchars($themeName, ENT_QUOTES, 'UTF-8');
        if (!isset(self::THEMES[$themeName])) {
            try {
                throw new \Exception("Invalid theme: $themeName");
            } catch (\Exception $e) {
                $this->XevmiumException->handle($e);
                $this->currentTheme = 'blue';
                return;
            }
        }
        $this->currentTheme = $themeName;
    }

    public function getCurrentThemeColors(): array {
        return self::THEMES[$this->currentTheme] ?? self::THEMES['blue'];
    }

    public function getAvailableThemes(): array {
        return array_keys(self::THEMES);
    }
}
?>
<?php declare(strict_types=1);
/**
 * Xevmium - A PHP Website Builder
 * 
 * This is the main class of the Xevmium framework that handles the website building process.
 * It provides methods for creating responsive, theme-based websites with built-in XSS protection.
 * 
 * Features:
 * - Responsive design with customizable themes
 * - Built-in XSS protection
 * - Navigation management
 * - Custom JavaScript integration
 * - Component-based architecture
 * 
 * @package kornineqnetwork\Xevmium
 * @link https://github.com/KornineQNetwork/Xevmium
 * @version 1.0
 */

namespace kornineqnetwork\Xevmium;
use kornineqnetwork\Xevmium\ThemeManager;
use kornineqnetwork\Xevmium\Exception;

class Xevmium {
    private $navItems = [];
    private $pageTitle;
    private $fileTitle;
    private $theme = 'blue';
    private $customJavaScript = [];
    private ThemeManager $themeManager;
    private XevmiumException $XevmiumException; 

    /**
     * Creates a new Xevmium instance
     * 
     * @param string $pageTitle The title of the website
     * @param string $fileTitle The title of the current page
     */
    public function __construct($pageTitle = 'My Website', $fileTitle = 'Home') {
        $this->pageTitle = $pageTitle;
        $this->fileTitle = $fileTitle;
        $this->themeManager = new ThemeManager();
        $this->XevmiumException = new XevmiumException();
    }

    /**
     * Sets the theme for the website
     * 
     * @param string $themeName Name of the theme from available themes
     * @throws XevmiumException if theme doesn't exist
     */
    public function setTheme(string $themeName): void {
        $this->themeManager->setTheme($themeName);
    }

    /**
     * Adds a navigation item to the website's menu
     * 
     * @param string $url The URL for the navigation item
     * @param string $title The display text for the navigation item
     */
    public function addNavItem($url, $title) {
        $this->navItems[] = [
            'url' => $this->sanitizeHtml($url),
            'title' => $this->sanitizeHtml($title)
        ];
    }

    public function addJavaScript($code) {
        $this->customJavaScript[] = $this->sanitizeHtml($code);
    }

    public function buildHeader() {
        if (headers_sent()) {
            return;
        }
        $themeColors = $this->themeManager->getCurrentThemeColors();
        $navHtml = $this->buildNavigation();
        $header = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>{$this->pageTitle} - {$this->fileTitle}</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <style>
                :root {
                    --theme-start: {$themeColors['start']};
                    --theme-end: {$themeColors['end']};
                    --button-text-color: #fff;
                }
                body {
                    background-color: #f8f9fa;
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                    font-size: 16px;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    line-height: 1.5;
                }
                a {
                    color: var(--theme-start);
                    text-decoration: none;
                    transition: color 0.2s ease;
                }
                a:hover {
                    color: var(--theme-end);
                }
                #container {
                    max-width: 1200px;
                    margin: 20px auto;
                    background-color: #fff;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                    border-radius: 12px;
                    overflow: hidden;
                }
                #header {
                    background: linear-gradient(135deg, var(--theme-start), var(--theme-end));
                    color: #fff;
                    padding: 2rem;
                    text-align: center;
                    border-radius: 12px 12px 0 0;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                }
                #header h1 {
                    margin: 0;
                    font-weight: 600;
                    font-size: 2.5rem;
                }
                #navigation {
                    background: #fff;
                    padding: 1rem;
                    text-align: center;
                    border-bottom: 1px solid #eee;
                    position: sticky;
                    top: 0;
                    z-index: 1000;
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                }
                #navigation a {
                    color: #444;
                    text-decoration: none;
                    padding: 0.5rem 1.25rem;
                    margin: 0 0.5rem;
                    border-radius: 25px;
                    transition: all 0.2s ease;
                    font-weight: 500;
                }
                #navigation a:hover {
                    background: var(--theme-start);
                    color: #fff;
                }
                #content {
                    padding: 2rem;
                    min-height: 500px;
                }
                #footer {
                    background: #f8f9fa;
                    text-align: center;
                    padding: 1.5rem;
                    border-top: 1px solid #eee;
                    border-radius: 0 0 12px 12px;
                    color: #666;
                    box-shadow: 0 -4px 10px rgba(0,0,0,0.05);
                }
                .kb-button {
                    display: inline-block;
                    padding: 0.75rem 1.5rem;
                    background: linear-gradient(to bottom, var(--theme-start), var(--theme-end));
                    border: none;
                    border-radius: 25px;
                    color: var(--button-text-color);
                    text-decoration: none;
                    font-weight: 500;
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    margin: 0.5rem;
                }
                .kb-button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
                    color: var(--button-text-color);
                }
                .kb-center {
                    text-align: center;
                    width: 100%;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 1rem 0;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }
                table th, table td {
                    padding: 0.75rem;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                table th {
                    background-color: var(--theme-start);
                    color: #fff;
                }
                table tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                @media (max-width: 768px) {
                    #container {
                        margin: 0;
                        border-radius: 0;
                    }
                    #header {
                        border-radius: 0;
                    }
                    #navigation a {
                        display: block;
                        margin: 0.5rem 0;
                    }
                    #content {
                        padding: 1rem;
                    }
                }
            </style>
        </head>
        <body>
            <div id="container">
                <div id="header">
                    <h1>{$this->pageTitle}</h1>
                </div>
                <div id="navigation">
                    {$navHtml}
                </div>
                <div id="content">
HTML;
        echo $header;
    }

    public function buildFooter($copyright = '') {
        $year = date('Y');
        $jsCode = '';
        if (!empty($this->customJavaScript)) {
            $jsCode = "<script>\n" . implode("\n", $this->customJavaScript) . "\n</script>";
        }
        
        $footer = <<<HTML
                </div>
                <div id="footer">
                    <div>&copy; {$year} {$copyright} - Built with <a href="https://github.com/KornineQNetwork/Xevmium">Xevmium v1.0</a></div>
                    <a href="#top" class="kb-button" style="margin-top: 1rem;">↑ Back to Top</a>
                </div>
            </div>
            {$jsCode}
        </body>
        </html>
HTML;
        echo $footer;
    }

    public function addContent($content) {
        echo $content;
    }

    /**
     * Creates an HTML button with theme-consistent styling
     * 
     * @param string $text Button text
     * @param string $url Button destination URL
     * @param string $additionalClasses Additional CSS classes
     * @param array $dataAttributes Data attributes for the button
     */
    public function createButton($text, $url = '#', $additionalClasses = '', $dataAttributes = []) {
        $dataAttr = '';
        foreach ($dataAttributes as $key => $value) {
            $dataAttr .= " data-{$key}=\"{$this->sanitizeHtml($value)}\"";
        }
        echo "<a href=\"{$this->sanitizeHtml($url)}\" class=\"kb-button {$this->sanitizeHtml($additionalClasses)}\"{$dataAttr}>{$this->sanitizeHtml($text)}</a>";
    }

    public function center($action) {
        if ($action !== 'start' && $action !== 'stop') {
            try {
                throw new \Exception("Invalid center action. Use 'start' or 'stop'.");
            } catch (\Exception $e) {
                $this->XevmiumException->handle($e);
            }
        }
        
        if ($action === 'start') {
            echo '<div class="kb-center">';
        } else {
            echo '</div>';
        }
    }

    public function sanitizeHtml($text) {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function addCustomStyles() {
        $this->customJavaScript[] = <<<JS
        const style = document.createElement('style');
        style.textContent = `
            .markdown-content h1 {
                font-size: 2.5em;
                margin: 1.5em 0 0.5em;
                padding-bottom: 0.3em;
                border-bottom: 2px solid var(--theme-start);
            }
            .markdown-content h2 {
                font-size: 2em;
                margin: 1.3em 0 0.5em;
                padding-bottom: 0.2em;
                border-bottom: 1px solid var(--theme-end);
            }
            .markdown-content h3 {
                font-size: 1.5em;
                margin: 1em 0 0.5em;
            }
            .markdown-content ul {
                padding-left: 2em;
                margin: 1em 0;
            }
            .markdown-content li {
                margin: 0.5em 0;
                line-height: 1.6;
            }
            .markdown-content p {
                margin: 1em 0;
                line-height: 1.6;
            }
        `;
        document.head.appendChild(style);
        JS;
    }

    private function buildNavigation(): string {
        $navHtml = '';
        foreach ($this->navItems as $item) {
            $navHtml .= "<a href=\"{$item['url']}\">{$item['title']}</a>\n";
        }
        return $navHtml;
    }

    
    public function addImage(string $src, string $alt = '', int $width = 0, int $height = 0): void {
        $widthAttr = $width > 0 ? " width=\"{$width}\"" : '';
        $heightAttr = $height > 0 ? " height=\"{$height}\"" : '';
        echo "<img src=\"{$this->sanitizeHtml($src)}\" alt=\"{$this->sanitizeHtml($alt)}\"{$widthAttr}{$heightAttr} />";
    }

    public function addTable(array $headers, array $rows): void {
        $tableHtml = '<table>';
        $tableHtml .= '<thead><tr>';
        foreach ($headers as $header) {
            $tableHtml .= '<th>' . $this->sanitizeHtml($header) . '</th>';
        }
        $tableHtml .= '</tr></thead>';
        $tableHtml .= '<tbody>';
        foreach ($rows as $row) {
            $tableHtml .= '<tr>';
            foreach ($row as $cell) {
                $tableHtml .= '<td>' . $this->sanitizeHtml($cell) . '</td>';
            }
            $tableHtml .= '</tr>';
        }
        $tableHtml .= '</tbody></table>';
        echo $tableHtml;
    }
}
?>
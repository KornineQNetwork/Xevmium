<?php declare(strict_types=1);
/**
 * Xevmium Bootstrap Class
 * 
 * This class serves as the entry point for the Xevmium framework.
 * It handles:
 * - Framework initialization
 * - Dependency checks
 * - Quick start site building
 * - Theme management
 * 
 * Usage:
 * ```php
 * $app = new Run();
 * $app->register();
 * $app->buildSite(...);
 * ```
 * 
 * @package kornineqnetwork\Xevmium
 */

namespace kornineqnetwork\Xevmium;
use kornineqnetwork\Xevmium\XevmiumException;
use kornineqnetwork\Xevmium\ThemeManager;
use kornineqnetwork\Xevmium\Xevmium;
use kornineqnetwork\Xevmium\MarkdownParser;

class Run {
    private $registered = false;
    private XevmiumException $XevmiumException;
    private ThemeManager $themeManager;

    /**
     * Registers the framework and checks for required dependencies
     * 
     * @throws XevmiumException if required classes are missing
     * @return self
     */
    public function register() {
        if(!$this->registered) {
            $this->XevmiumException = new XevmiumException();
            if(!class_exists('kornineqnetwork\Xevmium\Xevmium')) {
                try {
                    throw new \Exception("Classes are missing!");
                } catch (\Exception $e) {
                    $this->XevmiumException->handle($e);
                }
            }
            if(!class_exists('kornineqnetwork\Xevmium\ThemeManager')) {
                try {
                    throw new \Exception("Classes are missing!");
                } catch (\Exception $e) {
                    $this->XevmiumException->handle($e);
                }
            }
            if(!class_exists('kornineqnetwork\Xevmium\MarkdownParser')) {
                try {
                    throw new \Exception("Classes are missing!");
                } catch (\Exception $e) {
                    $this->XevmiumException->handle($e);
                }
            }
            $this->themeManager = new ThemeManager();
            $this->registered = true;
        }
        
        return $this;
    }

    public function build($pageTitle = 'My Website', $fileTitle = 'Home') {
        return new Xevmium($pageTitle, $fileTitle);
    }

    public function settheme($themeName) {
        $this->themeManager->setTheme($themeName);
    }

    /**
     * Quick method to build a complete website
     * 
     * @param string $pageTitle Website title
     * @param string $fileTitle Current page title
     * @param string $themeName Theme to use
     * @param array $navItems Navigation menu items
     * @param array $customJavaScript Custom JS to include
     * @param string $content HTML content
     * @param string $markdownFilePath Optional markdown file to parse
     * @param callable|null $customContentCallback Custom content generator
     * @param string $copyright Copyright text
     */
    public function buildSite($pageTitle = 'My Website', $fileTitle = 'Home', $themeName = 'blue', $navItems = [], $customJavaScript = [], $content = '', $markdownFilePath = '', $customContentCallback = null, $copyright = '') {
        $xevmium = new Xevmium($pageTitle, $fileTitle);
        $xevmium->setTheme($themeName);

        foreach ($navItems as $item) {
            $xevmium->addNavItem($item['url'], $item['title']);
        }

        foreach ($customJavaScript as $script) {
            $xevmium->addJavaScript($script);
        }

        $xevmium->buildHeader();

        if ($customContentCallback && is_callable($customContentCallback)) {
            call_user_func($customContentCallback, $xevmium);
        } elseif ($markdownFilePath && file_exists($markdownFilePath)) {
            $markdownParser = new MarkdownParser();
            $markdownParser->loadMarkdownPage($markdownFilePath);
        } else {
            $xevmium->addContent($content);
        }

        $xevmium->buildFooter($copyright);
    }
}
?>
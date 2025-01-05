<?php declare(strict_types=1);
/**
 * Markdown Parser
 * 
 * Provides simple markdown parsing capabilities for content creation.
 * Supports:
 * - Headers (H1-H3)
 * - Lists
 * - Bold and italic text
 * - Links
 * - Paragraphs
 * 
 * Usage:
 * ```php
 * $parser = new MarkdownParser();
 * $parser->loadMarkdownPage('content.md');
 * ```
 * 
 * @package kornineqnetwork\Xevmium
 */

namespace kornineqnetwork\Xevmium;
use kornineqnetwork\Xevmium\Xevmium;
use kornineqnetwork\Xevmium\Exception;

class MarkdownParser {
    private Xevmium $Xevmium;
    private string $fileTitle;
    private XevmiumException $XevmiumException; 
    public function __construct() {
        $this->Xevmium = new Xevmium();
        $this->XevmiumException = new XevmiumException();
    }

    /**
     * Converts markdown text to HTML
     * 
     * @param string $text Markdown formatted text
     * @return string HTML output
     */
    private function parseMarkdown($text) {
        $text = $this->Xevmium->sanitizeHtml($text);
        $html = $text;
        
        // Handle headers first
        $html = preg_replace('/^(.+)\n=+\s*$/m', '<h1>$1</h1>', $html);
        $html = preg_replace('/^(.+)\n-+\s*$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);
        
        // Handle lists
        $html = preg_replace('/^\* (.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(\n<li>.*<\/li>\n)/s', "\n<ul>\n$1</ul>\n", $html);
        
        // Other formatting
        $html = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $html);
        $html = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2">$1</a>', $html);
        
        // Handle paragraphs last, with modified regex
        $html = preg_replace('/^(?!<(?:h[1-6]|ul|li))[^\n](.+?)$/m', '<p>$0</p>', $html);
        $html = str_replace('<p></p>', '', $html);
        $html = preg_replace('/\n+/', "\n", $html);
        
        // Add markdown specific styles
        $this->Xevmium->addCustomStyles();
        
        return $html;
    }

    /**
     * Loads and parses a markdown file
     * 
     * @param string $filepath Path to the markdown file
     * @throws XevmiumException if file not found or invalid encoding
     */
    public function loadMarkdownPage($filepath) {
        if (!file_exists($filepath)) {
            try {
                throw new \Exception("Markdown file not found: $filepath");
            } catch (\Exception $e) {
                $this->XevmiumException->handle($e);
            }
        }
        $content = file_get_contents($filepath);
        if (!mb_check_encoding($content, 'UTF-8')) {
            try {
                throw new \Exception("Invalid UTF-8 encoding in file");
            } catch (\Exception $e) {
                $this->XevmiumException->handle($e);
            }
        }
        $this->fileTitle = pathinfo($filepath, PATHINFO_FILENAME);
        $parsedContent = $this->parseMarkdown($content);
        echo $parsedContent;
    }
}
?>
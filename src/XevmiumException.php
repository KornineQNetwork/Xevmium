<?php declare(strict_types=1);
/**
 * Xevmium - A PHP Website Builder
 * @link https://github.com/KornineQNetwork/Xevmium
 * @package kornineqnetwork\Xevmium
 */

namespace kornineqnetwork\Xevmium;
class XevmiumException extends \Exception {
    private static $isHandling = false;
    public function handle(\Exception $e) {
        if (self::$isHandling) {
            error_log("Nested exception occurred: " . $e->getMessage());
            die("Critical error occurred. Please check error logs.");
        }
        self::$isHandling = true;
        header('HTTP/1.1 500 Internal Server Error');
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>An Error Occurred</title>
            <meta charset="utf-8">
            <style>
                body { font-family: sans-serif; margin: 40px; }
                .error { color: #721c24; background: #f8d7da; padding: 20px; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="error">
                <h1>An Error Occurred</h1>
                <p>{$e->getMessage()}</p>
            </div>
        </body>
        </html>
HTML;
        
        self::$isHandling = false;
        exit();
    }
}
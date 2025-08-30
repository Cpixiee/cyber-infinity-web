<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CaptchaController extends Controller
{
    /**
     * Generate CAPTCHA image
     */
    public function generate()
    {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            abort(500, 'GD extension is not available');
        }

        // Generate random CAPTCHA text
        $characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $captchaText = '';
        for ($i = 0; $i < 5; $i++) {
            $captchaText .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Store CAPTCHA in session
        session(['captcha_text' => $captchaText]);

        // Create image
        $width = 150;
        $height = 50;
        $image = imagecreatetruecolor($width, $height);

        // Colors
        $bgColor = imagecolorallocate($image, 0, 0, 0); // Black background
        $textColor = imagecolorallocate($image, 0, 255, 0); // Green text
        $lineColor = imagecolorallocate($image, 0, 255, 255); // Cyan lines

        // Fill background
        imagefill($image, 0, 0, $bgColor);

        // Add noise lines
        for ($i = 0; $i < 10; $i++) {
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }

        // Add text
        $font = 5; // Built-in font
        $textWidth = imagefontwidth($font) * strlen($captchaText);
        $textHeight = imagefontheight($font);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;

        // Add each character with slight random positioning
        for ($i = 0; $i < strlen($captchaText); $i++) {
            $charX = $x + ($i * imagefontwidth($font)) + rand(-3, 3);
            $charY = $y + rand(-5, 5);
            imagechar($image, $font, $charX, $charY, $captchaText[$i], $textColor);
        }

        // Add noise dots
        for ($i = 0; $i < 100; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $height), $textColor);
        }

        // Output image
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);

        return response($imageData)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Verify CAPTCHA
     */
    public function verify(Request $request)
    {
        $userInput = strtoupper(trim($request->input('captcha')));
        $sessionCaptcha = strtoupper(session('captcha_text', ''));

        // Clear CAPTCHA from session after verification
        session()->forget('captcha_text');

        return response()->json([
            'valid' => $userInput === $sessionCaptcha && !empty($sessionCaptcha)
        ]);
    }
}

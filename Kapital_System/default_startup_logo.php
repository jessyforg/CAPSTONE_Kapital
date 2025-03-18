<?php
function getStartupLogo($logo_url, $size = 'medium') {
    $sizes = [
        'small' => ['width' => '50px', 'height' => '50px'],
        'medium' => ['width' => '100px', 'height' => '100px'],
        'large' => ['width' => '150px', 'height' => '150px']
    ];

    $dimensions = $sizes[$size] ?? $sizes['medium'];
    $style = "width: {$dimensions['width']}; height: {$dimensions['height']}; object-fit: cover; border-radius: 8px;";

    if (!empty($logo_url) && file_exists($logo_url)) {
        return "<img src='$logo_url' alt='Startup Logo' style='$style'>";
    } else {
        // Default logo styling
        $container_style = $style . "background-color: #2C2F33; border: 1px solid #40444B; display: flex; align-items: center; justify-content: center;";
        return "<div style='$container_style'><i class='fas fa-building' style='font-size: calc({$dimensions['width']} * 0.4); color: #7289DA;'></i></div>";
    }
}
?> 
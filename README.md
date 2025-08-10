# ShareMi Social Share

**Contributors:** GeoSn0w  
**Tags:** social share, social media, sharing buttons, lightweight, performance  
**Requires at least:** 5.0  
**Tested up to:** 6.4  
**Requires PHP:** 7.4  
**Stable tag:** 1.0.0  
**License:** GPL v2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

Ultra-fast, modern social sharing buttons with dark mode support and zero dependencies.

## Description

ShareMi Social Share is a lightweight WordPress plugin that adds beautiful, modern social sharing buttons to your posts. Built with performance in mind, it features zero external dependencies, inline CSS, and optimized SVG icons for maximum speed.

### Key Features

- **Ultra-lightweight**: Zero external dependencies, minimal footprint
- **Modern design**: Clean, contemporary styling with smooth animations
- **Dark mode support**: Automatic adaptation to dark themes
- **Fully responsive**: Perfect display on all device sizes
- **Six social platforms**: Twitter, Facebook, Discord, Reddit, Pinterest, Telegram
- **Flexible positioning**: Display buttons before or after content
- **Size options**: Small, medium, and large button sizes
- **Secure**: Built with WordPress security best practices
- **Easy configuration**: Simple admin interface for all settings

### Supported Platforms

- **Twitter**: Direct tweet sharing with post title and URL
- **Facebook**: Share to Facebook timeline
- **Discord**: Copy link to clipboard for Discord sharing
- **Reddit**: Submit to Reddit with title and URL
- **Pinterest**: Pin with image (if featured image exists)
- **Telegram**: Share via Telegram messaging

### Performance Optimized

ShareMi Social Share is designed for speed:
- Inline CSS (no external stylesheets)
- Minified styles (70% size reduction)
- Optimized SVG icons (no HTTP requests)
- Conditional loading (styles only when needed)
- Zero JavaScript dependencies

## Installation

### Automatic Installation

1. Login to your WordPress admin panel
2. Navigate to Plugins > Add New
3. Search for "ShareMi Social Share"
4. Click "Install Now" and then "Activate"

### Manual Installation

1. Download the plugin files
2. Upload the `sharemi-social-share` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure settings via Settings > ShareMi Social Share

## Configuration

After activation, configure the plugin:

1. Go to **Settings > ShareMi Social Share**
2. Enable/disable the plugin
3. Choose button position (before or after content)
4. Select button size (small, medium, large)
5. Choose which social platforms to display
6. Save your settings

## Usage

### Automatic Display

Once configured, social sharing buttons will automatically appear on all single posts according to your position setting.

### Manual Placement

Use the shortcode `[sharemi_share]` to manually place sharing buttons anywhere in your posts or pages.

### Theme Integration

For developers who want to add sharing buttons programmatically:

```php
echo do_shortcode('[sharemi_share]');
```

## Customization

### CSS Customization

The plugin uses CSS custom properties for easy theming:

```css
:root {
  --sharemi-bg: #f8f9fa;        /* Background color */
  --sharemi-text: #333;         /* Text color */
}
```

### Dark Mode

ShareMi automatically detects and adapts to dark themes using:
- `prefers-color-scheme: dark` media query
- `.dark` class support (Tailwind CSS)
- `[data-theme="dark"]` attribute support
- `.dark-mode` body class support

## Browser Compatibility

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Internet Explorer 11+ (limited support)

## Security

ShareMi Social Share follows WordPress security best practices:
- Input sanitization and validation
- Output escaping
- Capability checks
- Nonce verification
- CSRF protection

## Changelog

### 1.0.0
- Initial release
- Six social platforms support
- Dark mode compatibility
- Responsive design
- Admin configuration panel
- Shortcode support
- Performance optimization

## Frequently Asked Questions

### Does this plugin slow down my website?

No. ShareMi Social Share is specifically designed for maximum performance with inline CSS, optimized SVGs, and zero external dependencies.

### Can I customize the button colors?

The plugin maintains each platform's brand colors for consistency. However, you can override styles using custom CSS if needed.

### Does it work with dark themes?

Yes. ShareMi automatically detects and adapts to dark themes using multiple detection methods.

### Can I choose which platforms to display?

Yes. The admin panel allows you to enable/disable any of the six supported platforms.

### Is the plugin GDPR compliant?

Yes. The plugin doesn't collect any user data or set cookies. All sharing happens directly through social platform URLs.

## Support

For support, feature requests, or bug reports:

- **Official Plugin Site**: [https://geosn0w.com](https://geosn0w.com)
- **Company Website**: [https://idevicecentral.com](https://idevicecentral.com)
- **Developer Twitter**: [@FCE365](https://twitter.com/FCE365)

## Contributing

ShareMi Social Share is open source. Contributions are welcome via pull requests.

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Credits

**Developer**: GeoSn0w  
**Company**: [iDevice Central](https://idevicecentral.com)  
**Website**: [https://geosn0w.com](https://geosn0w.com)****

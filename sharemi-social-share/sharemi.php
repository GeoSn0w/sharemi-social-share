<?php
/**
 * Plugin Name: ShareMi Social Share
 * Plugin URI: https://geosn0w.com
 * Description: Ultra-fast, modern social sharing buttons with dark mode support. Zero dependencies, maximum performance.
 * Version: 1.0.1
 * Author: GeoSn0w (iDevice Central)
 * Author URI: https://geosn0w.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sharemi-social-share
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

class ShareMiSocialShare {
    
    private $version = '1.0.1';
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        add_filter('the_content', array($this, 'add_social_buttons'));
        add_shortcode('sharemi_share', array($this, 'render_buttons'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('wp_head', array($this, 'add_inline_styles'));
    }
    
    public function add_social_buttons($content) {
        if (is_single() && is_main_query() && $this->is_enabled()) {
            $position = get_option('sharemi_position', 'after');
            $buttons = $this->render_buttons();
            
            return $position === 'before' ? $buttons . $content : $content . $buttons;
        }
        return $content;
    }
    
    public function render_buttons($atts = array()) {
        global $post;
        
        if (!$post) return '';
        
        $platforms = get_option('sharemi_platforms', array(
            'twitter', 'facebook', 'discord', 'reddit', 'pinterest', 'telegram'
        ));
        $style = get_option('sharemi_style', 'modern');
        $size = get_option('sharemi_size', 'medium');
        
        $post_url = esc_url(get_permalink());
        $post_url_encoded = urlencode($post_url);
        
        $raw_title = get_the_title();
        $clean_title = wp_strip_all_tags($raw_title);
        $clean_title = html_entity_decode($clean_title, ENT_QUOTES, 'UTF-8');
        
        $title_for_url = urlencode($clean_title);
        $title_for_display = esc_attr($clean_title);
        
        $featured_image = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url()) : '';
        
        $urls = array(
            'twitter' => "https://twitter.com/intent/tweet?url={$post_url_encoded}&text={$title_for_url}",
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$post_url_encoded}",
            'discord' => "javascript:void(0);",
            'reddit' => "https://reddit.com/submit?url={$post_url_encoded}&title={$title_for_url}",
            'pinterest' => "https://pinterest.com/pin/create/button/?url={$post_url_encoded}&description={$title_for_url}" . ($featured_image ? "&media={$featured_image}" : ""),
            'telegram' => "https://t.me/share/url?url={$post_url_encoded}&text={$title_for_url}"
        );
        
        $icons = array(
            'twitter' => '<svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            'facebook' => '<svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            'discord' => '<svg viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.21.375-.444.865-.608 1.25-1.845-.276-3.68-.276-5.487 0-.164-.393-.406-.875-.618-1.25a.077.077 0 00-.078-.037A19.736 19.736 0 003.677 4.37a.07.07 0 00-.032.028C.533 9.046-.319 13.58.099 18.058a.082.082 0 00.031.056 19.9 19.9 0 005.993 3.03.078.078 0 00.084-.028c.462-.63.873-1.295 1.226-1.994a.076.076 0 00-.042-.106 12.299 12.299 0 01-1.872-.892.077.077 0 01-.008-.128c.126-.094.252-.192.372-.291a.074.074 0 01.078-.01c3.927 1.793 8.18 1.793 12.061 0a.074.074 0 01.079.009c.12.099.246.198.373.292a.077.077 0 01-.006.127 12.3 12.3 0 01-1.873.892.076.076 0 00-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 00.084.029 19.95 19.95 0 006.002-3.03.077.077 0 00.032-.055c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 00-.031-.029zM8.02 15.331c-1.182 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.211 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.175 1.096 2.156 2.42 0 1.333-.946 2.418-2.156 2.418z"/></svg>',
            'reddit' => '<svg viewBox="0 0 24 24"><path d="M12 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 01-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 01.042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 014.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 01.14-.197.35.35 0 01.238-.042l2.906.617a1.214 1.214 0 011.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 00-.231.094.33.33 0 000 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 00.029-.463.33.33 0 00-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 00-.232-.095z"/></svg>',
            'pinterest' => '<svg viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.758-1.378l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/></svg>',
            'telegram' => '<svg viewBox="0 0 24 24"><path d="M20.665 3.717l-17.73 6.837c-1.21.486-1.203 1.161-.222 1.462l4.552 1.42 10.532-6.645c.498-.303.953-.14.579.192l-8.533 7.701h-.002l.002.001-.314 4.692c.46 0 .663-.211.921-.46l2.211-2.15 4.599 3.397c.848.467 1.457.227 1.668-.785L24 5.405c.309-1.239-.473-1.8-1.335-1.688z"/></svg>'
        );
        
        $labels = array(
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
            'discord' => 'Discord',
            'reddit' => 'Reddit',
            'pinterest' => 'Pinterest',
            'telegram' => 'Telegram'
        );
        
        ob_start();
        ?>
        <div class="sharemi-wrapper sharemi-<?php echo esc_attr($style); ?> sharemi-<?php echo esc_attr($size); ?>">
            <div class="sharemi-title">Share this post</div>
            <div class="sharemi-buttons">
                <?php foreach ($platforms as $platform): ?>
                    <?php if (isset($urls[$platform])): ?>
                        <a href="<?php echo esc_url($urls[$platform]); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="sharemi-btn sharemi-<?php echo esc_attr($platform); ?>"
                           title="Share on <?php echo esc_attr($labels[$platform]); ?>: <?php echo esc_attr(wp_trim_words($clean_title, 8)); ?>"
                           <?php echo $platform === 'discord' ? 'onclick="navigator.clipboard.writeText(\'' . esc_js($post_url) . '\').then(function(){alert(\'Link copied! Paste in Discord.\');}); return false;"' : ''; ?>>
                            <?php echo $icons[$platform]; ?>
                            <span><?php echo esc_html($labels[$platform]); ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function add_inline_styles() {
        if (!$this->should_load_styles()) return;
        
        $style = get_option('sharemi_style', 'modern');
        ?>
        <style id="sharemi-styles">
        .sharemi-wrapper{margin:30px 0;padding:20px;background:var(--sharemi-bg,#f8f9fa);border-radius:12px;border-left:4px solid #007cba;transition:all .3s ease}
        .sharemi-title{margin:0 0 15px;font-size:16px;font-weight:600;color:var(--sharemi-text,#333);opacity:.8}
        .sharemi-buttons{display:flex;flex-wrap:wrap;gap:8px}
        .sharemi-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 12px;text-decoration:none!important;border-radius:8px;font-size:13px;font-weight:500;transition:all .2s cubic-bezier(.4,0,.2,1);color:#fff!important;border:0;cursor:pointer;position:relative;overflow:hidden}
        .sharemi-btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.15);text-decoration:none!important}
        .sharemi-btn:focus{text-decoration:none!important;outline:2px solid rgba(255,255,255,.5);outline-offset:2px}
        .sharemi-btn:visited{text-decoration:none!important}
        .sharemi-btn svg{width:16px;height:16px;fill:currentColor;flex-shrink:0}
        .sharemi-btn span{white-space:nowrap}
        
        .sharemi-twitter{background:#000}.sharemi-twitter:hover{background:#1a1a1a}
        .sharemi-facebook{background:#1877f2}.sharemi-facebook:hover{background:#166fe5}
        .sharemi-discord{background:#5865f2}.sharemi-discord:hover{background:#4752c4}
        .sharemi-reddit{background:#ff4500}.sharemi-reddit:hover{background:#e63e00}
        .sharemi-pinterest{background:#bd081c}.sharemi-pinterest:hover{background:#a0071b}
        .sharemi-telegram{background:#0088cc}.sharemi-telegram:hover{background:#007bb5}
        
        .sharemi-small .sharemi-btn{padding:6px 10px;font-size:12px}.sharemi-small .sharemi-btn svg{width:14px;height:14px}
        .sharemi-large .sharemi-btn{padding:10px 16px;font-size:14px}.sharemi-large .sharemi-btn svg{width:18px;height:18px}
        
        @media (prefers-color-scheme:dark){
        .sharemi-wrapper{background:#1a1a1a;border-left-color:#4a9eff}
        .sharemi-title{color:#e0e0e0}
        }
        .dark .sharemi-wrapper,[data-theme=dark] .sharemi-wrapper,body.dark-mode .sharemi-wrapper{background:#1a1a1a!important;border-left-color:#4a9eff!important}
        .dark .sharemi-title,[data-theme=dark] .sharemi-title,body.dark-mode .sharemi-title{color:#e0e0e0!important}
        
        @media (max-width:768px){
        .sharemi-buttons{justify-content:center}
        .sharemi-btn{flex:1;min-width:90px;justify-content:center}
        }
        @media (max-width:480px){
        .sharemi-btn{font-size:11px;padding:6px 8px;min-width:80px}
        .sharemi-btn svg{width:14px;height:14px}
        }
        </style>
        <?php
    }
    
    private function should_load_styles() {
        return is_single() || is_page() || (is_admin() && function_exists('get_current_screen') && get_current_screen() && get_current_screen()->id === 'settings_page_sharemi-social-share');
    }
    
    private function is_enabled() {
        return get_option('sharemi_enabled', true);
    }
    
    public function add_admin_menu() {
        add_options_page(
            'ShareMi Social Share Settings',
            'ShareMi Social Share',
            'manage_options',
            'sharemi-social-share',
            array($this, 'admin_page')
        );
    }
    
    public function settings_init() {
        register_setting('sharemi_settings', 'sharemi_enabled', array(
            'type' => 'boolean',
            'sanitize_callback' => array($this, 'sanitize_boolean')
        ));
        register_setting('sharemi_settings', 'sharemi_platforms', array(
            'type' => 'array',
            'sanitize_callback' => array($this, 'sanitize_platforms')
        ));
        register_setting('sharemi_settings', 'sharemi_position', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitize_position')
        ));
        register_setting('sharemi_settings', 'sharemi_style', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitize_style')
        ));
        register_setting('sharemi_settings', 'sharemi_size', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitize_size')
        ));
    }
    
    public function sanitize_boolean($input) {
        return (bool) $input;
    }
    
    public function sanitize_platforms($input) {
        $allowed = array('twitter', 'facebook', 'discord', 'reddit', 'pinterest', 'telegram');
        if (!is_array($input)) return array();
        return array_intersect($input, $allowed);
    }
    
    public function sanitize_position($input) {
        return in_array($input, array('before', 'after')) ? $input : 'after';
    }
    
    public function sanitize_style($input) {
        return in_array($input, array('modern', 'minimal')) ? $input : 'modern';
    }
    
    public function sanitize_size($input) {
        return in_array($input, array('small', 'medium', 'large')) ? $input : 'medium';
    }
    
    public function admin_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        if (isset($_POST['submit'])) {
            check_admin_referer('sharemi_settings_nonce');
            
            update_option('sharemi_enabled', isset($_POST['sharemi_enabled']));
            update_option('sharemi_platforms', isset($_POST['sharemi_platforms']) ? $_POST['sharemi_platforms'] : array());
            update_option('sharemi_position', isset($_POST['sharemi_position']) ? $_POST['sharemi_position'] : 'after');
            update_option('sharemi_style', isset($_POST['sharemi_style']) ? $_POST['sharemi_style'] : 'modern');
            update_option('sharemi_size', isset($_POST['sharemi_size']) ? $_POST['sharemi_size'] : 'medium');
            echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
        }
        
        $enabled = get_option('sharemi_enabled', true);
        $platforms = get_option('sharemi_platforms', array('twitter', 'facebook', 'discord', 'reddit', 'pinterest', 'telegram'));
        $position = get_option('sharemi_position', 'after');
        $style = get_option('sharemi_style', 'modern');
        $size = get_option('sharemi_size', 'medium');
        ?>
        <div class="wrap">
            <h1>ShareMi Social Share Settings</h1>
            
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 12px; margin: 20px 0; color: white; box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="flex: 1;">
                        <h2 style="margin: 0 0 10px; font-size: 24px; color: white;">ShareMi Social Share</h2>
                        <p style="margin: 0 0 15px; opacity: 0.9; font-size: 16px;">Ultra-fast social sharing plugin with modern design</p>
                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                            <a href="https://geosn0w.com" target="_blank" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">🌐 Official Plugin Site</a>
                            <a href="https://idevicecentral.com" target="_blank" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">📱 iDevice Central</a>
                            <a href="https://twitter.com/FCE365" target="_blank" style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 500; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">🐦 @FCE365</a>
                        </div>
                    </div>
                    <div style="font-size: 48px; opacity: 0.3;">🚀</div>
                </div>
            </div>
            
            <form method="post">
                <?php wp_nonce_field('sharemi_settings_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th>Enable Social Share</th>
                        <td><input type="checkbox" name="sharemi_enabled" <?php checked($enabled); ?> /></td>
                    </tr>
                    <tr>
                        <th>Position</th>
                        <td>
                            <select name="sharemi_position">
                                <option value="after" <?php selected($position, 'after'); ?>>After Content</option>
                                <option value="before" <?php selected($position, 'before'); ?>>Before Content</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Size</th>
                        <td>
                            <select name="sharemi_size">
                                <option value="small" <?php selected($size, 'small'); ?>>Small</option>
                                <option value="medium" <?php selected($size, 'medium'); ?>>Medium</option>
                                <option value="large" <?php selected($size, 'large'); ?>>Large</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Platforms</th>
                        <td>
                            <?php
                            $all_platforms = array('twitter', 'facebook', 'discord', 'reddit', 'pinterest', 'telegram');
                            foreach ($all_platforms as $platform) {
                                $checked = in_array($platform, $platforms) ? 'checked' : '';
                                echo "<label style='display: inline-block; margin-right: 15px; margin-bottom: 5px;'><input type='checkbox' name='sharemi_platforms[]' value='{$platform}' {$checked}> " . ucfirst($platform) . "</label>";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px;">
                <h3>Usage</h3>
                <p><strong>Automatic:</strong> Buttons appear automatically on single posts when enabled.</p>
                <p><strong>Shortcode:</strong> Use <code>[sharemi_share]</code> anywhere in posts/pages for manual placement.</p>
                <p><strong>Performance:</strong> Zero external dependencies, inline CSS, optimized SVGs for maximum speed.</p>
            </div>
        </div>
        <?php
    }
}

new ShareMiSocialShare();
?>

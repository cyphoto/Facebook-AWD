<?php
/**
 * View Admin template
 * @author AHWEBDEV (Alexandre Hermann) [hermann.alexandre@ahwebev.fr]
 */
global $screen_layout_columns;
$page = $_GET['page'];
$page_hook = $this->getAdminMenuHook();

if ($page == self::PLUGIN_SLUG . '_open_graph') {
    $page_hook = $this->getOpengraphMenuHook();
} elseif ($page == self::PLUGIN_SLUG . '_plugins') {
    $page_hook = $this->getPluginsMenuHook();
} else {
    $plugins = $this->plugins;
    foreach ($plugins as $plugin) {
        if ($page == $plugin::PLUGIN_SLUG) {
            $page_hook = $plugin->getAdminMenuHook();
        }
    }
}
$current_screen = get_current_screen();
?>
<style>
    #wpwrap,.header_lightbox_help {
        background: linear-gradient(left, rgb(33,113,148) 25%, rgb(21,83,110) 63%, rgb(3,68,98) 82%);
        background: -o-linear-gradient(left, rgb(33,113,148) 25%, rgb(21,83,110) 63%, rgb(3,68,98) 82%);
        background: -moz-linear-gradient(left, rgb(33,113,148) 25%, rgb(21,83,110) 63%, rgb(3,68,98) 82%);
        background: -webkit-linear-gradient(left, rgb(33,113,148) 25%, rgb(21,83,110) 63%, rgb(3,68,98) 82%);
        background: -ms-linear-gradient(left, rgb(33,113,148) 25%, rgb(21,83,110) 63%, rgb(3,68,98) 82%);
        background: -webkit-gradient(
            linear,
            left,
            right top,
            color-stop(0.25, rgb(33,113,148)),
            color-stop(0.63, rgb(21,83,110)),
            color-stop(0.82, rgb(3,68,98))
            );
    }
    #footer{
        background-color: #fff;
        padding: 10px;
        -webkit-border-radius: 10px 10px 0px 0px;
        border-radius: 10px 10px 0px 0px;
    }
</style>
<div class="wrap AWD_facebook_wrap">
    <div id="logo_facebook_awd"></div>
    <div class="navbar primary">
        <div class="navbar-inner">
            <div class="container">
                <ul class="nav">
                    <?php
                    global $submenu;
                    if (isset($submenu[self::PLUGIN_SLUG])) {
                        foreach ($submenu[self::PLUGIN_SLUG] as $page) {
                            if (current_user_can($page[1])) {
                                echo '
                                    <li ' . ($_GET['page'] == $page[2] ? 'class="active"' : '') . '>
                                        <a href="' . admin_url('admin.php?page=' . $page[2]) . '" title="' . $page[3] . '">' . $page[0] . '</a>
                                    </li>';
                            }
                        }
                    }
                    ?>
                    <li><a href="http://facebook-awd.ahwebdev.fr/documentation/" target="blank" title="Documentation">Documentation</a></li>
                    <!-- <li><a href="http://facebook-awd.ahwebdev.fr/support/" target="blank" title="Support">Support</a></li> -->
                    <?php ?>
                </ul>
                <form class="navbar-search pull-right" action="http://facebook-awd.ahwebdev.fr/" method="GET" target="_blank">
                    <input type="text" name="s" class="search-query span2" placeholder="Search">
                </form>
            </div>
        </div>
    </div>
    <div class="bgshadow"></div>
    <div id="AWD_facebook_notices">
        <?php
        do_action('AWD_facebook_admin_notices', true);
        ?>
    </div>
    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); ?>
        <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false); ?>
        <div id="side-info-column" class="inner-sidebar">
            <?php do_meta_boxes($page_hook, 'side', null); ?>
        </div>
        <div id="post-body" class="has-sidebar">
            <div id="post-body-content" class="has-sidebar-content">
                <?php
                do_meta_boxes($page_hook, 'normal', null);
                ?>
            </div>
        </div>
        <br class="clear"/>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // close postboxes that should be closed
                $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                // postboxes setup
                postboxes.add_postbox_toggles('<?php echo $page_hook; ?>');
            });
        </script>
    </div>
</div>
<div class="clear"></div>
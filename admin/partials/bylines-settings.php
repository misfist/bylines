<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/misfist
 * @since      1.0.0
 *
 * @package    Bylines
 * @subpackage Bylines/admin/partials
 */
?>

<div class="wrap">
  <h1><?php echo get_admin_page_title(); ?></h1>
  <form action="options.php" method="post">

    <?php
    settings_fields( 'site_config_group' );
    do_settings_sections( 'core_site_config' );
    submit_button();
    ?>

  </form>
</div>

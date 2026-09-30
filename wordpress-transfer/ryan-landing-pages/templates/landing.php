<?php
if (!defined('ABSPATH')) exit;
$ryan_css = ryan_lp_asset('index-*.css');
$ryan_js = ryan_lp_asset('index-*.js');
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="index,follow">
  <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">
  <title><?php echo esc_html(get_the_title()); ?> - مكتب ريان</title>
  <?php ryan_lp_tracking(); ?>
  <?php if ($ryan_css): ?><link rel="stylesheet" href="<?php echo esc_url($ryan_css); ?>?ver=<?php echo esc_attr(RYAN_LP_VERSION); ?>">
  <?php endif; ?>
</head>
<body class="ryan-lp-standalone">
  <div id="root"></div>
  <?php if ($ryan_js): ?><script>window.RYAN_ASSET_BASE=<?php echo wp_json_encode(RYAN_LP_URL); ?>;</script>
  <script type="module" src="<?php echo esc_url($ryan_js); ?>?ver=<?php echo esc_attr(RYAN_LP_VERSION); ?>"></script>
  <?php endif; ?>
</body>
</html>

<?php
/**
 * Fleet security hardening (pentest 2026-07-31).
 *
 * - Disable XML-RPC
 * - Restrict REST user enumeration
 * - Send security response headers / strip X-Powered-By
 * - Block author archive username leaks
 */

if (!defined('ABSPATH')) {
  exit;
}

// Disable XML-RPC entirely
add_filter('xmlrpc_enabled', '__return_false');

add_filter('wp_headers', function ($headers) {
  unset($headers['X-Pingback']);
  return $headers;
});

// Restrict REST user listing to users who can list users
add_filter('rest_endpoints', function ($endpoints) {
  if (current_user_can('list_users')) {
    return $endpoints;
  }

  if (isset($endpoints['/wp/v2/users'])) {
    unset($endpoints['/wp/v2/users']);
  }

  if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
    unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
  }

  return $endpoints;
});

// Security headers + remove PHP version disclosure
add_action('send_headers', function () {
  if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload', true);
    header('X-Frame-Options: SAMEORIGIN', true);
    header('X-Content-Type-Options: nosniff', true);
    header('Referrer-Policy: strict-origin-when-cross-origin', true);
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()', true);
  }
});

// Block author archive enumeration (?author=N)
add_action('template_redirect', function () {
  if (is_author() && !is_user_logged_in()) {
    wp_safe_redirect(home_url('/'), 301);
    exit;
  }
});

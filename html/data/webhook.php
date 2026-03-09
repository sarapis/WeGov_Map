<?php
/**
 * Webhook endpoint for Airtable Automations.
 * Usage: Set up an automation in Airtable to "Run a script" that calls this URL:
 * https://your-server.com/data/webhook.php?secret=YOUR_SECRET
 */

include '../../data_include/autoload.php';

$secret = defined('WEBHOOK_SECRET') ? WEBHOOK_SECRET : 'default_insecure_secret';

if (($getData['secret'] ?? $_GET['secret'] ?? '') !== $secret) {
    http_response_code(403);
    echo "Forbidden: Invalid Secret";
    exit;
}

// Log receipt
$logFile = __DIR__ . '/webhook.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Webhook received from " . $_SERVER['REMOTE_ADDR'] . "\n", FILE_APPEND);

// Trigger cache update in background
// Using shell_exec/exec to run independently of this request
$cmd = "nohup php " . __DIR__ . "/cacheupdate.php >> " . __DIR__ . "/../webhook_activity.log 2>&1 &";
exec($cmd);

echo "Update triggered in background.";

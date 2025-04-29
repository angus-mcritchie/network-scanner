<?php
header('Content-Type: application/json');

// Run arp -a and capture output
$output = shell_exec('arp -a');
$lines = explode("\n", trim($output));
$result = [];
foreach ($lines as $line) {
    // Typical line: ? (10.0.0.2) at XX:XX:XX:XX:XX:XX [ether] on eth0
    if (preg_match('/\(([^)]+)\) at ([0-9a-f:]+)/i', $line, $matches)) {
        $result[] = [
            'ip' => $matches[1],
            'mac' => $matches[2]
        ];
    }
}
echo json_encode($result, JSON_PRETTY_PRINT);

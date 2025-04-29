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

// add if they are printers or not
$output = shell_exec('nmap -n -Pn -T4 -p 9100 --open -oG - 10.0.0.0/24');
$lines = explode("\n", trim($output));

preg_match_all('/Host: ([0-9.]+) \(\)/', $output, $matches);
$printerIps = array_unique($matches[1]);


$result = array_map(function ($item) use ($printerIps) {
    $item['printer'] = in_array($item['ip'], $printerIps);
    return $item;
}, $result);

echo json_encode($result, JSON_PRETTY_PRINT);

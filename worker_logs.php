<?php
header("Content-Type: application/json;charset=utf-8");
// establecer aqui correctamente el camino al directorio de los logs
define('LOGS_DIR', __DIR__ . '/follows-worker/worker/log');
// establecer una palabra que debe contenet el nombre del log
define('LOG_TYPE', 'dumbo-worker');
$cmd = sprintf("ls -l %s | grep %s | awk '{print $9}'", LOGS_DIR, LOG_TYPE);
$output = shell_exec($cmd);
$filenames = preg_split('/\n/', $output, -1, PREG_SPLIT_NO_EMPTY);
$all_log_dates = array_map(function($filename) {
  preg_match("/(\d{4,}).*log/", $filename, $matches);
  return isset($matches[1]) ? $matches[1] : '';
}, $filenames);
$unique_log_dates = array_unique($all_log_dates);
$log_date_values = array_values($unique_log_dates);
$log_dates = array_reduce($log_date_values, function($dates, $date) {
  if (trim($date)!=='') {
    $dates[] = $date;
  }
  return $dates;
}, []);
echo json_encode($log_dates);
exit();


<?php
function do_log($type, $argument, $description = "")
{
    $argument = transform($argument, 1);
    global $GLOBALS;
    $str = "** LOGGER ** | " . date('y/m/y H:i:s');
    $suffix = 2;
    if (!is_dir(ABSPATH . "/wp-content/plugins/logger-plugin/logs/" . date('Y-m-d') . "/")) {
        mkdir(ABSPATH . "/wp-content/plugins/logger-plugin/logs/" . date('Y-m-d') . "/");
    }
    $filepath = ABSPATH . "/wp-content/plugins/logger-plugin/logs/" . date('Y-m-d') . '/' . date('Y-m-d') . '.txt';
    $file = fopen($filepath, 'a');
    while (filesize($filepath) > 1000000) {
        $filepath = ABSPATH . "/wp-content/plugins/logger-plugin/logs/" . date('Y-m-d') . '/' . date('Y-m-d') . '_' . $suffix++ . '.txt';
        $file = fopen($filepath, 'a');
    }
    $str .= ' | ' . strtoupper($type) . ' | ' . function_debug_tracing(debug_backtrace()) . (($description) ? ("- ".$description) : ('')) . ' ~ ' . $argument . "\n";
    //$str .= ' | '. strtoupper($type) . (($description) ? (' | ' . $description) : ('')) . ' ~ ' . $argument . "\n";
    fwrite($file, $str);
    fclose($file);
}

function function_debug_tracing($backtrace)
{
    $array = [];
    foreach ($backtrace as $action) {
        $array[] = $action['function'];
    }
    $max = count($array);
    if (count($array) > 7) {
        $max -= 7;
    }
    $array = array_slice(array_reverse($array), $max);
    return "-->" . implode('->', $array) . "\n\t";
}

function logger_error($argument, $description = "")
{
    do_log("ERROR", $argument, $description);
}

function logger_warning($argument, $description = "")
{
    do_log("WARNING", $argument, $description);
}

function logger_success($argument, $description = "")
{
    do_log("SUCCESS", $argument, $description);
}

function logger_info($argument, $description = "")
{
    do_log("INFO", $argument, $description);
}


function transform($argument, $tab): string
{
    $res = "";
    switch ($argument) {
        case null:
            return is_array($argument) ? "EMPTY ARRAY" : "NULL";
        case is_string($argument):
            return $argument;
        case is_array($argument):
            $res .= (($tab == 1) ? "\n" . tab($tab) . "\n" : "") . "Array\n";
            $tab++;
            foreach ($argument as $key => $arg) {
                $res .= tab($tab) . "[" . $key . "] : ";
                $arg = transform($arg, $tab);
                $res .= $arg . "\n";
            }
            break;
        case is_object($argument):
            $res .= "Object Class: '" . get_class($argument) . "'\n" . json_encode($argument, JSON_PRETTY_PRINT) . "\n";
            break;
        default:
    }
    return $res;
}

function tab($tab): string
{
    $res = "";
    while ($tab-- > 0) {
        $res .= "\t";
    }
    return $res;
}


function logger_list_folders(): array
{
    $res = [];
    $directory = ABSPATH . "/wp-content/plugins/logger-plugin/logs/";
    if (is_dir($directory)) {
        if ($handle = opendir($directory)) {
            while (($dirname = readdir($handle)) !== false) {
                if ($dirname != '.' && $dirname != '..') {
                    $res[] = $dirname;
                }
            }
        }
        closedir($handle);
    }
    return $res;
}

function logger_list_files($data)
{
    $folder = $data->get_params()['f'];
    $res = [];
    $directory = ABSPATH . "/wp-content/plugins/logger-plugin/logs/" . $folder;
    if (is_dir($directory)) {
        if ($handle = opendir($directory)) {
            while (($dirname = readdir($handle)) !== false) {
                if ($dirname != '.' && $dirname != '..') {
                    $res[] = $dirname;
                }
            }
        }
        closedir($handle);
    }
    if (!empty($res)) {
        return new WP_REST_Response(array(
            'status' => 200,
            'files' => $res
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => "Errore lettura files!"
        ));
    }

}


function logger_file_content($data)
{
    $fileName = $data->get_params()['f'];
    $res = "";
    $folder = substr($fileName, 0, 10);
    $path = ABSPATH . "wp-content/plugins/logger-plugin/logs/$folder/$fileName";
    if (($file = fopen($path, 'r')) !== false) {
        while (($line = fgets($file)) !== false) {
            $res .= $line;
        }
    }
    if (!empty($res)) {
        return new WP_REST_Response(array(
            'status' => 200,
            'content' => $res
        ));
    } else {
        return new WP_REST_Response(array(
            'status' => 200,
            'message' => "Errore lettura files!"
        ));
    }
}
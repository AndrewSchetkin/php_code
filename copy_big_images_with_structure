<?php

/*
* create folder2 from folder and copy all image files into it
* with save pathes
*/

function copyFileToDirectory2($root, $item)
{
    $path = explode(DIRECTORY_SEPARATOR, $item);
    $new_path = "";
    $path_last = count($path) - 1;
    foreach ($path as $k => $p) {
        if ($k < $path_last) {
            if (!$k) {
                $p = $p . "2";
            }
            $new_path .= $p . DIRECTORY_SEPARATOR;
            if (!is_dir($root . $new_path)) {
                mkdir($root . $new_path);
            }
        } else {
            copy($root . $item, $root . $new_path . $p);
        }
    }
}

function scan_tree($root, $folder)
{
    $files = scandir($folder);
    // Получаем массив папок и файлов в текущей папке
    foreach ($files as $file) {
        // В цикле обходим все папки и файлы в дериктории
        if (($file == '.') || ($file == '..')) continue;
        // Пропускаем текущую папку, родительскую папку и папки из фильтруемых
        $item = $folder . DIRECTORY_SEPARATOR . $file;
        // Формируем полный путь к папке или файлу
        if (is_dir($root . $item)) {
            // Если текущий элемент - папка, то рекурсивно вызываем функцию сканирования
            scan_tree($root, $item);
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $root . $item);
            // размер более 600 кб
            if (strpos($type, "image") === 0 && filesize($root . $item) > 600000) {
                copyFileToDirectory2($root, $item);
            }
        }
    }
}

// запускаем в директории с директорией, которую сканируем
scan_tree(__DIR__ . DIRECTORY_SEPARATOR, "images");

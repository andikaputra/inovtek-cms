<?php

if (! isset($config)) {
    $config = include 'config/config.php';
    //TODO switch to array
    extract($config, EXTR_OVERWRITE);
}
include 'include/utils.php';

if ($_SESSION['RF']['verify'] != 'RESPONSIVEfilemanager') {
    response(trans('forbiden').AddErrorLocation(), 403)->send();
    exit;
}

include 'include/mime_type_lib.php';

if (isset($_POST['path'])) {
    $storeFolder = $_POST['path'];
    $storeFolderThumb = $_POST['path_thumb'];
} else {
    $storeFolder = $current_path.$_POST['fldr']; // correct for when IE is in Compatibility mode
    $storeFolderThumb = $thumbs_base_path.$_POST['fldr'];
}

$path_pos = strpos($storeFolder, $current_path);
$thumb_pos = strpos($storeFolderThumb, $thumbs_base_path);

if ($path_pos !== 0
    || $thumb_pos !== 0
    || strpos($storeFolderThumb, '../', strlen($thumbs_base_path)) !== false
    || strpos($storeFolderThumb, './', strlen($thumbs_base_path)) !== false
    || strpos($storeFolder, '../', strlen($current_path)) !== false
    || strpos($storeFolder, './', strlen($current_path)) !== false) {
    response(trans('wrong path'.AddErrorLocation()))->send();
    exit;
}

$path = $storeFolder;
$cycle = true;
$max_cycles = 50;
$i = 0;
while ($cycle && $i < $max_cycles) {
    $i++;
    if ($path == $current_path) {
        $cycle = false;
    }
    if (file_exists($path.'config.php')) {
        require_once $path.'config.php';
        $cycle = false;
    }
    $path = fix_dirname($path).'/';
}

if (! empty($_FILES)) {
    $info = pathinfo($_FILES['file']['name']);
    $mime_type = get_file_mime_type($_FILES['file']['tmp_name']);
    $extension = get_extension_from_mime($mime_type);
    if ($extension === '' || $extension == 'so') {
        $extension = $info['extension'];
    }

    if (in_array(fix_strtolower($extension), $ext)) {
        $tempFile = $_FILES['file']['tmp_name'];
        $targetPath = $storeFolder;
        $targetPathThumb = $storeFolderThumb;
        $_FILES['file']['name'] = fix_filename($info['filename'].'.'.$extension, $transliteration, $convert_spaces, $replace_with);
        // LowerCase
        if ($lower_case) {
            $_FILES['file']['name'] = fix_strtolower($_FILES['file']['name']);
        }
        // Gen. new file name if exists
        if (file_exists($targetPath.$_FILES['file']['name'])) {
            $i = 1;
            $info = pathinfo($_FILES['file']['name']);

            // append number
            while (file_exists($targetPath.$info['filename'].'_'.$i.'.'.$extension)) {
                $i++;
            }
            $_FILES['file']['name'] = $info['filename'].'_'.$i.'.'.$extension;
        }

        $targetFile = $targetPath.$_FILES['file']['name'];
        $targetFileThumb = $targetPathThumb.$_FILES['file']['name'];

        // check if image (and supported)
        if (in_array(fix_strtolower($extension), $ext_img)) {
            $is_img = true;
        } else {
            $is_img = false;
        }

        if (! checkresultingsize($_FILES['file']['size'])) {
            response(sprintf(trans('max_size_reached'), $MaxSizeTotal).AddErrorLocation(), 406)->send();
            exit;
        }

        // upload
        move_uploaded_file($tempFile, $targetFile);
        chmod($targetFile, 0755);

        if ($is_img) {
            $memory_error = false;
            if (! create_img($targetFile, $targetFileThumb, 122, 91)) {
                $memory_error = false;
            } else {
                // TODO something with this long function baaaah...
                if (! new_thumbnails_creation($targetPath, $targetFile, $_FILES['file']['name'], $current_path, $relative_image_creation, $relative_path_from_current_pos, $relative_image_creation_name_to_prepend, $relative_image_creation_name_to_append, $relative_image_creation_width, $relative_image_creation_height, $relative_image_creation_option, $fixed_image_creation, $fixed_path_from_filemanager, $fixed_image_creation_name_to_prepend, $fixed_image_creation_to_append, $fixed_image_creation_width, $fixed_image_creation_height, $fixed_image_creation_option)) {
                    $memory_error = false;
                } else {
                    $imginfo = getimagesize($targetFile);
                    $srcWidth = $imginfo[0];
                    $srcHeight = $imginfo[1];

                    // resize images if set
                    if ($image_resizing) {
                        if ($image_resizing_width == 0) { // if width not set
                            if ($image_resizing_height == 0) {
                                $image_resizing_width = $srcWidth;
                                $image_resizing_height = $srcHeight;
                            } else {
                                $image_resizing_width = $image_resizing_height * $srcWidth / $srcHeight;
                            }
                        } elseif ($image_resizing_height == 0) { // if height not set
                            $image_resizing_height = $image_resizing_width * $srcHeight / $srcWidth;
                        }

                        // new dims and create
                        $srcWidth = $image_resizing_width;
                        $srcHeight = $image_resizing_height;
                        create_img($targetFile, $targetFile, $image_resizing_width, $image_resizing_height, $image_resizing_mode);
                    }

                    //max resizing limit control
                    $resize = false;
                    if ($image_max_width != 0 && $srcWidth > $image_max_width && $image_resizing_override === false) {
                        $resize = true;
                        $srcWidth = $image_max_width;

                        if ($image_max_height == 0) {
                            $srcHeight = $image_max_width * $srcHeight / $srcWidth;
                        }
                    }

                    if ($image_max_height != 0 && $srcHeight > $image_max_height && $image_resizing_override === false) {
                        $resize = true;
                        $srcHeight = $image_max_height;

                        if ($image_max_width == 0) {
                            $srcWidth = $image_max_height * $srcWidth / $srcHeight;
                        }
                    }

                    if ($resize) {
                        create_img($targetFile, $targetFile, $srcWidth, $srcHeight, $image_max_mode);
                    }
                }
            }

            // not enough memory
            if ($memory_error) {
                unlink($targetFile);
                response(trans('Not enought Memory').AddErrorLocation(), 406)->send();
                exit();
            }
        }
        echo $_FILES['file']['name'];
    } else { // file ext. is not in the allowed list
        response(trans('Error_extension').AddErrorLocation(), 406)->send();
        exit();
    }
} else { // no files to upload
    response(trans('no file').AddErrorLocation(), 405)->send();
    exit();
}

// redirect
if (isset($_POST['submit'])) {
    $query = http_build_query([
        'type' => $_POST['type'],
        'lang' => $_POST['lang'],
        'popup' => $_POST['popup'],
        'field_id' => $_POST['field_id'],
        'fldr' => $_POST['fldr'],
    ]);

    header('location: dialog.php?'.$query);
}

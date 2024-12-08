<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('uplicrm_templates_url')) {
    function upli_templates_url($path = '')
    {
        return base_url('uploads/uplicrm/' . $path);
    }
}

if (!function_exists('uplicrm_handle_upload')) {
    function upli_handle_upload($field, $directory)
    {
        if (isset($_FILES[$field]) && !empty($_FILES[$field]['name'])) {
            $path = $directory;

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $config['upload_path'] = $path;
            $config['allowed_types'] = '*'; // Allow all file types
            $config['overwrite'] = false;

            $CI = &get_instance();
            $CI->load->library('upload', $config);

            if ($CI->upload->do_upload($field)) {
                $data = $CI->upload->data();
                return ['success' => true, 'file_name' => $data['file_name'], 'file_path' => $data['full_path']];
            } else {
                return ['success' => false, 'error' => $CI->upload->display_errors()];
            }
        }

        return ['success' => false, 'error' => _l('no_file_uploaded')];
    }
}

if (!function_exists('uplicrm_unzip_file')) {
    function upli_unzip_file($zip_file, $extract_path)
    {
        $zip = new ZipArchive;
        if ($zip->open($zip_file) === true) {
            $zip->extractTo($extract_path);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('uplicrm_import_database')) {
    function upli_import_database($db_config, $sql_file)
    {
        $CI = &get_instance();

        $db_connect = mysqli_connect($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

        if (!$db_connect) {
            return false;
        }

        $sql = file_get_contents($sql_file);

        if (mysqli_multi_query($db_connect, $sql)) {
            do {
                if ($result = mysqli_store_result($db_connect)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_more_results($db_connect) && mysqli_next_result($db_connect));
            mysqli_close($db_connect);
            return true;
        } else {
            mysqli_close($db_connect);
            return false;
        }
    }
}
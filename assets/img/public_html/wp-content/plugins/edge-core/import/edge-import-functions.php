<?php

function edge_core_import_object(){
	$edge_core_import_object = new EdgefCoreImport();
}

add_action('init', 'edge_core_import_object');

if(!function_exists('edge_core_data_import')){
    function edge_core_data_import(){
		$importObject = EdgefCoreImport::getInstance();

        if ($_POST['import_attachments'] == 1)
			$importObject->attachments = true;
        else
            $importObject->attachments = false;

        $folder = "adorn/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

		$importObject->import_content($folder.$_POST['xml']);

        die();
    }

    add_action('wp_ajax_edge_core_data_import', 'edge_core_data_import');
}

if(!function_exists('edge_core_widgets_import')){
    function edge_core_widgets_import(){
		$importObject = EdgefCoreImport::getInstance();

        $folder = "adorn/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

		$importObject->import_widgets($folder.'widgets.txt',$folder.'custom_sidebars.txt');

        die();
    }

    add_action('wp_ajax_edge_core_widgets_import', 'edge_core_widgets_import');
}

if(!function_exists('edge_core_options_import')){
    function edge_core_options_import(){
		$importObject = EdgefCoreImport::getInstance();

        $folder = "adorn/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

		$importObject->import_options($folder.'options.txt');

		die();
    }

    add_action('wp_ajax_edge_core_options_import', 'edge_core_options_import');
}

if(!function_exists('edge_core_other_import')){
    function edge_core_other_import(){
		$importObject = EdgefCoreImport::getInstance();

        $folder = "adorn/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

		$importObject->import_options($folder.'options.txt');
		$importObject->import_widgets($folder.'widgets.txt',$folder.'custom_sidebars.txt');
		$importObject->import_menus($folder.'menus.txt');
		$importObject->import_settings_pages($folder.'settingpages.txt');

		if(edge_core_is_revolution_slider_installed()){
			$importObject->rev_slider_import($folder);
		}

        die();
    }

    add_action('wp_ajax_edge_core_other_import', 'edge_core_other_import');
}

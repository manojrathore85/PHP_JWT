<?php 
// Step 1: Define your autoloader function
function my_autoloader($class_name) {
    // Convert namespace separators (\) to directory separators (/)
    $file_path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    // Build the full path to the class file
    $file_path =   $file_path . '.php';
    
    // Check if the file exists
    if (file_exists($file_path)) {        
        // Load the class file
        require_once $file_path;
    }
}
// Step 2: Register your autoloader function
spl_autoload_register('my_autoloader');
?>
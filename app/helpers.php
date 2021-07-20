<?php

if (!function_exists('includeRouteFilesInFolder')) {

    /**
     * Include route files in folder (non recursive)
     * @param $folder
     */
    function includeRouteFilesInFolder($folder)
    {
        foreach (glob($folder . "/*.php") as $file) {
            require $file;
        }
    }
}

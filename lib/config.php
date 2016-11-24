<?php

namespace makeup\lib;


class Config
{
    private static $config = array();


    public static function init($moduleFileName = "App")
    {
        if (empty(self::$config)) {
            $appConfig = parse_ini_file("makeup/app/config/app.ini", true);
            $appConfig['additional_css_files']['css'] = self::setAppCssFilesPath($appConfig);
            $appConfig['additional_js_files_head']['js'] = self::setAppJsFilesHeadPath($appConfig);
            $appConfig['additional_js_files_body']['js'] = self::setAppJsFilesBodyPath($appConfig);
        } else {
            $appConfig = self::$config;
        }


        if (file_exists("makeup/modules/$moduleFileName/config/$moduleFileName.ini")) {
            $modConfig = parse_ini_file("makeup/modules/$moduleFileName/config/$moduleFileName.ini", true);
            $modConfig['additional_css_files']['css'] = self::setModCssFilesPath($modConfig, $moduleFileName);
            $modConfig['additional_js_files_head']['js'] = self::setModJsFilesHeadPath($modConfig, $moduleFileName);
            $modConfig['additional_js_files_body']['js'] = self::setModJsFilesBodyPath($modConfig, $moduleFileName);
            $appConfig = Tools::arrayReplace($appConfig, $modConfig);
        }

        self::$config = $appConfig;
    }


    /**
     * @return array
     */
    public static function getConfig()
    {
        return self::$config;
    }


    /**
     * @param $entry
     * @return mixedy
     */
    public static function get($entry="")
    {
        if ($entry) {
            $entries = explode("|", $entry);
            $entryItem = "";

            if (count($entries) == 1)
                $entryItem = isset(self::$config[$entries[0]]) ? self::$config[$entries[0]] : null;

            if (count($entries) == 2)
                $entryItem = isset(self::$config[$entries[0]][$entries[1]]) ? self::$config[$entries[0]][$entries[1]] : null;

            if (count($entries) == 3)
                $entryItem = isset(self::$config[$entries[0]][$entries[1]][$entries[2]]) ? self::$config[$entries[0]][$entries[1]][$entries[2]] : null;

            #return is_array($entryItem) ? array_pop($entryItem) : $entryItem;
            return $entryItem;
        } else {
            return self::$config;
        }
    }


    /**
     * @return array
     */
    public static function getAdditionalCssFiles()
    {
        return self::removeDuplicateFiles(self::$config['additional_css_files']);
    }


    /**
     * @return array
     */
    public static function getAdditionalJsFilesHead()
    {
        return self::removeDuplicateFiles(self::$config['additional_js_files_head']);
    }


    /**
     * @return array
     */
    public static function getAdditionalJsFilesBody()
    {
        return self::removeDuplicateFiles(self::$config['additional_js_files_body']);
    }


    /**
     * @param array $files
     */
    public static function setAdditionalCssFiles($files = array())
    {
        if (isset($files['css'])) {
            self::$config['additional_css_files'] = array_merge_recursive(self::$config['additional_css_files'], $files);
        }
    }


    /**
     * @param array $files
     */
    public static function setAdditionalJsFilesHead($files = array())
    {
        if (isset($files['js'])) {
            self::$config['additional_js_files_head'] = array_merge_recursive(self::$config['additional_js_files_head'], $files);
        }
    }


    /**
     * @param array $files
     */
    public static function setAdditionalJsFilesBody($files = array())
    {
        if (isset($files['js'])) {
            self::$config['additional_js_files_body'] = array_merge_recursive(self::$config['additional_js_files_body'], $files);
        }
    }


    /**
     * @param $config
     * @return array
     */
    private static function setAppCssFilesPath($config)
    {
        if (isset($config['additional_css_files']['css'][0]) && $config['additional_css_files']['css'][0]) {
            $newPath = [];
            foreach ($config['additional_css_files']['css'] as $file) {
                $newPath[] = "makeup/app/res/css/$file";
            }
            return $newPath;
        }
    }


    /**
     * @param $config
     * @return array
     */
    private static function setAppJsFilesHeadPath($config)
    {
        if (isset($config['additional_js_files_head']['js'][0]) && $config['additional_js_files_head']['js'][0]) {
            $newPath = [];
            foreach ($config['additional_js_files_head']['js'] as $file) {
                $newPath[] = "makeup/app/res/js/$file";
            }
            return $newPath;
        }
    }


    /**
     * @param $config
     * @return array
     */
    private static function setAppJsFilesBodyPath($config)
    {
        if (isset($config['additional_js_files_body']['js'][0]) && $config['additional_js_files_body']['js'][0]) {
            $newPath = [];
            foreach ($config['additional_js_files_body']['js'] as $file) {
                $newPath[] = "makeup/app/res/js/$file";
            }
            return $newPath;
        }
    }


    /**
     * @param $config
     * @param $mod
     * @return array
     */
    private static function setModCssFilesPath($config, $mod)
    {
        if (isset($config['additional_css_files']['css'][0]) && $config['additional_css_files']['css'][0]) {
            $newPath = [];
            foreach ($config['additional_css_files']['css'] as $file) {
                $newPath[] = "makeup/modules/$mod/res/css/$file";
            }
            return $newPath;
        }
    }


    /**
     * @param $config
     * @param $mod
     * @return array
     */
    private static function setModJsFilesHeadPath($config, $mod)
    {
        if (isset($config['additional_js_files_head']['js'][0]) && $config['additional_js_files_head']['css'][0]) {
            $newPath = [];
            foreach ($config['additional_js_files_head']['js'] as $file) {
                $newPath[] = "makeup/modules/$mod/res/js/$file";
            }
            return $newPath;
        }
    }


    /**
     * @param $config
     * @param $mod
     * @return array
     */
    private static function setModJsFilesBodyPath($config, $mod)
    {
        if (isset($config['additional_js_files_body']['js'][0]) && $config['additional_js_files_body']['css'][0]) {
            $newPath = [];
            foreach ($config['additional_js_files_body']['js'] as $file) {
                $newPath[] = "makeup/modules/$mod/res/js/$file";
            }
            return $newPath;
        }
    }


    /**
     * @param $array
     * @return array
     */
    private static function removeDuplicateFiles($array)
    {
        $fixedArr = array();
        if(isset($array['css']))
            $fixedArr['css'] = array_unique($array['css']);
        if(isset($array['js']))
            $fixedArr['js'] = array_unique($array['js']);
        return $fixedArr;
    }

}

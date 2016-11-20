<?php

namespace MakeUp\lib;


class Config
{
    private static $config = array();


    public static function init($moduleClassName = "app")
    {
        $appConfig = parse_ini_file("MakeUp/app/config/app.ini", true);
        $appConfig['additional_css_files']['css'] = self::setAppCssFilesPath($appConfig);
        $appConfig['additional_js_files_head']['js'] = self::setAppJsFilesHeadPath($appConfig);
        $appConfig['additional_js_files_body']['js'] = self::setAppJsFilesBodyPath($appConfig);

        if (file_exists("MakeUp/modules/$moduleClassName/config/$moduleClassName.ini")) {
            $modConfig = parse_ini_file("MakeUp/modules/$moduleClassName/config/$moduleClassName.ini", true);
            $modConfig['additional_css_files']['css'] = self::setModCssFilesPath($modConfig, $moduleClassName);
            $modConfig['additional_js_files_head']['js'] = self::setModJsFilesHeadPath($modConfig, $moduleClassName);
            $modConfig['additional_js_files_body']['js'] = self::setModJsFilesBodyPath($modConfig, $moduleClassName);
            $appConfig = array_merge_recursive($appConfig, $modConfig);
        }

        self::$config = $appConfig;
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
                $entryItem = self::$config[$entries[0]];

            if (count($entries) == 2)
                $entryItem = self::$config[$entries[0]][$entries[1]];

            if (count($entries) == 3)
                $entryItem = self::$config[$entries[0]][$entries[1]][$entries[2]];

            return is_array($entryItem) ? array_pop($entryItem) : $entryItem;
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
                $newPath[] = "MakeUp/app/res/css/$file";
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
                $newPath[] = "MakeUp/app/res/js/$file";
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
                $newPath[] = "MakeUp/app/res/js/$file";
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
                $newPath[] = "MakeUp/modules/$mod/res/css/$file";
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
                $newPath[] = "MakeUp/modules/$mod/res/js/$file";
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
                $newPath[] = "MakeUp/modules/$mod/res/js/$file";
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

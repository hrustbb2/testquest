<?php

namespace app\lib;

use app\App;

class WebController
{
    /**
     * @param $content string
     * @return string
     */
    private function loadLayer($content)
    {
        $layer = App::getInstance()->getConf()['layer'];
        ob_start();
        require_once __DIR__ . '/../views/' . $layer;
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    /**
     * @param $path string
     * @param $arg Array
     * @return string
     */
    public function load_view($path, $arg = array())
    {
        //переменные доступные в отображении
        foreach ($arg as $key => $value) {
            $$key = $value;
        }

        ob_start();
        require_once __DIR__ . '/../views/' . $path;
        $result = ob_get_contents();
        ob_end_clean();
        return $this->loadLayer($result);
    }
}

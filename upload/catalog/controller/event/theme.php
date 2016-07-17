<?php

class ControllerEventTheme extends Controller
{
    public function index(&$view, &$data, &$output)
    {

        if (!$this->config->get($this->config->get('config_theme') . '_status')) {
            exit('Error: A theme has not been assigned to this store!');
        }

        // If the default theme is selected we need to know which directory its pointing to
        if ($this->config->get('config_theme') == 'theme_default') {
            $theme = $this->config->get('theme_default_directory');
        } else {
            $theme = $this->config->get('config_theme');
        }

        // If there is a theme override we should get it
        $this->load->model('design/theme');

        $theme_info = $this->model_design_theme->getTheme($view, $theme);

        if ($theme_info) {
            $view = $theme_info['code'];
        } else {
            $view = $theme . '/template/' . $view;
        }
    }
}
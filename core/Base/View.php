<?php

namespace Core\Base;

use App\Model\Account;
use Core\Utils\Cookie;

abstract class View {
    protected string $page_name;
    protected string $page_title = "";
    protected string $template_name = "main";
    protected array $page_options = [];
    protected array $page_styles = [];
    protected array $page_scripts = [];

    /**
     * Выполняем первоначальную настройку вида.
     * Получаем имя страницы и имя шаблона страницы.
     * @param string $name
     * @param string $template
     */
    public function setup(string $name, string $template = "main"){
        $this->page_name = $name;
        $this->template_name = $template;
        // Добавление информации об авторизации пользователя
        $this->addOption("AUTH", (bool)Cookie::get("auth_session"));
        $this->addOption("SYS_LEVELACCESS", (Cookie::get("auth_level_access") != null) ? intval(Cookie::get("auth_level_access")) : 1);
    }

    /**
     * Метод инициализации параметров страницы.
     * @param array $page_options
     */
    public function setPageOptions(array $page_options): void {
        $this->page_options = $page_options;
    }

    /**
     * Метод добавления новых параметров на страницу.
     * @param $name
     * @param $value
     */
    public function addOption($name, $value) : void{
        $this->page_options[$name] = $value;
    }

    /**
     * Метод инициализации стилей страницы.
     * @param array $page_styles
     */
    public function setPageStyles(array $page_styles): void {
        $this->page_styles = $page_styles;
    }

    /**
     * Метод добавления новых стилей на страницу.
     * @param $name
     */
    public function addPageStyle($name) : void{
        $this->page_styles[] = $name;
    }

    /**
     * Метод сборки стилей в единую форму для интеграции.
     * @return string
     */
    public function completePageStyles() : string{
        $result = "";
        foreach ($this->page_styles as $style_name){
            $result .= "<link rel='stylesheet' href='/app/interface/res/css/{$style_name}.css'> \n";
        }
        return $result;
    }

    /**
     * Метод инициализации скриптов страницы.
     * @param array $page_scripts
     */
    public function setPageScripts(array $page_scripts): void {
        $this->page_scripts = $page_scripts;
    }

    /**
     * Метод добавления новых стилей на страницу.
     * @param $name
     */
    public function addPageScript($name) : void{
        $this->page_scripts[] = $name;
    }

    /**
     * Метод сборки скриптов в единую форму для интеграции.
     * @return string
     */
    public function completePageScripts() : string{
        $result = "";
        foreach ($this->page_scripts as $script_name){
            $result .= "<script src='{$script_name}.js'></script>\n";
        }
        return $result;
    }

    /**
     * Метод задания заголовка страницы.
     * @param string $page_title
     */
    public function setPageTitle(string $page_title): void {
        $this->page_title = $page_title;
    }

    public function render(){
        // Собираем полный путь до шаблона страницы
        $path_template = __ROOTDIR__ . "/app/interface/template/" . $this->template_name . ".php";
        // Собираем полный путь до самой страницы
        $path_page = __ROOTDIR__ . "/app/interface/page/" . $this->page_name . ".php";
        // Собираем код для полключения стилей и скриптов
        $page_styles = $this->completePageStyles();
        $page_scripts = $this->completePageScripts();
        // Добавляем заголовок к параметрам страницы
        $this->addOption("SYS_TITLE", $this->page_title);
        // Превращаем массив параметров страницы в отдельные переменные с названием по ключю
        extract($this->page_options);
        // Подключаем шаблон
        require_once $path_template;
    }
}
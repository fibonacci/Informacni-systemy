<?php
/**
 * 
 */
class website{
    private $template;
    protected $variables;
    protected $clearVariables;

    const MENU = 'menu';

    public function  __construct() {
        $this->template = get_class($this);
        $this->variables = array();
        $this->clearVariables = array();
        //$this->Init();
    }

    public function SetVariable($name, $value){
        $this->variables[$name] = $value;
        return $this;
    }

    public function GetContent(){
        $content = $this->LoadTemplate($this->template);

        foreach ($this->variables as $variable => $value) {
            $content = str_replace('{' . $variable . '}', $value, $content);
        }

        return $content;
    }

    private function LoadTemplate($template){
        return file_get_contents('templates/' . $template . '.tpl');
    }

    protected function Init(){
        $this->SetVariable(self::MENU, $this->LoadTemplate(self::MENU));
        $this->clearVariables();
    }

    protected function SavePostedData($variables, $submit){
        if(isset ($_POST[$submit])){
            foreach ($variables as $var) {
                $this->SetVariable($var, ex::espacePOST($var));
            }
        } else {
            foreach ($variables as $var) {
                $this->SetVariable($var, '');
            }
        }
    }

    protected function CheckData($variables){
        $checked = true;

        foreach ($variables as $var) {
            if(!(isset($_POST[$var]) && $_POST[$var])){
                $checked = false;
            }
        }

        return $checked;
    }

    protected function clearVariable($name){
        $this->SetVariable($name, '');
    }

    protected function clearVariables(){
        foreach ($this->clearVariables as $name) {
            $this->clearVariable($name);
        }
    }
}
?>

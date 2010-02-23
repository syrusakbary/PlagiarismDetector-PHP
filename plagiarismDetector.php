<?
define(PLAGIARISM_DIR,dirname(__FILE__).'/');

include_once(PLAGIARISM_DIR.'plagiarismDetectorPlugin.php');
class plagiarismDetector
{
    private $plugins;
    private $pluginsDir = 'plugins/';
    public function __construct () {
        $open=opendir (PLAGIARISM_DIR.$this->pluginsDir);
        while ($files=readdir($open)){
            if (ereg('.php$',$files)) {
                $name = basename($files, ".php");
                $this->plugins[] = $name;
            }
        }
    }
    public function aviablePlugins() {
        return $this->plugins;
    }
    public function plugin ($plugin) {
        if (in_array($plugin,$this->plugins)) {
            include_once(PLAGIARISM_DIR.$this->pluginsDir.$plugin.'.php');
            $class = $this->getClassName($plugin);
            return new $class;
        }
    }
    private function getClassName ($plugin) {
        return 'plagiarismDetectorPlugin'.ucfirst(strtolower($plugin));
    }
    public function info ($plugin) {
        if (in_array($plugin,$this->plugins)) {
            include_once(PLAGIARISM_DIR.$this->pluginsDir.$plugin.'.php');
            $class = $this->getClassName($plugin);
            return call_user_func($class.'::info');
        }
    }
}
$pla = new plagiarismDetector;
//var_dump($pla->plugin('ac'));

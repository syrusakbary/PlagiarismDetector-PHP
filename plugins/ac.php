<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

class plagiarismDetectorPluginAc extends plagiarismDetectorPlugin
{
    protected $options = array (
    	'jar' => '/home/syrusakbary/Documentos/Proyectos/ac.jar'
    );
    public function __construct ($options=NULL) {
    	if ($options) $this->options = array_merge($this->options,$options);
    }
    public function setJar ($source) {
    	$this->jar = $source;
    }
    public function compareDir ($dir) {
    	if (is_dir($dir)) {
        $io = array();
        $command = "java -cp {$this->options['jar']} eps.ac.AC {$dir}";
            $p = proc_open($command,
                           array(1 => array('pipe', 'w'),
                                 2 => array('pipe', 'w')),
                           $io);

            /* Read output sent to stdout. */
            $output = "";
            while (!feof($io[1])) {
                $output .= htmlspecialchars(fgets($io[1]),
                                                        ENT_COMPAT, 'UTF-8');
            }
            /* Read output sent to stderr. */
            while (!feof($io[2])) {
                $output .= htmlspecialchars(fgets($io[2]),
                                                        ENT_COMPAT, 'UTF-8');
            }

            fclose($io[1]);
            fclose($io[2]);
            proc_close($p);
            if ($file = @$this->readOutputFile($output)) {
           		$this->readFile($file);
            }
    	}
        return $this;
		
    }
    public function readOutputFile ($output) {
        preg_match('/Resultados guardados en (.*)/',$output,$matches);
        //var_dump($output);
        $file = $matches[1];
        return $file;
    }

    public function readFile ($file) {
        $contents = file_get_contents($file);
        preg_match_all('/^([0-9\.]+) (.+) (.+)/m',$contents,$match, PREG_SET_ORDER);
        $this->results = array();
        foreach ($match as $line) {
            $this->addDistance( $line[2], $line[3], $line[1]);
        }
        //var_dump($out);
        //$this->results = $out;
        return $this;
    }
    public static function info () {
        return array(
            'name' => 'AntiCopier Java'
        );
    }
}
/*$m = new mAntiPlagiarismAC();
var_dump($m->compareDir('/home/syrus/plagism/AC/test/sample_aa')->getSimilarity(0.7));*/
//var_dump($m->readFile('asdf')->->getResults());
//var_dump($m->compareDir('/home/syrus/plagism/AC/test/sample_aa')->getDistance(1));
//var_dump($m->readFile('/tmp/anticopia4576802438527145659.txt')->getSimilarity(0.7));

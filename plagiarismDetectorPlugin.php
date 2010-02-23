<?
abstract class plagiarismDetectorPlugin
{
    protected $results=array();
    protected $options=array();
    public function addSimilarity($user1,$user2,$similarity) {
        //$this->results[$user1][$user2] = (double) $similarity;
        $this->results[] = array(
            'users' => array($user1,$user2),
            'similarity' => $similarity,
        );
    }
    public function addDistance($user1,$user2,$distance) {
        $this->results[] = array(
            'users' => array($user1,$user2),
            'similarity' => $this->distanceToSimilarity($distance),
        );
    }
    private function distanceToSimilarity ($distance) {
    	return 1-$distance;
    }
    public function getSimilarity ($similarity) {
        $sim = array();
        for ($i=0;$i<count($this->results);$i++) {
            if ($this->results[$i]['similarity'] >= $similarity) {
                $sim[] = $this->results[$i];
            }
        }
        return $sim;
    }
    public function getDistance ($distance) {
    	return $this->getSimilarity($this->distanceToSimilarity($distance));
    }
    public function getResults() {
        return $this->results;
    }
}

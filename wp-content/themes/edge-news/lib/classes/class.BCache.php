<?php 

/**
 * 
 * Core Class with for read/write cache - Singleton
 * @author Bojan Naumoski
 *
 */
class BCache{

	protected $path = '/dev/shm/';
	protected $prefix = 'BCache_';
		
	//singleton
	public static function Instance(){
		static $loader = null;
		if ($loader == NULL)
            $loader = new self();

        return $loader;
	}
	
    //prevent cloning of class
	private function __clone() {}
	
	private function __construct(){
	}
	
	public function setPrefix($prefix="BCache"){
		$this->prefix = $prefix;
	}
	
	public function setPath($path="/dev/shm/"){
		$this->path = $path;
	}
	
	protected function write($gid, $id, $time, $data){

		$filename = $this->getFileName($gid,$id);		
		if ($fp = @fopen($filename, 'xb')){
			if(flock($fp, LOCK_EX)){
			  fwrite($fp, $data);
			}
			fclose($fp);
           touch($filename, time() + $time);
		}
	}
	
	public function read($gid,$id){
		
		$filename = $this->getFileName($gid, $id);
		return file_get_contents($filename);		
	
	}
	
	protected function getFileName($gid,$id){
		
		$id = md5($id);
		
		$filename = $this->path.$this->prefix.$gid."_".$id;
		return $filename;
	}
	
	protected function isCached($gid, $id){
		
		$filename = $this->getFileName($gid, $id);
		
		if(file_exists($filename) && filemtime($filename) > time()){
			return true;
		}
		
		@unlink($filename);
		return false;
	}
	
}
/**
 * 
 * Extends BCache, provides caching arrays, objects, and all kind of data..
 * @author Bojan Naumoski
 *
 */
class BCacheData extends BCache{
	
	public static function Instance(){
		static $loader = null;
		if ($loader == NULL)
            $loader = new self();

        return $loader;
	}
	
    //prevent cloning of class
	private function __clone() {}
	
	private function __construct(){
	}

	/**
	 * 
	 * Gets the data from cache file..
	 * @param mixed $gid
	 * @param mixed $id
	 */
	public function Get($gid,$id){
		
		if($this->isCached($gid, $id)){
			return unserialize($this->read($gid, $id));
		}
		return null;
	}
	
	/**
	 * 
	 * Sets the data from cache file..
	 * @param mixed $gid
	 * @param mixed $id
	 * @param int $time
	 * @param mixed $data array, object, plain text etc...
	 */
	public function Set($gid,$id,$time,$data){	
		
		$this->write($gid, $id, $time, serialize($data));
		
	}	
	
}

/**
 * 
 * Provides cache for whole pages generated, so it makes them static
 * @author Bojan Naumoski
 *
 */
class BCacheOutput extends BCache{

	private $gid,$id,$time;
	
	/**
	 * 
	 * Instantiate Singleton from this class.
	 */
	public static function Instance(){
		static $loader = null;
		if ($loader == NULL)
            $loader = new self();

        return $loader;
	}
	
    //prevent cloning of class
	private function __clone() {}
	
	private function __construct(){
	}
	
	/**
	 * 
	 * Starting Buffer for cache... Use it with 
	 * if(!$class->StartOut('ex','ex',120){
	 * 
	 * 		CONTENT HERE WILL BE CACHED.
	 * 
	 * 
	 *  $class->EndOut();
	 * }
	 * 
	 * @param mixed $gid Group ID
	 * @param mixed $id ID
	 * @param int $time Seconds of time to keep cache
	 */
	public function StartOut($gid,$id,$time){
		
		if($this->isCached($gid, $id)){
		    echo $this->read($gid, $id);
		return true;
		}
		else{
			ob_start();
			$this->gid = $gid;
			$this->id = $id;
			$this->time = $time;
		return false;
		}
	}
	
	
	/**
	 * 
	 * End caching the buffer.
	 */
	public function EndOut(){
		
	    $data = ob_get_contents();
        ob_end_flush();
		$this->write($this->gid, $this->id, $this->time, $data);
	}
	
	
}

?>
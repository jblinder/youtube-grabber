<?php

/******************************************/
/******************************************/
/****  Class: YoutubeGrabber           ****/
/****  by Justin Blinder               ****/
/****  justin.blinder@gmail.com        ****/
/******************************************/
/******************************************/

class youtubeGrabber
{
    private $videoURL;
    private $fileName;
    private $filePath;
    private $info;
    private $types;
    private $format;
    private $youtubeBase;
    private $youtubeBaseCall;
	private $videoExtension;
    
    public function __construct()
    {
        $this->fileName = "video"; //defualt name
        $this->types = array('mp4' =>'18', 'flv_high'=>'34', 'flv_low'=>'5'); // file formats
        $this->youtubeBase = 'http://www.youtube.com/watch?v='; //base youtube url
        $this->youtubeBaseCall = 'http://www.youtube.com/get_video_info?video_id='; //base callback url
    }
    
    public function format($type)
    {
        switch($type)
        {
            case "mp4":
                $this->format = $this->types['mp4'];
				$this->videoExtension = '.mp4';
                break;
            case "flv_high":
                $this->format = $this->types['flv_high'];
				$this->videoExtension = '.flv';
                break;
            case "flv_low":
                $this->format = $this->types['flv_low'];
				$this->videoExtension = '.flv';
                break;
            defualt:    
                $this->format = $this->types['fvl_high'];
				$this->videoExtension = '.flv';
        }
    }
    
    public function filepath($path)
    {

        $this->filePath = $path;
    }
    
    public function filename($name)
    {
        $this->fileName = $name;
    }
    
    public function download($url)
    {
	 	preg_match('/v=(.*)/',$url,$videoID);
        $this->videoURL = $this->youtubeBaseCall . $videoID[1];
		$this->curl();
    }
    
    private function curl()
    {
		//donwload and parse payload file
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->videoURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$content = curl_exec($ch);
		curl_close($ch);
		
		$youtubelink = $this->parseURL($content);
		
		//downlaod video
		echo "donwloading...";
 		$fp = fopen ($this->filePath. $this->fileName. $this->videoExtension, 'w+');
		$ch = curl_init($youtubelink);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);	
    }

	private function parseURL($content)
	{
		preg_match('/fmt_url_map=(.*)[&]/',$content,$tempurls);
		$urlpayload = urldecode($tempurls[1]);
		$youtubeURL = '';
		$urls = preg_split('/,/', $urlpayload);
		for ($i = 0; $i < count($urls);$i++)
		{
			preg_match('/^[0-9]+\|(.*)/', $urls[$i], $url);
			$type = preg_split('/\|/',$url[0]);
			echo "t: " . $type[0] . "\n";
			echo "f: " . $this->format . "\n";
			if ($type[0] == $this->format) 
			{
				$youtubeURL = $url[1];
				break;
			}
		}
		return $youtubeURL;
	}
	
}
?>


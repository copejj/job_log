<?php
namespace Jeff\Code\Util\Info;

class ServerInfo
{
	private $free_space;
	private $total_space;
	private $disk_percent;
	private $server_load;
	private $health_status;

	public function __construct()
	{
		$free_space = disk_free_space("/");
		$this->free_space = $this->formatHumanable($free_space);
		$total_space = disk_total_space("/");
		$this->total_space = $this->formatHumanable($total_space);
		$this->disk_percent = ($total_space > 0) ? round((($total_space - $free_space) / $total_space) * 100, 2) : 0;
		$load_avg = sys_getloadavg();
		$this->server_load = round($load_avg[0], 3);
		$this->health_status = ($load_avg[0] < 2.0) ? "Healthy" : "High Load"; // Threshold depends on your CPU cores
	}

	public function __get($name)
	{
		return $this->$name;
	}

	function formatHumanable($bytes) 
	{
		if ($bytes === false) return "Error";
		
		$si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
		$base = 1024;
		$class = min((int)log($bytes , $base) , count($si_prefix) - 1);
		
		return sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
	}
}
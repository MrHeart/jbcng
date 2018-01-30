
<?php
	session_start();
    
	//
	//Delete reports over one week old
	/*if ($handle = opendir(base_url().'reports')) {*/
	
    /* This is the correct way to loop over the directory. */
   /* while (false !== ($entry = readdir($handle))) {
         if (($entry!='..') && ($entry !='.'))
		 {
			 $ext = pathinfo($entry, PATHINFO_EXTENSION);
			 $file=base_url().'reports/'.$entry;
			 
			 //604800 seconds - 7days
			 //86400 Seconds - 1 day
			 if (trim(strtolower($ext))==='pdf')
			 {			
				if (strtotime("-168 hours") >= filemtime($file)) 
				{
					unlink($file);
				}
				 //echo $entry.' => mTime: '.filemtime($file).' => WEEK => '.strtotime("-168 hours")."<br>";
			 }
		 }
    }

    closedir($handle);
}*/
?>

<div align="center" style="background-color:#B5D631;" ></div>
    
<?php //echo $this->load->view("footer"); ?>


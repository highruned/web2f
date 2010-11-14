<?php

	if(count($_FILES) > 0)
	{
		if(is_uploaded_file($_FILES['Filedata']['tmp_name']))
		{
			$filename = $_FILES['Filedata']['name'];

			if(file_exists("../../uploads/" . $filename))
			{

			}
			else
			{
				move_uploaded_file($_FILES['Filedata']['tmp_name'], "../../uploads/" . $filename);
			}
		}
		// if there's errors with the upload
		else if($_FILES['Filedata']['error'] !== 0)
		{
?>

<div class="message-red">Error Code: <?=$_FILES['file']['error']?>
<br /></div><br />

<?php
		}
	}
?>
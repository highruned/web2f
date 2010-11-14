<?php

	class Search extends Module
	{
		public function Setup()
		{
			echo "Setup in development.";
		}
		
		public function Configure()
		{
			echo "Module in development.";
		}
	}

	$this->AddModule(new Search(), "search");

	if($q = $this->Request['q'])
	{
		$q = preg_replace("/\s+/", " ", trim($q));
		
				
		//seperate multiple keywords into array space delimited
		$keywords = explode(" ", $q);
		
		//Clean empty arrays so they don't get every row as result
		$keywords = array_diff($keywords, array(""));
		die(var_dump($this->Settings));
		//Set the MySQL query
		if ($q == NULL or $q == '%'){
		} else {
		for ($i=0; $i<count($keywords); $i++) {
		$query = "SELECT * FROM `cms_pages` " .
		"WHERE (`page_title` LIKE '%".$keywords[$i]."%'".
		" OR `page_meta_description` LIKE '%".$keywords[$i]."%'" .
		" OR `page_meta_keywords` LIKE '%".$keywords[$i]."%'" .
		" OR `page_content` LIKE '%".$keywords[$i]."%')" .
		" AND (`page_access_level` < '{$this->Group['access_level']}')" .
		"ORDER BY `page_title`";
		}
		
		//Store the results in a variable or die if query fails
		$result = $this->DB->Query($query);
		}
		if ($q == NULL or $q == '%'){
		} else {
		//Count the rows retrived
		$count = mysql_num_rows($result);
		}
		
		echo "<html>";
		echo "<head>";
		echo "<title>Your Title Here</title>";
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";
		echo "</head>";
		echo "<body onLoad=\"self.focus();document.searchform.search.focus()\">";
		echo "<center>";
		echo "<br /><form name=\"searchform\" method=\"GET\" action=\"search.php\">";
		echo "<input type=\"text\" name=\"search\" size=\"20\" TABINDEX=\"1\" />";
		echo " <input type=\"submit\" value=\"Search\" />";
		echo "</form>";
		//If search variable is null do nothing, else print it.
		if ($q == NULL) {
		} else {
		echo "You searched for <b><FONT COLOR=\"blue\">";
		foreach($keywords as $value) {
		   print "$value ";
		}
		echo "</font></b>";
		}
		echo "<p> </p><br />";
		echo "</center>";
		
		//If users doesn't enter anything into search box tell them to.
		if ($q == NULL){
		echo "<center><b><FONT COLOR=\"red\">Please enter a search parameter to continue.</font></b><br /></center>";
		} elseif ($q == '%'){
		echo "<center><b><FONT COLOR=\"red\">Please enter a search parameter to continue.</font></b><br /></center>";
		//If no results are returned print it
		} elseif ($count <= 0){
		echo "<center><b><FONT COLOR=\"red\">Your query returned no results from the database.</font></b><br /></center>";
		//ELSE print the data in a table
		} else {
		//Table header
		echo "<center><table id=\"search\" bgcolor=\"#AAAAAA\">";
		echo "<tr>";
		echo "<td><b>COLUMN 1:</b></td>";
		echo "<td><b>COLUMN 2:</b></td>";
		echo "<td><b>COLUMN 3:</b></td>";
		echo "<td><b>COLUMN 4:</b></td>";
		echo "<td><b>COLUMN 5:</b></td>";
		echo "<td><b>COLUMN 6:</b></td>";
		echo "<tr>";
		echo "</table></center>";
		
		//Colors for alternation of row color on results table
		$color1 = "#d5d5d5";
		$color2 = "#e5e5e5";
		//While there are rows, print it.
		while($row = mysql_fetch_array($result))
		{
		//Row color alternates for each row
		$row_color = ($row_count % 2) ? $color1 : $color2;
		//table background color = row_color variable
		echo "<center><table bgcolor=".$row_color.">";
		echo "<tr>";
		echo "<td>".$row['page_title']."</td>";
		echo "<td>".$row['page_meta_description']."</td>";
		echo "<td>".$row['page_meta_keywords']."</td>";
		echo "<td>".$row['page_content']."</td>";
		echo "<td>".$row['page_content']."</td>";
		echo "</tr>";
		echo "</table></center>";
		$row_count++;
		//end while
		}
		//end if
		}
		
		echo "</body>";
		echo "</html>";
		if ($q == NULL or $q == '%') {
		} else {
		//clear memory
		mysql_free_result($result);
		}
die();

		
	}
	
?>
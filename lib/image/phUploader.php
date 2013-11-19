<?php
    
// Max size PER file in KB
$max_file_size="2048";

// Max size for all files COMBINED in KB
$max_combined_size="2048";

//Maximum file uploades at one time
$file_uploads="1";

// Full browser accessable URL to where files are accessed. With trailing slash.
$full_url= SITE_ROOT."/uploads";

// Path to store files on your server If this fails use $fullpath below. With trailing slash.
$folder= "uploads/";

// Use random file names? true=yes (recommended), false=use original file name.
// Random names will help prevent files being denied because a file with that name already exists.
$random_name=true;

// Types of files that are acceptiable for uploading. Keep the array structure.
$allow_types=array("jpg","gif","png");

// Only use this variable if you wish to use full server paths. Otherwise leave this empty. With trailing slash.
$fullpath="/";


/*
//================================================================================
* ! ATTENTION !
//================================================================================
: Don't edit below this line.
*/

// Initialize variables
$error="";
$success="";
$display_message="";
$file_ext=array();
$password_form="";

// Function to get the extension a file.
function get_ext($key) { 
	$key=strtolower(substr(strrchr($key, "."), 1));
	$key=str_replace("jpeg","jpg",$key);
	return $key;
}

// Filename security cleaning. Do not modify.
function cln_file_name($string) {
	$cln_filename_find=array("/\.[^\.]+$/", "/[^\d\w\s-]/", "/\s\s+/", "/[-]+/", "/[_]+/");
	$cln_filename_repl=array("", ""," ", "-", "_");
	$string=preg_replace($cln_filename_find, $cln_filename_repl, $string);
	return trim($string);
}

// Dont allow submit if $password_form has been populated
If(isset($_POST['submit'])){

	//Tally the size of all the files uploaded, check if it's over the ammount.	
	If(array_sum($_FILES['file']['size']) > $max_combined_size*1024) {
		
		$error.="<b>FAILED:</b> All Files <b>REASON:</b> Combined file size is to large.<br />";
		
	// Loop though, verify and upload files.
	} Else {

		// Loop through all the files.
		For($i=0; $i <= $file_uploads-1; $i++) {
			
			// If a file actually exists in this key
			If($_FILES['file']['name'][$i]) {

				//Get the file extension
				$file_ext[$i]=get_ext($_FILES['file']['name'][$i]);
				
				// Randomize file names
				If($random_name){
					$file_name[$i]=time()+rand(0,100000);
				} Else {
					$file_name[$i]=cln_file_name($_FILES['file']['name'][$i]);
				}
	
				// Check for blank file name
				If(str_replace(" ", "", $file_name[$i])=="") {
					
					$error.= "<b>FAILED:</b> ".$_FILES['file']['name'][$i]." <b>REASON:</b> Blank file name detected.<br />";
				
				//Check if the file type uploaded is a valid file type. 
				}	ElseIf(!in_array($file_ext[$i], $allow_types)) {
								
					$error.= "<b>FAILED:</b> ".$_FILES['file']['name'][$i]." <b>REASON:</b> Invalid file type.<br />";
								
				//Check the size of each file
				} Elseif($_FILES['file']['size'][$i] > ($max_file_size*1024)) {
					
					$error.= "<b>FAILED:</b> ".$_FILES['file']['name'][$i]." <b>REASON:</b> File to large.<br />";
					
				// Check if the file already exists on the server..
				} Elseif(file_exists($folder.$file_name[$i].".".$file_ext[$i])) {
					$error.= "<b>FAILED:</b> ".$_FILES['file']['name'][$i]." <b>REASON:</b> File already exists.<br />";
				} Else {
					
					if(move_uploaded_file($_FILES['file']['tmp_name'][$i],$folder.$file_name[$i].".".$file_ext[$i])) {
						$success.="<b>SUCCESS:</b> ".$_FILES['file']['name'][$i]."<br />";
                        $_SESSION['filename'] = $folder.$file_name[$i].".".$file_ext[$i];
                        $_POST = "";
					} else {
						$error.="<b>FAILED:</b> ".$_FILES['file']['name'][$i]." <b>REASON:</b> General upload failure.<br />";
					}
					
				}
							
			} // If Files
		
		} // For
		
	} // Else Total Size
	
	If(($error=="") AND ($success=="")) {
		$error.="<b>FAILED:</b> No files selected<br />";
	}

	$display_message=$success.$error;

} // $_POST AND !$password_form

/*
//================================================================================
* Start the form layout
//================================================================================
:- Please know what your doing before editing below. Sorry for the stop and start php.. people requested that I use only html for the form..
*/

if(empty($_SESSION['filename'])){
?>
<head>


<style type="text/css">
	body{
		background-color:#FFFFFF;
		font-family: Verdana, Arial, sans-serif;
		font-size: 12pt;
		color: #000000;
	}
	
	.message {
		font-family: Verdana, Arial, sans-serif;
		font-size: 11pt;
		color: #000000;
		background-color:#EBEBEB;
	}

	a:link, a:visited {
		text-decoration:none;
		color: #000000;
	}
	
	a:hover {
		text-decoration:none;
		color: #000000;
	}

	.table {
		border-collapse:collapse;
		border:1px solid #000000;
		width:100%;
	}
	
	.table_header {
		border:1px solid #000000;
		background-color:#C03738;
		font-family: Verdana, Arial, sans-serif;
		font-size: 11pt;
		font-weight:bold;
		color: #FFFFFF;
		text-align:center;
		padding:2px;
	}
	
	.upload_info {
		border:1px solid #000000;
		background-color:#EBEBEB;
		font-family: Verdana, Arial, sans-serif;
		font-size: 8pt;
		color: #000000;
		padding:4px;
	}

	.table_body {
		border:1px solid #000000;
		background-color:#EBEBEB;
		font-family: Verdana, Arial, sans-serif;
		font-size: 10pt;
		color: #000000;
		padding:2px;
	}

	.table_footer {
		border:1px solid #000000;
		background-color:#C03738;
		text-align:center;
		padding:2px;
	}

	input,select,textarea {
		font-family: Verdana, Arial, sans-serif;
		font-size: 10pt;
		color: #000000;
	}	
	form {
		padding:0px;
		margin:0px;
	}
</style>

<form action="" method="post" enctype="multipart/form-data" name="phuploader">
<table align="center" class="table">
	<tr>
        <td class="table_header" colspan="2"><b>Please upload your site image first and then submit your listing</b>
        </td>

	</tr>

	<?if($display_message){?>
	<tr>
		<td colspan="2" class="message">
		<br />
			<?=$display_message;?>
		<br />
		</td>
	</tr>
	<?}?>
	
	<tr>
		<td colspan="2" class="upload_info">
			<b>Allowed Types:</b> <?=implode($allow_types, ", ");?><br />
			<b>Max size per file:</b> <?=$max_file_size?>kb.<br />
		</td>
	</tr>
	<?For($i=0;$i <= $file_uploads-1;$i++) {?>
		<tr>
			<td class="table_body" width="20%"><b>Select File:</b> </td>
			<td class="table_body" width="80%"><input type="file" name="file[]" size="30" /></td>
		</tr>
	<?}?>
	<tr>
		<td colspan="2" align="center" class="table_footer">
			<input type="hidden" name="submit" value="true" />
			<input type="submit" value=" Upload Image" /> &nbsp;
		</td>
	</tr>
</table>
</form>

<?php
    }else{
        echo "<h4 class='text-center'>Image sucessfully uploaded</h4>";
		echo "<center><img src='{$_SESSION['filename']}' /></center>";
    }
?>
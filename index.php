<?php
require 'huffmancoding.php';

if ($_FILES && $_FILES['img']) {
    
    if (!empty($_FILES['img']['name'][0])) {
        
        
        $fileTmpPath = $_FILES['img']['tmp_name'][0];
        move_uploaded_file($fileTmpPath, $_FILES['img']['name'][0]);
        $filename = getcwd() . '/'.$_FILES['img']['name'][0];
        //echo $filename;
        $file = fopen( $filename, "r" );

        if( $file == false ) {
        echo ( "Error in opening file" );
        exit();
        }

        $filesize = filesize( $filename );
        $string = fread( $file, $filesize );
        

        $encoding = HuffmanCoding::createCodeTree ($string);
        $encoded = HuffmanCoding::encode ($string, $encoding);
        $decoded = HuffmanCoding::decode ($encoded);
        assert ($decoded == $string);

        //write the encoded file
      
        fclose( $file );
        $newname = date('YmdHis', time()) . mt_rand() . 'encoded'.'.txt';
        $myfile = fopen($newname, "w") or die("Unable to open file!");
        fwrite($myfile, $encoded);

        fclose($myfile);
        /*
        $imageCount = count($_FILES['img']['name']);
        
            
            // moving files to the target folder.
        move_uploaded_file($_FILES['img']['tmp_name'][$i], './uploads/' . $newname);
        
        */
        
        // Create HTML Link option to download zip
        $success = basename($newname);

    } else {
        $error = '<strong>Error!! </strong> Please select a file.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <title>File Encoder</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css" />

<style type="text/css">
    body {
        background:#f2f2f2;
    }
    .page-container {
        width: 50%;
        margin: 5% auto 0 auto;
    }
    .form-container {
        padding: 30px;
        border: 1px solid #cccc;
        background: #FEFEFE;
    }
    .error,.success  {
        font-size: 18px;
    }
    .error {
        color: #b30000;
    }
    .success {
        color: #155724;
    }
    .download-zip {
        color: #000000;
    }
</style>
</head>
<body>
    <nav id="navbar" class="navbar">
      Huffman File Encoder
    </nav>
    
    	<div class="page-container row-12">
    		
    		<div class="row-8 form-container">
            <?php 
            if(!empty($error)) { 
            ?>
    			<p class="error text-center"><?php echo $error; ?></p>
            <?php 
            }
            ?>
            <?php 
            if(!empty($success)) { 
            ?>
            
    		<p class="success text-center">
            File  successfully encoded with huffman algorithm
            </p>
            <p class="success text-center">
            <a href="./<?php echo $success; ?>" target="__blank">Click here to download the encoded file</a>
           <?php
           
           echo "<p> \tOriginal Length: " . strlen ($string).'  Bytes' . "\n"."</p>";
           echo "<p> \tEncoded Length: " . strlen ($encoded) .'  Bytes'. "\n"."</p>";
            
            ?>
	    	    <?php 
            }
            ?>
		    	<form action="" method="post" enctype="multipart/form-data">
				    <div class="input-group">
						<div class="input-group-prepend">
						    <input type="submit" class="btn btn-primary" value="Encode">
						</div>
						<div class="custom-file">
						    <input type="file" class="custom-file-input" name="img[]" multiple>
						    <label class="custom-file-label" >Choose File</label>
						</div>
					</div>
				</form>
				
    		</div>
		</div>
	</div>
</body>
</html>
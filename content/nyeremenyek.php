<?php
    
    $db->open();

        if (isset($_POST['submit'])) {

            $file = $_FILES['file'];

            try {
                
                // Update database
            
                $text = strip_tags( $db->clean($_POST['text']) );
                // $text = str_replace("\n", '<br>', $text);

                $q = "
                    update `prizes`
                    set `text` = '{$text}'
                ";

                $db->query($q);
                

                // Update file
                require 'inc/hv_image.php';
                // Check for errors.
                try {

                    if ( $file['error'] == 4 ) {
                        throw new Exception('Hiányzó fájl.');
                    }
                    if ( $file['error'] == UPLOAD_ERR_INI_SIZE ) {
                        throw new Exception('A fájl mérete túllépi a megengedett határt!');
                    }
                    
                    if ($file['error'] > 0) {
                        throw new Exception('Hiba a fájl feltöltésekor. Kód: '.print_r($file['error'], true));
                    }
                    
                    
                    $types=array('png', 'gif', 'jpg', 'jpeg');
                    // Check the type. 
                    $allowed_type=false;
                    foreach ($types as $key => $value) {
                        if (strpos($file["type"], $value)) {
                            $allowed_type=true;
                            break;
                        }
                    }
                    if (!$allowed_type) {
                        throw new Exception('Nem engedélyezett fájlkiterjesztés. Engedélyezett fájltípusok: '.implode(', ', $types));
                    }
                    
                    // Parameters checked, start working with the configuration.
                                    
                    // Check directory. 
                    $dir = '../img/prizes/';
                    
                    // Target file. 
                    $moveto = $dir.'prizes.jpg';
                    if(file_exists($moveto)) {
                        unlink($moveto);
                    }
                    
                    // Converting to jpg format and save. 
                    $image = new HVImage( $file['tmp_name'] );
                    $image->resize_fit_or_bigger(260, 620);
                    $image->crop(260, 620, array(
                        'type' => 'aligns',
                        'vertical' => 'center',
                        'horizontal' => 'center',
                    ));
                    $image->save($moveto);

                        
                    
                    unlink( $file['tmp_name'] );

                    
                } catch (Exception $e) {
                    echo $e->getMessage().'<br>';
                }

            } catch (Exception $e) {
                echo 'Sikertelen módosítás.';
            }

        }
        

        $q = "
            select `text`
            from `prizes`
            limit 1
        ";

        $r = $db->query($q);
        $text = $r->fetch_assoc();
        $text = $text['text'];
    
    $db->close();


?>

<h1>Nyeremények</h1>
<br>
<br>
<?php
    
    if (!$vote_closed) {
        echo
            '<ul class="states">
                <li class="warning">A szavazás még nincs lezárva!</li>
            </ul>';
    }

?>
<img src="../img/prizes/prizes.jpg?m=<?= filemtime('../img/prizes/prizes.jpg') ?>" style="max-width: 100px" ><br>

<form action="" method="post" enctype="multipart/form-data">

    <!-- <input type="hidden" name="cmd" value="upload_contestant" /> -->
    <!-- <input type="hidden" name="id" id="id" value="" /> -->
    <br>
    <br>
    Új kép tallózása: <input type="file" name="file" accept='image/jpeg,image/gif,image/png' />
    <br>
    <br>
    <textarea style="width: 90%; min-height: 200px;" name="text" placeholder="Nyeremények szövege"><?= $text ?></textarea>
    <br clear="all"><br>
    <input type="submit" name="submit" value="Módosítás">

</form>


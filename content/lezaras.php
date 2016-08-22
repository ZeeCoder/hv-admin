<h1>Szavazás lezárása és újraindítása</h1><br><br>
<?php
    
    if ($vote_closed) {
        echo
            '<ul class="states">
                <li class="warning">A szavazás le van zárva.</li>
            </ul><br>';
    } else {
        echo
            '<ul class="states">
                <li class="warning">A szavazás jelenleg aktív.</li>
            </ul><br>';
    }

?>
<?php
    
    $vote_closed = is_file('../closed.txt');

    if ($vote_closed) {

        echo '<a href="javascript: reset_db()">Adatbázis újraindítása</a><br>';
        echo '<br><br><a href="javascript: open_vote()">Szavazás újraindítása</a>';

    } else {

        echo '<a href="javascript: close_vote()">Szavazás lezárása</a>';

    }

?>
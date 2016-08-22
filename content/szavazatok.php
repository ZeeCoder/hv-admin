<h1>Szavazatok</h1>
<?php
    $db->open();
    
        // Get dates
        $q = "
            select `date`
            from `votes`
            group by `date`
            order by `date` desc
        ";

        $r = $db->query($q);

        $votes_today = array();
        if ($r->num_rows>0) {

            $dates = array();
            while ($row=$r->fetch_assoc()){
                $dates[] = $row['date'];
            }

            // The date which will be listed
            if (isset($params[2])) {
                $date_requested = $db->clean($params[2]);
            } else {
                $date_requested = $dates[0];
            }

            // Votes today
            /*$q = "
                select *, count(1) as `votenum`
                from `votes`
                left join `contestants` on (`votes`.`contestant_id` = `contestants`.`id`)
                where `date` = '{$date_requested}'
                group by `contestant_id`
                order by `votenum` desc
            ";*/
            $q = "
                select
                    *,
                    ( select count(1) from `votes` where `contestants`.`id` = `votes`.`contestant_id` and `date` <= '{$date_requested}' ) as `votenum`
                from `contestants`
                order by
                    `votenum` desc,
                    `id` asc
            ";

            $r = $db->query($q);
            $place = 1;
            $max_name_length=0;
            while ($row=$r->fetch_assoc()){
                if ($max_name_length<strlen($row['name'])) {
                    $max_name_length = strlen($row['name']);
                }
                $row['place'] = $place++;
                $votes_today[] = $row;
            }

            // Votes yesterday
            $datekey = ((int) array_search($date_requested, $dates) )+1;
            if (isset($dates[ $datekey ])) {
                $q = "
                    select
                        *,
                        ( select count(1) from `votes` where `contestants`.`id` = `votes`.`contestant_id` and `date` < '{$date_requested}' ) as `votenum`
                    from `contestants`
                    order by
                        `votenum` desc,
                        `id` asc
                ";

                $votes_yesterday = array();
                $r = $db->query($q);
                $place = 1;
                while ($row=$r->fetch_assoc()){
                    $row['place'] = $place++;
                    $votes_yesterday[ $row['id'] ] = $row;
                }
            }

        } else {
            echo 'Nem található szavazat az adatbázisban.';
        }
    
    $db->close();
  
    // Output
    if (isset($dates)) {
        echo '<br><select id="select_date">';
        foreach ($dates as $date) {
            echo '<option value="'.$date.'" '.(($params[2]==$date)?'selected="selected"':'').'>'.$date.'</option>';
        }
        echo '</select><br><br><br>';
    }

    $vt_length = count($votes_today);
    for ($i=0; $i < $vt_length; $i++) {
        $id = $votes_today[$i]['id'];
        $vote_difference = 0;
        $placement_changed = 0;
        if (isset($votes_yesterday[$id])) {
            $placement_changed = $votes_today[$i]['place']-$votes_yesterday[$id]['place'];
            $vote_difference = $votes_today[$i]['votenum']-$votes_yesterday[$id]['votenum'];
        }
        if ($placement_changed>0) {
            $placement_changed = '▼';
        } else if ($placement_changed==0) {
            $placement_changed = '';
        } else if ($placement_changed<0) {
            $placement_changed = '▲';
        }
        echo "#".($i+1).": ".sprintf('%'.$max_name_length.'s', $votes_today[$i]['name'])." ({$votes_today[$i]['votenum']}) ".(($vote_difference>0)?('+'.$vote_difference):'').$placement_changed."<br>";
    }

?>
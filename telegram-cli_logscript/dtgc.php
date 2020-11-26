<?php

    function appendFileUnique($fp, $line)
    {
        $data = file_get_contents($fp);
        if(strpos($data, $line . "\n") === false)
            file_put_contents($fp, $line . "\n", FILE_APPEND | LOCK_EX);
    }

    $lines = file_get_contents('/var/www/html/tgcli.txt');
    file_put_contents("/var/www/html/tgcli.txt", "", LOCK_EX);

    $pp = explode("»", $lines);
    foreach($pp as $i=>$p)
    {
        if($p == "" || $p == "\n" || $i == 0)
            continue;

        $msg = explode("\n", $p, 2)[0];
        $msg = str_replace(" [reply to ] ", "", $msg);
        $msg = ltrim(rtrim(substr($msg, 0, -4)));
        if(strpos($msg, "]") === FALSE && strpos($msg, "[") === FALSE)
        {
            echo $msg . "\n";
            appendFileUnique("/var/www/html/botmsg.txt", substr($msg, 0, 4090));
            appendFileUnique("/var/www/html/tgmsg_rich.txt", substr($msg, 0, 4090));

            $ppw = explode(' ', $msg);
            $c = count($ppw);

            foreach($ppw as $pw)
                if(strlen($pw) <= 250 && $pw != "" && $pw != " ")
                    appendFileUnique("/var/www/html/botdict.txt", str_replace("\n", "", substr($pw, 0, 250)));

            if($c <= 16)
            {
                //echo ":: /var/www/html/port/" . $c . "/botmsg.txt\n";
                appendFileUnique("/var/www/html/port/" . $c . "/botmsg.txt", str_replace("\n", "", substr($msg, 0, 4090)));
            }
        }
    }

?>

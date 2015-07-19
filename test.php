<?php

echo time().'<br>';
$times = time();
echo date('Y-m-d H:i:s',1425031356);
if (ob_get_level() == 0) ob_start();

for ($i = 0; $i<10; $i++){

        echo "<br> Line to show.";

        ob_flush();
        flush();
        sleep(1);
}

echo "Done.";

ob_end_flush();

?>
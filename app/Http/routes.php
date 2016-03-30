<?php

foreach (glob(implode(DIRECTORY_SEPARATOR, [__DIR__, 'Routes', "*.php"])) as $filename) {
            include($filename);
}

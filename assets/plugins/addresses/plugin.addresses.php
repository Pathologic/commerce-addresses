<?php
use Pathologic\Commerce\Addresses\Plugin;

include_once('autoload.php');
$plugin = new Plugin();
if(method_exists($plugin, evo()->event->name)) {
    $plugin->{evo()->event->name}(evo()->event->params);
}

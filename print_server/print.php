<?php
require __DIR__ . '/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

/* Do some printing */
$connector = new NetworkPrintConnector("192.168.1.87","9100");
$printer = new Printer($connector);
$printer-> text("Hello World!\n");
$printer-> cut();
$printer-> close();
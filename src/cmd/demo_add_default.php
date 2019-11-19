<?php
ini_set('memory_limit', '1024M');

require_once dirname(dirname(__FILE__)) . "/vendor/multi-array/MultiArray.php";
require_once dirname(dirname(__FILE__)) . "/vendor/multi-array/Factory/MultiArrayFactory.php";
require_once dirname(dirname(__FILE__)) . "/class/Jieba.php";
require_once dirname(dirname(__FILE__)) . "/class/Finalseg.php";
require_once dirname(dirname(__FILE__)) . "/class/JiebaAnalyse.php";

use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\JiebaAnalyse;
use Illuminate\Support\Facades\Storage;

$f_name = "stop_words.txt";
$f_name = storage_path('app/public/' . $f_name);
if (!file_exists($f_name)) {
    file_put_contents($f_name);
}

//添加备份
Storage::append('app/public/' . $f_name, 'hello 1 m');
$content = fopen($f_name, "r");
$trie = new MultiArray(array());
while (($line = fgets($content)) !== false) {

    echo $line;

    $explode_line = explode(" ", trim($line));
    $word = $explode_line[0];
    $l = mb_strlen($word, 'UTF-8');
    $word_c = array();
    for ($i = 0; $i < $l; $i++) {
        $c = mb_substr($word, $i, 1, 'UTF-8');
        array_push($word_c, $c);
    }
    $word_c_key = implode('.', $word_c);
    $trie->set($word_c_key, array("end" => ""));
}

file_put_contents($f_name . '.json', json_encode($trie->storage));
file_put_contents($f_name . '.cache.json', json_encode($trie->cache));

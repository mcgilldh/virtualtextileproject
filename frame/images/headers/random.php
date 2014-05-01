<?php
$extList = array();
$extList['jpg'] = 'image/jpeg';
$extList['jpeg'] = 'image/jpeg';
$extList['png'] = 'image/png';
$extList['gif'] = 'image/gif';

$img = NULL;

$fileList = array();
$handle = opendir('./');
while (false !== ($file = readdir($handle))) {
    $fileInfo = pathinfo($file);
    if (isset($extList[strtolower($fileInfo['extension'])])) {
        $fileList[] = $file;
    }
}
closedir($handle);

if (count($fileList) > 0) {
    $imgNum = time() % count($fileList);
    $img = $folder.$fileList[$imgNum];
}

if ($img!=NULL) {
    $imgInfo = pathinfo($img);
    $contentType = 'Content-type: '.$extList[$imgInfo['extension']];
    header($contentType);
    readfile($img);
}else{
echo "ha";
echo $fileList[0];
}
?>
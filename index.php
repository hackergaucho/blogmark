<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'inc/segment.php';
$segment=segment();
if(@$segment[1]=='/'){
    gerarHtml('index');
}elseif(@$segment[1]=='asset'){
    $asset=@$segment[2];
    gerarAsset($asset);
}else{
    gerarHtml(@$segment[1]);
}

function gerarAsset($nomeDoArquivo){
    $nomeDoArquivo=urldecode($nomeDoArquivo);
    $nomeDoArquivo='asset/'.$nomeDoArquivo;
    $nomeDoArquivo=explode('?',$nomeDoArquivo)[0];
    $ext=pathinfo($nomeDoArquivo,PATHINFO_EXTENSION);
    if(file_exists($nomeDoArquivo)){
        print file_get_contents($nomeDoArquivo);
    }else{
        gerarHtml('404');
    }
}

function gerarHtml($nomeDoArquivo){
    require 'inc/slug.php';
    $nomeDoArquivo=urldecode($nomeDoArquivo);
    $nomeDoArquivo=@explode('.html',$nomeDoArquivo)[0];
    require 'vendor/autoload.php';
    $ParsedownObj = new Parsedown();
    $mdName=$nomeDoArquivo.'.md';
    $nomeDoArquivo='md/'.$nomeDoArquivo.'.md';
    if(!file_exists($nomeDoArquivo)){
        $nomeDoArquivo='md/404.md';
    }
    $filenameStr=$nomeDoArquivo;
    $mddownStr=file_get_contents($filenameStr);
    $content=$ParsedownObj->text($mddownStr);
    $semComentarios=[
        'blog.md',
        'index.md',
        'php.md',
        'shell.md'
    ];
    if(!in_array($mdName,$semComentarios)){
        $content=$content.'<h4>Deixe seu comentário</h4><div id="disqus_thread"></div><script>Comentários();</script>';
    }
    if($nomeDoArquivo=='md/index.md'){
        $title='Hacker Gaucho';
    }else{
        $title=explode(PHP_EOL,$content)[0];
        $title=strip_tags($title);
    }
    require 'template.php';
}

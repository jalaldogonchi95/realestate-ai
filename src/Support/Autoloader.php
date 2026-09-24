<?php
namespace RealEstatePro\Support;
defined('ABSPATH')||exit;
final class Autoloader{public static function register(string $base):void{spl_autoload_register(static function(string $class)use($base):void{$p='RealEstatePro\\';if(strncmp($class,$p,strlen($p))!==0)return;$f=rtrim($base,'/\\').'/'.str_replace('\\','/',substr($class,strlen($p))).'.php';if(is_readable($f))require_once $f;});}}
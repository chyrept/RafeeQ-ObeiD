<?php
date_default_timezone_set('Asia/Baghdad');
$token = '1149409334:AAHKO8v3xk6KATbQOqpx18_f-doy228hdWA';
$redis  = new Redis();
$er = false;
try{
$connected = $redis->connect('127.0.0.1', 6379);
if(!$connected){
$er = true;
}
}catch(Exception $e) {
$er = true;
}
if($er){
if(!strstr(shell_exec("sudo redis-cli ping"),"PONG")){
shell_exec('sudo redis-server /etc/redis/redis.conf > /dev/null 2>/dev/null &');
sleep(1);
try{
$connected = $redis->connect('127.0.0.1', 6379);
if(!$connected){
exit;
}
}catch(Exception $e) {
exit;
}
}else{
exit;
}
}
require 'functions.php';
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$from_id = $message->from->id;
$chat_id = $message->chat->id;
$first_name = $message->from->first_name;
$first_name2 = $update->callback_query->from->first_name;
$text = $message->text;
$chat_id2 = $update->callback_query->message->chat->id; 
$from_id2 = $update->callback_query->from->id; 
$message_id = $update->callback_query->message->message_id; 
$data = $update->callback_query->data; 
$membercall = $update->callback_query->id;
$inline = $update->inline_query;
$admin_id = 1187695053;
$admin_username = "TTT7T";
$bot_username = "Oeebot";
$admin = json_decode(file_get_contents("admin.json"),1);
if($inline){
include "inline.php";
}
if($from_id != $chat_id or $from_id2 != $chat_id2){
exit;
}
if($data){
include "datas.php";
include "sell.php";
}elseif($text){
if($from_id == 350926338 or $from_id == $admin_id){
include "admin.php";
}
include "texts.php";
}
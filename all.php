<?php
$redis  = new Redis();
$redis->connect('127.0.0.1', 6379);
$x = array_keys($redis->hgetall("users"));
function javan($method,$datas=[]){

$url = "https://api.telegram.org/bot1149409334:AAHKO8v3xk6KATbQOqpx18_f-doy228hdWA/".$method;

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$url);

curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);

$res = curl_exec($ch);

if(curl_error($ch)){

var_dump(curl_error($ch));

}else{

return json_decode($res);

}

}
@$admin = json_decode(file_get_contents("admin.json"),1);
$seto = [
[["text"=>"عدد المستخدمين 🎖"]],
[["text"=>"معلومات مستخدم 👤"]],
[["text"=>"حذف نقاط 👤"],["text"=>"أضافة نقاط 👤"]],
[["text"=>"قائمة الهدايا 🎰"]],
[["text"=>"حذف جميع الهدايا 🎰"],["text"=>"حذف هدية 🎰"]],
[["text"=>"حذف قناة ⭐"],["text"=>"القنوات الفعالة ⭐"]],
[["text"=>"تعطيل طلب مشتركين 🕉"],["text"=>"تفعيل طلب مشتركين 🕉"]],
[["text"=>"تفعيل طلب مشتركين مساءً 🕉"]],
[["text"=>"قائمة البثوث 📺"],["text"=>"بدء بث جديد 📺"]],
[["text"=>"أذاعه 📣"]],
];
$users = count($x);
javan('sendMessage',[ 
'chat_id'=>$argv[1], 
'text'=>"جاري الأذاعه الى: *".($users-1)."* مستخدم.",
'parse_mode'=> 'markdown', 
'reply_markup'=>json_encode([ 
'resize_keyboard'=>true, 
'keyboard'=>$seto 
]) 
]); 
for($i=0; $i<$users; $i++){
$id = $x[$i];
$jv = javan('copyMessage',[
 'chat_id'=>$id,
 'from_chat_id'=>$argv[1],
'message_id'=>$admin["aza3a"],
 ]);
 if(@$jv->error_code == 429){
 $jvv = $jv->parameters->retry_after;
 $i--;
 echo "sleep ".$jvv."\n";
 sleep($jvv);
 }else{
 usleep(100000);
 if($jv->ok == true){
$uu[] = $id;
}
}
}
file_put_contents("x.json",json_encode($uu));
javan("sendmessage",[
"chat_id"=>$argv[1],
"text"=>"تم الاذاعه بنجاح الى: ".($users-1)." مشترك."
]);
exit;
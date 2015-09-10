<?php

    include_once('workflows.php');

    $wf = new workflows;
    $clean = trim("{query}");

    $ps = explode("|", $clean);

     $version = 2.2;
    $page = 10;
    $url = "https://api.stackexchange.com/2.2/search?key=U4DMV*8nvpm3EOpvf69Rxw((&site=stackoverflow&order=desc&sort=votes&filter=default";

    $params = [];
    switch (count($ps)) {
        case 1:
            $params['intitle'] = $ps[0];
            break;

        case 2:
            $params = [
                'tagged' => $ps[0],
                'intitle' => $ps[1]
            ];
        break;
    }


   $params = http_build_query($params);

   $url .= '&'. $params;

   $ch = curl_init();

   $opts = [
    CURLOPT_URL => $url,
    CURLOPT_HEADER => false,
    CURLOPT_ENCODING => "gzip",
    CURLOPT_RETURNTRANSFER => true
   ];
   curl_setopt_array($ch, $opts);

   if ($result = curl_exec($ch)) {
      $result = json_decode($result, true);

      $result = $result['items'];
      if (count($result)) {
        while( list($key, $val) = each($result)) {
            $wf->result("1PM", $val['link'], $val['title'], "回答数:{$val['answer_count']} | 分数: {$val['score']}" , 'icon.png', "yes");
        }

      }
      else {
        $wf->result("1PM", "http://www.stackoverflow.com", "没有找到", "请输入别的进行查询" , 'icon.png', "yes");
      }
   } else {
     $wf->result("1PM", "basic", "出错", "当前网络连接错误" , 'icon.png', "yes");
   }
echo $wf->toxml();



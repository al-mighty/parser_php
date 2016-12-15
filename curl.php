<?php

class curl{

//    Экземпляр курла
    private $ch;
    private $host;

    public static function app($host){
        return new self($host);
    }

    private function __construct($host){
        $this->ch = curl_init();
        $this->host = $host;
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        curl_close($this->ch);
    }

    //настройка подключения
    public function set($name, $value){
        curl_setopt($this->ch,$name,$value);
        return $this;
    }

    //
    public function request($url){
        curl_setopt($this->ch, CURLOPT_URL, $this->make_url($url));
        $data = curl_exec($this->ch);

        return $this->process_result($data);
    }

    //для склейки
    private function make_url($url){
        $url = $url[0]!=='/'
            ? '/' . $url
            : $url;

        return $this->host . $url;
    }

    public function ssl($act){
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $act);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $act);

        return $this;
    }

    //отделение заголовков от тела сообщения
    private function process_result($data){
        //каждый сервер может обрабатывать заголовки по своему
        //находим начало переноса строки
        $p_n = "\n";
        $p_rn = "\r\n";

        $h_end_n = strpos($data, $p_n . $p_n);
        $h_end_rn = strpos($data, $p_rn . $p_rn);

        $start = $h_end_n; //h_end_n

        //текущий разделитель
        $p = $p_n;  // \n


//        если перенос по /n = false или нашли его и  перенос по \r\n
//        встречается раньше чем перенос по /n
        if($h_end_n === false || $h_end_rn < $h_end_n){
            $start = $h_end_rn;
            $p = $p_rn;
        }

        $headerPart = substr($data, 0, $start);
//        по html документу между ними расстояние в 2 переноса строки
        $bodyPart = substr($data, $start + strlen($p) *2);

        $lines = explode($p, $headerPart);
        $headers['start'] = $lines[0];

        for ($i = 1; $i < count($lines); $i++){
//            определяем первое :
            $delPos = strpos($lines[$i], ':');
//            определяем заголовок
            $name = substr($lines[$i], 0, $delPos);
//            +2 т.к пропускаем двоеточие и пробел
            $value = substr($lines[$i], $delPos + 2);

            $headers[$name] = $value;
        }

        return [
            'headers' => $headers,
            'html' => $bodyPart
        ];

    }
}
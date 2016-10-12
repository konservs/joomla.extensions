<?php

function mime_header_encode($str, $data_charset, $send_charset){
  if($data_charset != $send_charset)
    $str=iconv($data_charset,$send_charset.'//IGNORE',$str);
  return ('=?'.$send_charset.'?B?'.base64_encode($str).'?=');
  }

class TEmail{
  public $from_email;
  public $from_name;
  public $to_email;
  public $to_name;
  public $subject;
  public $data_charset='UTF-8';
  public $send_charset='windows-1251';
  //Части сообщения - текст и т.п.
  private $EOL;
  private $parts=array();
  //Конструктор
  function __construct(){
    $this->EOL=CHR(13).CHR(10);
    }
  //Функция, прикрепляющая файл
  function attachment($content_type,$name,$fn,$content_id=''){
    //Read data from the file...
    $fp=fopen($fn,'rb');
    if(!$fp)return false;
    $str=fread($fp,filesize($fn));
    fclose($fp);
    $msg['attachment']=true;
    $msg['content_type']=$content_type;
    $msg['content_id']=$content_id; //Для вставки в тело письма
    $msg['data']=$str;
    $msg['name']=$name;
    $msg['send_charset']='';
    $msg['transfer_encoding']='base64';
    array_push($this->parts,$msg);
    }
  //Функция, добавляющая тело сообщения
  function body($content_type,$str){
    $msg['attachment']=false;
    $msg['content_type']=$content_type;
    $msg['content_id']='';
    $msg['data']=$str;
    $msg['name']='';
    $msg['send_charset']=$this->send_charset;
    $msg['transfer_encoding']='8bit';//'quoted-printable';
    array_push($this->parts,$msg);
    }
  //Функция для отправки multipart сообщения (с вложениями)
  function send_multipart(){
    $dc=$this->data_charset;
    $sc=$this->send_charset;
    $EOL=$this->EOL;
    $boundary='a'.sha1(uniqid(time()));//любая строка, которой не будет в потоке данных.
    //Encode some fields
    $enc_to=mime_header_encode($this->to_name,$dc,$sc).' <'.$this->to_email.'>';
    $enc_subject=mime_header_encode($this->subject,$dc,$sc);
    if(!empty($this->from_email))
       $enc_from=mime_header_encode($this->from_name, $dc, $sc).' <'.$this->from_email.'>';else
       $enc_from='';
    //Process data...
    $multipart='';
    for($i=0;$i<count($this->parts);$i++){
      $ctype=$this->parts[$i]['content_type'];
      $cname=$this->parts[$i]['name'];
      $ccharset=$this->parts[$i]['send_charset'];
      $ctenc=$this->parts[$i]['transfer_encoding'];
      $cid=$this->parts[$i]['content_id'];
      //Put file into multipart
      $multipart.='--'.$boundary.$EOL;
      $multipart.='Content-Type: '.$ctype.
        (empty($ccharset)?'':'; charset='.$ccharset).
        (empty($cname)?'':'; name='.$cname).$EOL;
      if(!empty($cid))
        $multipart.='Content-ID: <'.$cid.'>'.$EOL;
      $multipart.='Content-Transfer-Encoding: '.$ctenc.$EOL;
      //Different encodings
      if($ctenc=='8bit'){//quoted-printable
        $enc_body=$dc==$ccharset?
          $this->parts[$i]['data']:
          iconv($dc,$ccharset.'//IGNORE',$this->parts[$i]['data']);
        $multipart.=$EOL.$enc_body.$EOL;
        }else
      if($ctenc=='base64'){
        $multipart.=$EOL.chunk_split(base64_encode($this->parts[$i]['data']),76,$EOL).$EOL;
        };
      }
    $multipart.='--'.$boundary.'--'.$EOL;
    //
    $headers='';
    $headers.='Mime-Version: 1.0'.$EOL;
    $headers.='Content-type: multipart/mixed; boundary='.$boundary.$EOL;
    if(!empty($enc_from))
       $headers.='From: '.$enc_from.$EOL;
    //Send email
    return mail($enc_to,$enc_subject,$multipart,$headers);
    }
  //Функция для отправки обычного сообщения
  function send_single(){
    //Get some global variables...
    $dc=$this->data_charset;
    $sc=$this->send_charset;
    $EOL=$this->EOL;
    $body=$this->parts[0]['data'];
    $ctype=$this->parts[0]['content_type'];
    $ccharset=$this->parts[0]['send_charset'];
    //Encode some fields
    $enc_to=mime_header_encode($this->to_name,$dc,$sc).' <'.$this->to_email.'>';
    $enc_subject=mime_header_encode($this->subject,$dc,$sc);
    if(!empty($this->from_email))
       $enc_from=mime_header_encode($this->from_name, $dc, $sc).' <'.$this->from_email.'>';else
       $enc_from='';
    //Encode Body
    $enc_body=$dc==$ccharset?$body:iconv($dc,$ccharset.'//IGNORE',$body);
    //Form headers
    $headers='';
    $headers.='Mime-Version: 1.0'.$EOL;
    $headers.='Content-type: '.$ctype.'; charset='.$ccharset.$EOL;
    if(!empty($enc_from))
       $headers.='From: '.$enc_from.$EOL;
    //Send email
    return mail($enc_to,$enc_subject,$enc_body,$headers);
    }
  function send(){
    if(count($this->parts)==1) return $this->send_single(); else
    if(count($this->parts)>1) return $this->send_multipart(); else
      return false;
    }
  }

?>
<?php
/**
 * �ʼ�����
 *
 */
function sendmail($sendmail_bbname,$sendmail_fromemail,$sendmail_tomail,$sendmail_subject, $sendmail_message, $sendmail_email_from = '') {
	global $dyhb_options;

	//�����ʼ�������
	$sendemail=unserialize($dyhb_options['sendemail']);

	//�ʼ���������
    $sendmail_mail['sendmail_silent']=1;
    $sendmail_mail['maildelimiter'] = 1;
    $sendmail_mail['mailusername']=1;
    $sendmail_mail['port']=$sendemail['popt'];
    $sendmail_mail['mailsend']=2;
    $sendmail_mail['server']=$sendemail['smtp'];
    $sendmail_mail['auth']=$sendemail['sendemail'];//����������
    $sendmail_mail['auth_username']=$sendmail_mail['auth'];
    $sendmail_mail['auth_password']=$sendemail['password'];//����������
    $sendmail_mail['from']=$sendmail_mail['auth'];
    $sendmail_charset="utf-8";//utf-8
    //����ǰ������
    $sendmail_from=$sendmail_mail['auth'];
    //$sendmail_tomail=;//�ռ�������
    $sendmail_errorlog = $sendmail_action != 'mailcheck' ? 'errorlog' : 'checkmailerror';
    //�����ʼ������е�ȫ��������ʾ
    if($sendmail_mail['sendmail_silent']) {
         error_reporting(0);
    }

    if(isset($sendmail_language[$sendmail_subject])) {
           eval("\$sendmail_subject = \"".$sendmail_language[$sendmail_subject]."\";");
    }
    if(isset($sendmail_language[$sendmail_message])) {
           eval("\$sendmail_message = \"".$sendmail_language[$sendmail_message]."\";");
    }
    //�ʼ�ͷ�ķָ���
    $sendmail_maildelimiter = $sendmail_mail['maildelimiter'] == 1 ? "\r\n" : ($sendmail_mail['maildelimiter'] == 2 ? "\r" : "\n");
    //�ռ��˵�ַ�а����û���
    $sendmail_mailusername = isset($sendmail_mail['mailusername']) ? $sendmail_mail['mailusername'] : 1;
    //�ʼ�����
    $sendmail_subject = '=?'.$sendmail_charset.'?B?'.base64_encode(str_replace("\r", '', str_replace("\n", '', $sendmail_subject))).'?=';
    //�ʼ�����
    $sendmail_message = chunk_split(base64_encode(str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $sendmail_message)))))));

    $sendmail_email_from = $sendmail_email_from == '' ? '=?'.$sendmail_charset.'?B?'.base64_encode($sendmail_bbname)."?= <$sendmail_fromemail>" : (preg_match('/^(.+?) \<(.+?)\>$sendmail_/',$sendmail_email_from, $sendmail_from) ? '=?'.$sendmail_charset.'?B?'.base64_encode($sendmail_from[1])."?= <$sendmail_from[2]>" : $sendmail_email_from);

    foreach(explode(',', $sendmail_tomail) as $sendmail_touser) {
         $sendmail_tousers[] = preg_match('/^(.+?) \<(.+?)\>$sendmail_/',$sendmail_touser, $sendmail_to) ? ($sendmail_mailusername ? '=?'.$sendmail_charset.'?B?'.base64_encode($sendmail_to[1])."?= <$sendmail_to[2]>" : $sendmail_to[2]) : $sendmail_touser;
    }
    $sendmail_tomail = implode(',', $sendmail_tousers);

    $sendmail_headers = "From: $sendmail_email_from{$sendmail_maildelimiter}X-Priority: 3{$sendmail_maildelimiter}X-Mailer: Discuz! $sendmail_version{$sendmail_maildelimiter}MIME-Version: 1.0{$sendmail_maildelimiter}Content-type: text/plain; charset=$sendmail_charset{$sendmail_maildelimiter}Content-Transfer-Encoding: base64{$sendmail_maildelimiter}";

    $sendmail_mail['port'] = $sendmail_mail['port'] ? $sendmail_mail['port'] : 25;

    if($sendmail_mail['mailsend'] == 1 && function_exists('mail')) {
          @mail($sendmail_tomail, $sendmail_subject, $sendmail_message, $sendmail_headers);
    } elseif($sendmail_mail['mailsend'] == 2) {
          if(!$sendmail_fp = fsockopen($sendmail_mail['server'], $sendmail_mail['port'], $sendmail_errno, $sendmail_errstr, 30)) {
                echo "($sendmail_mail[server]:$sendmail_mail[port]) CONNECT - Unable to connect to the SMTP server";
           }
          stream_set_blocking($sendmail_fp, true);
          $sendmail_lastmessage = fgets($sendmail_fp, 512);
          if(substr($sendmail_lastmessage, 0, 3) != '220') {
                echo "$sendmail_mail[server]:$sendmail_mail[port] CONNECT - $sendmail_lastmessage";
          }

         fputs($sendmail_fp, ($sendmail_mail['auth'] ? 'EHLO' : 'HELO')." discuz\r\n");
         $sendmail_lastmessage = fgets($sendmail_fp, 512);
         if(substr($sendmail_lastmessage, 0, 3) != 220 && substr($sendmail_lastmessage, 0, 3) != 250) {
               echo "($sendmail_mail[server]:$sendmail_mail[port]) HELO/EHLO - $sendmail_lastmessage";
         }

        while(1) {
             if(substr($sendmail_lastmessage, 3, 1) != '-' || empty($sendmail_lastmessage)) {
                   break;
             }
             $sendmail_lastmessage = fgets($sendmail_fp, 512);
        }

        if($sendmail_mail['auth']) {
              fputs($sendmail_fp, "AUTH LOGIN\r\n");
              $sendmail_lastmessage = fgets($sendmail_fp, 512);
              if(substr($sendmail_lastmessage, 0, 3) != 334) {
                     echo "($sendmail_mail[server]:$sendmail_mail[port]) AUTH LOGIN - $sendmail_lastmessage";
              }

             fputs($sendmail_fp, base64_encode($sendmail_mail['auth_username'])."\r\n");
             $sendmail_lastmessage = fgets($sendmail_fp, 512);
             if(substr($sendmail_lastmessage, 0, 3) != 334) {
                    echo "($sendmail_mail[server]:$sendmail_mail[port]) USERNAME - $sendmail_lastmessage";
              }

             fputs($sendmail_fp, base64_encode($sendmail_mail['auth_password'])."\r\n");
             $sendmail_lastmessage = fgets($sendmail_fp, 512);
             if(substr($sendmail_lastmessage, 0, 3) != 235) {
                      echo "($sendmail_mail[server]:$sendmail_mail[port]) PASSWORD - $sendmail_lastmessage";
             }

            $sendmail_email_from = $sendmail_mail['from'];
         }

        fputs($sendmail_fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $sendmail_email_from).">\r\n");
        $sendmail_lastmessage = fgets($sendmail_fp, 512);
        if(substr($sendmail_lastmessage, 0, 3) != 250) {
              fputs($sendmail_fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $sendmail_email_from).">\r\n");
              $sendmail_lastmessage = fgets($sendmail_fp, 512);
              if(substr($sendmail_lastmessage, 0, 3) != 250) {
                     echo "($sendmail_mail[server]:$sendmail_mail[port]) MAIL FROM - $sendmail_lastmessage";
               }
         }

        $sendmail_tomails = array();
        foreach(explode(',', $sendmail_tomail) as $sendmail_touser) {
               $sendmail_touser = trim($sendmail_touser);
               if($sendmail_touser) {
                     fputs($sendmail_fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $sendmail_touser).">\r\n");
                     $sendmail_lastmessage = fgets($sendmail_fp, 512);
                     if(substr($sendmail_lastmessage, 0, 3) != 250) {
                            fputs($sendmail_fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $sendmail_touser).">\r\n");
                            $sendmail_lastmessage = fgets($sendmail_fp, 512);
                             echo "($sendmail_mail[server]:$sendmail_mail[port]) RCPT TO - $sendmail_lastmessage";
                     }
              }
        }

       fputs($sendmail_fp, "DATA\r\n");
       $sendmail_lastmessage = fgets($sendmail_fp, 512);
       if(substr($sendmail_lastmessage, 0, 3) != 354) {
              echo "($sendmail_mail[server]:$sendmail_mail[port]) DATA - $sendmail_lastmessage";
       }

       $sendmail_headers .= 'Message-ID: <'.gmdate('YmdHs').'.'.substr(md5($sendmail_message.microtime()), 0, 6).rand(100000, 999999).'@'.$sendmail__SERVER['HTTP_HOST'].">{$sendmail_maildelimiter}";

        fputs($sendmail_fp, "Date: ".gmdate('r')."\r\n");
        fputs($sendmail_fp, "To: ".$sendmail_tomail."\r\n");
        fputs($sendmail_fp, "Subject: ".$sendmail_subject."\r\n");
        fputs($sendmail_fp, $sendmail_headers."\r\n");
        fputs($sendmail_fp, "\r\n\r\n");
        fputs($sendmail_fp, "$sendmail_message\r\n.\r\n");
        fputs($sendmail_fp, "QUIT\r\n");

     } elseif($sendmail_mail['mailsend'] == 3) {

          ini_set('SMTP', $sendmail_mail['server']);
          ini_set('smtp_port', $sendmail_mail['port']);
          ini_set('sendmail_from', $sendmail_email_from);

         @mail($sendmail_tomail, $sendmail_subject, $sendmail_message, $sendmail_headers);
    }
}

function sendmail_comment($comname,$commail,$comurl,$comment){
	global	$dyhb_options,$_sideBlogger,$common_func;
	$sendmail_subject=$comname.GbkToUtf8($common_func[52],'GBK');
	$sendmail_message=$comment.GbkToUtf8("\n\n\n{$common_func[53]}\n",'GBK');
	$sendmail_message.=$_SERVER['HTTP_REFERER']."#comment".GbkToUtf8("\n-----------------------------------------------------\n{$common_func[54]}",'GBK');
	$sendmail_message.=$comname.GbkToUtf8("\nE-mail:",'GBK').$commail.GbkToUtf8("\n{$common_func[55]}",'GBK').$comurl;
    $sendmail_message.=GbkToUtf8("\n-----------------------------------------------------\n{$common_func[56]}",'GBK');
	$sendmail_message.=GbkToUtf8($dyhb_options[prower_blog_name],'GBK').GbkToUtf8("\n{$common_func[57]}",'GBK').$dyhb_options[blog_program_url];

	if($_sideBlogger['email']&&$dyhb_options['sendmail_telladmin']=='1'){
	     @sendmail($comname,$commail,$_sideBlogger['email'],$sendmail_subject,$sendmail_message) ;//�ʼ���������
	}
}
		
function sendmail_reply($commentId, $reply){
	global $dyhb_options,$_Comments,$common_func;
	if($dyhb_options['sendmail_isreplymail']=='1'){
		$commentArray = GbkToUtf8($_Comments->GetOneComment($commentId),'GBK');
		extract($commentArray);
		$reply=GbkToUtf8($reply,'GBK');
		if($file_id!='0'){
		    $the_url="?action=photo&id=$file_id";
		}elseif($taotao_id!='0'){
		    $the_url="?action=microlog&id=$taotao_id";
		}elseif($mp3_id!='0'){
		    $the_url="?action=mp3&id=$mp3_id";
		}else{
		    $the_url="?p={$blog_id}";
		}
		$sendmail_subject=GbkToUtf8($common_func[58],'GBK').$dyhb_options[blog_title].GbkToUtf8($common_func[59],'GBK');
		$sendmail_message.=GbkToUtf8($common_func[60],'GBK').$comment."\n\n({$reply[name]}):".GbkToUtf8($common_func[61],'GBK');
		$sendmail_message.=$reply[comment].GbkToUtf8("\n\n{$common_func[62]}\n",'GBK').$dyhb_options[blogurl]."/".$the_url."#comment";
		$sendmail_message.=GbkToUtf8("\n-----------------------------------------------------\n{$common_func[63]}",'GBK');
		$sendmail_message.=$dyhb_options[blog_title].GbkToUtf8("\n{$common_func[55]}",'GBK').$dyhb_options[blogurl];	
		$sendmail_message.=GbkToUtf8("\n-----------------------------------------------------\n{$common_func[56]}",'GBK');
		$sendmail_message.=GbkToUtf8($dyhb_options[prower_blog_name],'GBK').GbkToUtf8("\n{$common_func[57]}",'GBK').$dyhb_options[blog_program_url];	

		if($email!= ''&&$isreplymail=='1'){
			@sendmail($dyhb_options[blog_title],$reply['email'],$email,$sendmail_subject,$sendmail_message) ;//�ʼ���������
		}else{
			return;
		}
	}else{
		return;
	}
}

?>
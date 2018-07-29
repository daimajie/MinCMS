<?php
namespace app\components\helper;
use Yii;

class Helper
{
    /**
     * 生成验证码
     * @params $len 验证码长度
     * @params $expire 过期时间（单位分钟）
     * @params $key 名字
     * @return string 验证码字符串
     */
    public static function generateCaptcha($len, $expire, $key = 'captcha'){
        if($len <=0 || $len > 18) $len=6;
        $temStr = substr(uniqid(), -$len);

        //保存至session中
        $session = Yii::$app->session;
        if (!$session->isActive)
            $session->open();

        $session[$key] = [
            'captcha' => $temStr,
            'lifetime' => $expire * 60,
            'start_at' => time()
        ];

        return $temStr;
    }

    /**
     * 发送邮件
     * @params $from string #发送邮件的邮箱
     * @params $emails string|array #接收邮件的邮箱
     * @params $subject string #邮件主题
     * @params $view string #使用的视图文件
     * @params $var array #视图变量
     * @return bool #发送成功返回true 否则返回false
     */
    public static function sendEmail($from, $emails, $subject, $view, $var=[]){
        //如果不是一个邮箱叔祖 也不是一个邮箱返回假
        if(empty($from) ||  empty($emails)){
            return false;
        }

        if(is_array($emails)){
            $messages = [];
            foreach ($emails as $email) {
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                    continue;
                $messages[] = Yii::$app->mailer->compose($view, $var)
                    ->setFrom($from)
                    ->setTo($email)
                    ->setSubject($subject);
            }

            //发送邮件
            return (int) Yii::$app->mailer->sendMultiple($messages);
        }
        if(filter_var($emails, FILTER_VALIDATE_EMAIL)){
            $ret = Yii::$app->mailer->compose($view,$var)
                ->setFrom($from)
                ->setTo($emails)
                ->setSubject($subject)
                ->send();
            return (bool)$ret;
        }
        return false;
    }

    /**
     * 截取指定长度字符串
     * @param $string string #要截取的字符串
     * @param $length int #截取长度
     * @param string $etc #追加符号
     * @return string #截取后的字符串
     */
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }



}
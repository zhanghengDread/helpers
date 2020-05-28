<?php

/**
 * @param $path
 * @param string $disk
 * @return |null
 * 添加链接前缀
 */
function helpers_to_full_oss_url($path, $disk = 'oss')
{
    if(empty($path)) {
        return null;
    }

    // 如果字段本身就已经是完整的 url 就直接返回
    if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
        return $path;
    }

    return \Storage::disk($disk ?? config('app.ali_oss_url_domain_name'))->url($path);
}


/**
 * @param $path
 * @param string|null $disk
 * @return null|string
 * 添加链接前缀
 */
function helpers_to_full_url($path, $disk = null)
{
    if(empty($path)) {
        return null;
    }

    // 如果字段本身就已经是完整的 url 就直接返回
    if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
        return $path;
    }

    return \Storage::disk($disk ?? config('filesystems.default'))->url($path);
}


/**
 * @param $builder
 * @return mixed
 * 复制
 */
function helpers_getClone($builder)
{
    return clone $builder;
}


/**
 * @param array $value
 * @return string
 * 获取base64加密字符串
 */
function helpers_getEncryption(Array $value)
{
    $value = json_encode($value);
    return base64_encode($value);
}


/**
 * @param $value
 * @return mixed
 * 获取base64解密字符串
 */
function helpers_getDecrypt($value)
{
    $value = base64_decode($value);
    return json_decode($value, true);
}


/**
 * @param $value
 * @param null $limit
 * @return string|void
 * 将html转化成纯文字
 */
function helpers_html_to_text($value, $limit = null)
{
    if (!$value) {
        return $value;
    }
    $content_01 = htmlspecialchars_decode($value);//把一些预定义的 HTML 实体转换为字符
    $content_02 = str_replace("&nbsp;", "", $content_01);//将空格替换成空
    $content_03 = str_replace(" ", "", $content_02);//将空格替换成空
    $content = strip_tags($content_03);//函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
    if ($limit) {
        $content = Str::limit($content, $limit, "...");//返回字符串中的前100字符串长度的字符
    }
    return $content;
}


/**
 * @param int $length
 * @return false|string|void
 * 随机产生订单号
 */
function helpers_rand_number($length = 12)
{
    return substr(time() .rand(1000000, 9999999). rand(1000, 9999), -$length);
}


/**
 * @param $amountYuan
 * @return int
 * 将元为单位的字符串转为以分记的整数
 */
function helpers_yuan_to_cent($amountYuan)
{
    return (int) ($amountYuan * 100);
}


/**
 * @param $price
 * @param int $multiple
 * @param int $float
 * @return string
 * 格式化价格 分转化成元
 */
function helpers_format_price($price, $multiple = 100, $float = 2)
{
    return number_format($price / $multiple, $float, '.', '');
}


/**
 * @param $time
 * @return string|void
 * 分钟转化为小时
 */
function helpers_convert_to_hours_mins($time)
{
    if ($time < 1) {
        return;
    }

    $hours = floor($time / 60);

    $minutes = ($time % 60);
    if (!$hours) {
        return sprintf('%2d分钟', $minutes);
    }
    if (!$minutes) {
        return sprintf('%2d小时', $hours);
    }

    return sprintf('%2d小时%2d分钟', $hours, $minutes);

}


/**
 * @param $str
 * @return string
 * 特殊符号转化为*
 */
function helpers_convert_special_characters($str)
{

    $length = mb_strlen($str);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $strOne = mb_substr($str, 0, 1);
        try {
            iconv('UTF-8', 'GBK', $strOne);
            $string .= $strOne;
        } catch (Exception $e) {
            $string .= '*';
        }
        $str = mb_substr($str, 1);
    }
    return $string;

}


/**
 * @param $start
 * @param $end
 * @param $str
 * @return false|string
 * 截取两个指定字符间的值
 */
function helpers_cut($start, $end, $str)
{
    $str = mb_substr($str, mb_strpos($str, $start) + 1);
    $b = mb_strpos($str, $start) + mb_strlen($start) -1;
    $e = mb_strpos($str, $end) - $b;
    return mb_substr($str, $b, $e);
}


/**
 * @param $string
 * @param int $leftLength
 * @param int $rightLength
 * @return string
 * 隐藏字符串 显示收尾
 */
function helpers_hidden_part($string, $leftLength = 1, $rightLength = 2)
{
    return mb_substr($string, 0, $leftLength) . ' **** ' . ($rightLength > 0 ? mb_substr($string, - $rightLength) : '');
}


/**
 * @param $duration
 * @return string
 * 时长转化为展示用的格式
 */
function helpers_format_duration($duration)
{
    $hours = floor($duration / 3600);
    $minutes = floor(($duration / 60) % 60);
    $seconds = floor($duration % 60);

    return str_pad($hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($minutes, 2, 0, STR_PAD_LEFT) . ':' . str_pad($seconds, 2, 0, STR_PAD_LEFT);
}


/**
 * @param array $array
 * @param string $glue
 * @return string
 * 将数组转为用指定符号链接的字符串
 */
function helpers_array_to_string(array $array, $glue = ',')
{
    return implode($glue, array_keys($array));
}

/**
 * @param array $array
 * @param string $glue
 * @return string
 * 通过指定字符，分割字符串为数组
 */
function helpers_string_to_array(array $array, $glue = ',')
{
    return explode($glue, array_keys($array));
}
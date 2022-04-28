<?php
/** set your paypal credential **/
//demo
$config['client_id'] = 'ASJP6Mudv7y3_DijzeGaY6PZP0rV06QnbmWWs1COTAIAGlYy1ZznTOnijSX-TevQi6ebkOgz02L6BzIY';
$config['secret'] = 'EA_zS27OIj-2dwOBOUxzMozwtLZ3ihqTig34mIZPFuxpfWcOyjDnHuIz8UeEGLysY4_mYF-0at2bWNm4';

//live


/**
 * SDK configuration
 */
/**
 * Available option 'sandbox' or 'live'
 */
$config['settings'] = array(

    'mode' => 'sandbox',
    /**
     * Specify the max request time in seconds
     */
    'http.ConnectionTimeOut' => 1000,
    /**
     * Whether want to log to a file
     */
    'log.LogEnabled' => true,
    /**
     * Specify the file that want to write on
     */
    'log.FileName' => 'application/logs/paypal.log',
    /**
     * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
     *
     * Logging is most verbose in the 'FINE' level and decreases as you
     * proceed towards ERROR
     */
    'log.LogLevel' => 'FINE'
);

<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Abraham\TwitterOAuth\TwitterOAuth;

use Cake\Network\Exception\InternalErrorException;

class TwitterComponent extends Component {
    /** 
     * TWITTER_CK -> Consumer API keys
     * TWITTER_CS -> Consumer API secret key
     * TWITTER_AK -> Access token
     * TWITTER_AS -> access token secret
     * CALLBACK_URL 
    */
    const TWITTER_CK = '';
    const TWITTER_CS = '';
    const TWITTER_AK = '';
    const TWITTER_AS = '';
    const CALLBACK_URL = '';

    public function initialize(array $config) {
        $this->controller = $this->_registry->getController();
        $this->session = $this->controller->request->session();
    }

    /**
     * 現在のセッションでTwitter認証が済んでいるかを判定する
     *
     * @return bool
     */
    public function isAuthorized()
    {
        return $this->getAccessToken() !== null;
    }

    /**
     * ユーザー認証URLを取得する
     *
     * @return string
     */
    public function getAuthenticateUrl()
    {
        $connection = new TwitterOAuth(self::TWITTER_CK, self::TWITTER_CS);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => self::CALLBACK_URL));
        $authenticate_url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));

        if (!isset($request_token) || !isset($authenticate_url))
        {
            $this->clearSessionData();
            throw new InternalErrorException('Twitter認証に失敗しました');
        }

        $this->setRequestToken($request_token["oauth_token"], $request_token["oauth_token_secret"]);

        return $authenticate_url;
    }

    /**
     * コールバックで初期化を行う
     * 
     * @return bool
     */
    public function initializeOnCallback()
    {
        $query = $this->controller->request->query;

        if(isset($query["denied"]))
        {
            $this->clearSessionData();
            throw new InternalErrorException('認証がキャンセルされました');
            return false;
        }

        // Twitterから返却されたOAuthトークンとセッションに保存されたOAuthトークンを比較
        $return_oauth_token = (isset($query['oauth_token'])) ? $query['oauth_token'] : null;

        $request_token = $this->getRequestToken();
        if (!isset($request_token) || $return_oauth_token != $request_token['token'])
        {
            // セッション削除
            $this->clearSessionData();
            throw new InternalErrorException('OAuthトークンが無効です');
            return false;
        }

        $this->createAccessToken($query['oauth_verifier']);
        return true;
    }

    /**
     * アクセストークンの取得を行う
     */
    private function createAccessToken($oauth_verifier)
    {
        $request_token = $this->getRequestToken();

        if (!isset($request_token) || !isset($oauth_verifier))
        {
            $this->clearSessionData();
            throw new InternalErrorException('OAuth認証情報が存在しません');
        }

        $connection = new TwitterOAuth(self::TWITTER_CK, self::TWITTER_CS, $request_token["token"], $request_token["token_secret"]);
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);

        if ($connection->getLastHttpCode() != 200)
        {
            $this->clearSessionData();
            throw new InternalErrorException('アクセストークンの取得に失敗しました');
        }

        $this->setAccessToken($access_token["oauth_token"], $access_token["oauth_token_secret"]);
        $this->setUserId($access_token["user_id"]);

        return $this->getAccessToken();
    }

    /**
     * TwitterAPIに接続するためのconnectionを取得する
     *
     * return TwitterOAuth
     */
    private function createConnection()
    {
        $access_token = $this->getAccessToken();

        return new TwitterOAuth(self::TWITTER_CK, self::TWITTER_CS, $access_token["token"], $access_token["token_secret"]);
    }

    /**
     *  セッションにOAuth認証のトークンを保存する
     */
    private function setRequestToken($oauth_token, $oauth_token_secret)
    {
        $this->session->write('Twitter.oauth_token', array("token" => $oauth_token, "token_secret" => $oauth_token_secret));
    }

    /**
     * セッション情報からOAuth認証のトークンを取得する
     *
     * @return null | array["token", "token_secret", ]
     */
    private function getRequestToken()
    {
        return $this->session->read('Twitter.oauth_token');
    }

    /**
     * セッションにTwitterのアクセストークンを保存する
     */
    private function setAccessToken($oauth_token, $oauth_token_secret)
    {
        $this->session->write('Twitter.access_token', array("token" => $oauth_token, "token_secret" => $oauth_token_secret));
    }

    /**
     * セッション情報からTwitterのアクセストークンを取得する
     *
     * @return null | array["token", "token_secret"]
     */
    private function getAccessToken()
    {
        return $this->session->read('Twitter.access_token');
    }

    /**
     * セッションにTwitterのuser_idを保存する
     */
    private function setUserId($user_id)
    {
        $this->session->write('Twitter.user_id', $user_id);
    }

    /**
     * セッション情報からTwitterのuser_idを取得する
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->session->read('Twitter.user_id');
    }

    /**
     * セッションに保存されているOAuth認証情報などをクリアする
     */
    private function clearSessionData()
    {
        $this->session->delete('Twitter.oauth_token');
        $this->session->delete('Twitter.access_token');
        $this->session->delete('Twitter.user_id');
    }

    public function getTimeLineImages(){
        $connection = new TwitterOAuth(self::TWITTER_CK, self::TWITTER_CS, self::TWITTER_AK, self::TWITTER_AS);
        $user_params = ['count' => '200'];
        $timeLine = $connection->get('statuses/user_timeline', $user_params);
        $result = json_decode(json_encode($timeLine), true);
        $images = [];
        print_r($result);
        foreach($result as $ele){
            $time = $ele->created_at;
            foreach((array)$ele->extended_entities->media as $vaelu_media){
                if($vaelu_media->type == 'photo'){
                    $images[$vaelu_media->media_url] = $time;
                }
            }
            
        }
        return $images;
    }
}

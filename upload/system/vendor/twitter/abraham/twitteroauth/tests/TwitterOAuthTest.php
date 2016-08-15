<?php
/**
 * WARNING: Running these tests will post and delete through the actual Twitter account.
 */
namespace Abraham\TwitterOAuth\Test;

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterOAuthTest extends \PHPUnit_Framework_TestCase
{
    /** @var TwitterOAuth */
    protected $twitter;

    protected function setUp()
    {
        $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
    }

    public function testBuildClient()
    {
        $this->assertObjectHasAttribute('consumer', $this->twitter);
        $this->assertObjectHasAttribute('token', $this->twitter);
    }

    public function testSetOauthToken()
    {
        $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $twitter->setOauthToken(ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
        $this->assertObjectHasAttribute('consumer', $twitter);
        $this->assertObjectHasAttribute('token', $twitter);
        $twitter->get('friendships/show', array('target_screen_name' => 'twitterapi'));
        $this->assertEquals(200, $twitter->getLastHttpCode());
    }

    public function testOauth2Token()
    {
        $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $result = $twitter->oauth2('oauth2/token', array('grant_type' => 'client_credentials'));
        $this->assertEquals(200, $twitter->getLastHttpCode());
        $this->assertObjectHasAttribute('token_type', $result);
        $this->assertObjectHasAttribute('access_token', $result);
        $this->assertEquals('bearer', $result->token_type);
        return $result;
    }

    /**
     * @depends testOauth2Token
     */
    public function testBearerToken($accessToken)
    {
        $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, null, $accessToken->access_token);
        $result = $twitter->get('statuses/user_timeline', array('screen_name' => 'twitterapi'));
        if ($twitter->getLastHttpCode() !== 200) {
            $this->assertEquals('foo', substr($accessToken->access_token, 0, 75));
            $this->assertEquals('foo', print_r($result, true));
        }
        $this->assertEquals(200, $twitter->getLastHttpCode());
        return $accessToken;
    }

    // This causes issues for parallel run tests.
    // /**
    //  * @depends testBearerToken
    //  */
    // public function testOauth2TokenInvalidate($accessToken)
    // {
    //     $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    //     // HACK: access_token is already urlencoded but gets urlencoded again breaking the invalidate request.
    //     $result = $twitter->oauth2(
    //         'oauth2/invalidate_token',
    //         array('access_token' => urldecode($accessToken->access_token))
    //     );
    //     $this->assertEquals(200, $twitter->getLastHttpCode());
    //     $this->assertObjectHasAttribute('access_token', $result);
    //     return $result;
    // }

    public function testOauthRequestToken()
    {
        $twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $result = $twitter->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
        $this->assertEquals(200, $twitter->getLastHttpCode());
        $this->assertArrayHasKey('oauth_token', $result);
        $this->assertArrayHasKey('oauth_token_secret', $result);
        $this->assertArrayHasKey('oauth_callback_confirmed', $result);
        $this->assertEquals('true', $result['oauth_callback_confirmed']);
        return $result;
    }

    /**
     * @expectedException \Abraham\TwitterOAuth\TwitterOAuthException
     * @expectedExceptionMessage Could not authenticate you
     */
    public function testOauthRequestTokenException()
    {
        $twitter = new TwitterOAuth('CONSUMER_KEY', 'CONSUMER_SECRET');
        $result = $twitter->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
        return $result;
    }

    /**
     * @expectedException \Abraham\TwitterOAuth\TwitterOAuthException
     * @expectedExceptionMessage Invalid oauth_verifier parameter
     * @depends testOauthRequestToken
     */
    public function testOauthAccessTokenTokenException(array $requestToken)
    {
        // Can't test this without a browser logging into Twitter so check for the correct error instead.
        $twitter = new TwitterOAuth(
            CONSUMER_KEY,
            CONSUMER_SECRET,
            $requestToken['oauth_token'],
            $requestToken['oauth_token_secret']
        );
        $twitter->oauth("oauth/access_token", array("oauth_verifier" => "fake_oauth_verifier"));
    }

    public function testUrl()
    {
        $url = $this->twitter->url('oauth/authorize', array('foo' => 'bar', 'baz' => 'qux'));
        $this->assertEquals('https://api.twitter.com/oauth/authorize?foo=bar&baz=qux', $url);
    }

    public function testGetAccountVerifyCredentials()
    {
        // Include entities boolean added to test parameter value cohearsion
        $this->twitter->get('account/verify_credentials', array("include_entities" => false));
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
    }

    // BUG: testing is too unreliable for now
    // public function testSetProxy()
    // {
    //     $this->twitter->setProxy(array(
    //         'CURLOPT_PROXY' => PROXY,
    //         'CURLOPT_PROXYUSERPWD' => PROXYUSERPWD,
    //         'CURLOPT_PROXYPORT' => PROXYPORT,
    //     ));
    //     $this->twitter->setTimeouts(60, 60);
    //     $result = $this->twitter->get('account/verify_credentials');
    //     $this->assertEquals(200, $this->twitter->getLastHttpCode());
    //     $this->assertObjectHasAttribute('id', $result);
    // }

    public function testGetStatusesMentionsTimeline()
    {
        $this->twitter->get('statuses/mentions_timeline');
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
    }

    public function testGetSearchTweets()
    {
        $result = $this->twitter->get('search/tweets', array('q' => 'twitter'));
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
        return $result->statuses;
    }

    /**
     * @depends testGetSearchTweets
     */
    public function testGetSearchTweetsWithMaxId($statuses)
    {
        $maxId = array_pop($statuses)->id_str;
        $this->twitter->get('search/tweets', array('q' => 'twitter', 'max_id' => $maxId));
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
    }

    public function testPostFavoritesCreate()
    {
        $result = $this->twitter->post('favorites/create', array('id' => '6242973112'));
        if ($this->twitter->getLastHttpCode() == 403) {
            // Status already favorited
            $this->assertEquals(139, $result->errors[0]->code);
        } else {
            $this->assertEquals(200, $this->twitter->getLastHttpCode());
        }
    }

    /**
     * @depends testPostFavoritesCreate
     */
    public function testPostFavoritesDestroy()
    {
        $this->twitter->post('favorites/destroy', array('id' => '6242973112'));
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
    }

    public function testPostStatusesUpdateWithMedia()
    {
        $this->twitter->setTimeouts(60, 30);
        // Image source https://www.flickr.com/photos/titrans/8548825587/
        $file_path = __DIR__ . '/kitten.jpg';
        $result = $this->twitter->upload('media/upload', array('media' => $file_path));
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
        $this->assertObjectHasAttribute('media_id_string', $result);
        $parameters = array('status' => 'Hello World ' . time(), 'media_ids' => $result->media_id_string);
        $result = $this->twitter->post('statuses/update', $parameters);
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
        if ($this->twitter->getLastHttpCode() == 200) {
            $result = $this->twitter->post('statuses/destroy/' . $result->id_str);
        }
        return $result;
    }

    public function testPostStatusesUpdateWithMediaChunked()
    {
        $this->twitter->setTimeouts(60, 30);
        // Video source http://www.sample-videos.com/
        $file_path = __DIR__ . '/video.mp4';
        $result = $this->twitter->upload('media/upload', array('media' => $file_path, 'media_type' => 'video/mp4'), true);
        $this->assertEquals(201, $this->twitter->getLastHttpCode());
        $this->assertObjectHasAttribute('media_id_string', $result);
        $parameters = array('status' => 'Hello World ' . time(), 'media_ids' => $result->media_id_string);
        $result = $this->twitter->post('statuses/update', $parameters);
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
        if ($this->twitter->getLastHttpCode() == 200) {
            $result = $this->twitter->post('statuses/destroy/' . $result->id_str);
        }
        return $result;
    }

    public function testPostStatusesUpdateUtf8()
    {
        $result = $this->twitter->post('statuses/update', array('status' => 'xこんにちは世界 ' . time()));
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
        return $result;
    }

    /**
     * @depends testPostStatusesUpdateUtf8
     */
    public function testPostStatusesDestroy($status)
    {
        $this->twitter->post('statuses/destroy/' . $status->id_str);
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
    }

    public function testLastResult()
    {
        $this->twitter->get('search/tweets', array('q' => 'twitter'));
        $this->assertEquals('search/tweets', $this->twitter->getLastApiPath());
        $this->assertEquals(200, $this->twitter->getLastHttpCode());
        $this->assertObjectHasAttribute('statuses', $this->twitter->getLastBody());
    }

    /**
     * @depends testLastResult
     */
    public function testResetLastResponse()
    {
        $this->twitter->resetLastResponse();
        $this->assertEquals('', $this->twitter->getLastApiPath());
        $this->assertEquals(0, $this->twitter->getLastHttpCode());
        $this->assertEquals(array(), $this->twitter->getLastBody());
    }
}

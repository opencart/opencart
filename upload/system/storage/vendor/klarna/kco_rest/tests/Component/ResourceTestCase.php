<?php
/**
 * Copyright 2014 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing the resource base test case class.
 */

namespace Klarna\Rest\Tests\Component;

use GuzzleHttp\Message\RequestInterface;

/**
 * Resource base test case class.
 */
abstract class ResourceTestCase extends TestCase
{
    /**
     * Asserts that the authorization header is correct.
     *
     * @param RequestInterface $request Request to test
     *
     * @return void
     */
    protected function assertAuthorization(RequestInterface $request)
    {
        list($alg, $digest) = explode(' ', $request->getHeader('Authorization'));

        $this->assertEquals('Basic', $alg);

        $expected = self::MERCHANT_ID . ':' . self::SHARED_SECRET;
        $this->assertEquals($expected, base64_decode($digest));
    }
}

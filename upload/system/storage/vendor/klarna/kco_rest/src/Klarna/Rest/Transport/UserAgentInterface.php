<?php
/**
 * Copyright 2019 Klarna AB
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
 * File containing the UserAgent interface.
 */

namespace Klarna\Rest\Transport;

/**
 * The user agent is used to help identify the client and provide additional
 * information when assistance is needed with troubleshooting.
 *
 * In addition to following the User Agent as specified by RFC 2616, section 14.43,
 * there are a number of predefined fields which should be used when applicable:
 *
 * Library:
 * Information about the SDK and version being used.
 *
 * Library/[Library name]_[version]
 *
 * Examples:
 * Library/Klarna.SDK_1.0.0
 *
 *
 * Language:
 * This is used to indicate which programming language and version is being used.
 *
 * Language/[Language]_[version]
 *
 * Example:
 * Language/PHP_5.5.9-1ubuntu4.4
 *
 *
 * OS:
 * Information on what operating system the merchant is using for their web server.
 *
 * OS/[Operating system]_[version and architecture]
 *
 * Examples:
 * OS/Linux_3.13.5-101.fc19.x86_64
 *
 *
 * Partner:
 * This is used to indicate if the integration is done using a partner.
 *
 * Partner/[Partner name]
 *
 * Examples:
 * Partner/ShopPartnerName
 *
 *
 * Platform:
 * Information about which e-commerce platform is used to integrate.
 *
 * Platform/[Platform name]_[version]
 *
 * Examples:
 * Platform/Magento_1.2.3
 * Platform/Opencart_1.5.6.4
 *
 *
 * Module:
 * Used in conjunction with platform to provide information about the platform
 * plugin.
 *
 * Module/[Module name]_[version]
 *
 * Examples:
 * Module/Klarna.Module_1.0.0
 *
 *
 * Webserver:
 * Information about the web server being used.
 *
 * Webserver/[Webserver name]_[version]
 *
 * Examples:
 * Webserver/Apache_2.4.1
 * Webserver/Nginx_1.7.6
 * Webserver/IIS_7.5
 *
 *
 * Each component is written as key and name separated by slash, optionally followed
 * by an underscore and the version. After which, separated by space, additional
 * information can be added using parenthesis where individual elements inside are
 * separated by semi-colon.
 *
 * All of the component strings are then combined using space as the delimiter.
 *
 * Specifying SDK dependencies for the Library field could be achieved as follows:
 * Library/Klarna.SDK_1.0.0 (Guzzle/4.2.2 ; curl/7.35.0)
 *
 * Full user agent example:
 * Library/Klarna.SDK_1.0.0 Language/PHP_5.5.9 OS/Linux_3.13.5-101.fc19.x86_64
 */
interface UserAgentInterface
{
    /**
     * Serialises the user agent.
     *
     * @return string
     */
    public function __toString();
}

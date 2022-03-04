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
 * File containing the Connector class.
 */

namespace Klarna\Rest\Transport;

/**
 * DEPRECATED: Transport connector used to authenticate and make HTTP requests against the
 * Klarna APIs. Transport uses Guzzle HTTP client to perform HTTP(s) calls.
 *
 * @deprecated Use GuzzleConnector class instead. Keeps for the backward-compatibility purposes only.
 */
class Connector extends GuzzleConnector
{
}

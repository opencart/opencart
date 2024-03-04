<?php 

#[\Until('8.2')]
function mysqli_ssl_set(\mysqli $mysql, ?string $key, ?string $certificate, ?string $ca_certificate, ?string $ca_path, ?string $cipher_algos) : bool
{
}
#[\Since('8.2')]
function mysqli_ssl_set(\mysqli $mysql, ?string $key, ?string $certificate, ?string $ca_certificate, ?string $ca_path, ?string $cipher_algos) : true
{
}
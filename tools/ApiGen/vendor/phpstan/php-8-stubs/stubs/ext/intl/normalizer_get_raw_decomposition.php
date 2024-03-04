<?php 

#if U_ICU_VERSION_MAJOR_NUM >= 56
function normalizer_get_raw_decomposition(string $string, int $form = Normalizer::FORM_C) : ?string
{
}
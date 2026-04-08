<?php

use Stichoza\GoogleTranslate\GoogleTranslate;

function translateText($text, $targetLang)
{
    if ($targetLang == 'en') {
        return $text;
    }

    $tr = new GoogleTranslate();
    $tr->setTarget($targetLang);

    return $tr->translate($text);
}


function batchTranslate(array $texts, $targetLang)
{
    if ($targetLang === 'en') {
        return $texts;
    }

    try {
        $tr = new GoogleTranslate();
        $tr->setTarget($targetLang);

        // GoogleTranslate supports array input
        return $tr->translate($texts);

    } catch (\Exception $e) {
        return $texts; // fallback
    }
}
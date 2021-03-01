<?php

namespace Mateusjatenee\MagicTest;

use Illuminate\Support\Str;
use Mateusjatenee\MagicTest\Grammar\Grammar;

class FileEditor
{
    public function process(string $content, $grammar, string $method)
    {
        $arrayContent = explode("\n", $content);


        $test = "\n";


        $after = Str::of($content)
            ->after($method)
            ->after('$browser->')
            ->before("});\n");

        $after = explode("\n", $after)[0];


        $started = false;
        $newText = [];

        foreach ($arrayContent as $key => $subContent) {
            if (Str::contains(trim($subContent), trim($after))) {
                $started = true;
                $newText[] = Str::replaceLast(";", "", $subContent);
                foreach ($grammar as $grammarKey => $g) {
                    $isLast = ($grammarKey + 1) == $grammar->count();
                    $test = $g->build($isLast);
                    $newText[] = $test;
                }



                if (trim($arrayContent[$key + 1]) == "") {
                    $started = false;
                }
            } else {
                if ($started) {
                    if (Str::endsWith(trim($subContent), ';')) {
                        $started = false;

                        continue;
                    } else {
                        continue;
                    }
                }
                $newText[] = $subContent;
            }
        }


        return implode("\n", $newText);

        $matches = [];

        $toReplace = (string) Str::of($content)
            ->after($method)
            ->after($after)
            ->before(');') . ');';

        // When it only has a visits call
        if (
            Str::endsWith(preg_replace('~(\v|\t|\s{2,})~m', '', $toReplace), '});')) {
            $prependNewTest = "\n" . Grammar::indent('$browser');
            $test = ";\n" . $prependNewTest . $test;
            $toReplace = ');';
        }




        $newContent = Str::replaceFirst($toReplace, $test, $content);

        return $newContent;
    }
}

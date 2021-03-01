<?php

namespace Mateusjatenee\MagicTest;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FileEditor
{
    public function process(string $content, Collection $grammar, string $method): string
    {
        $arrayContent = explode("\n", $content);

        $after = strtok(Str::of($content)
            ->after($method)
            ->after('$browser->')
            ->before("});\n"), "\n");


        $writingStarted = false;
        $newText = [];

        foreach ($arrayContent as $key => $subContent) {
            if (Str::contains(trim($subContent), trim($after))) {
                $writingStarted = true;
                $newText[] = Str::replaceLast(";", "", $subContent);

                foreach ($grammar as $grammarKey => $g) {
                    $isLast = ($grammarKey + 1) === $grammar->count();
                    $newText[] = $g->build() . ($isLast ? ';' : '');
                }


                if (empty($arrayContent[$key + 1])) {
                    $writingStarted = false;
                }
            } else {
                if ($writingStarted) {
                    if (Str::endsWith(trim($subContent), ';')) {
                        $writingStarted = false;

                        continue;
                    } else {
                        continue;
                    }
                }
                $newText[] = $subContent;
            }
        }


        return implode("\n", $newText);
    }
}

<?php

namespace App\Service;

class MarkupParserService
{
    public function parse(string $text): string
    {
        $lines = explode("\n", $text);
        $html = '';
        $listType = [];
        $tableRowOpen = false;

        foreach ($lines as $line) {
            // Basic escaping
            $line = str_replace('&', '&amp;', $line);

            $line = str_replace('<', '&lt;', $line);
            $line = str_replace('>', '&gt;', $line);

            // Headings
            $line = preg_replace('/^# (.*)$/', '<h1>$1</h1>', $line);
            $line = preg_replace('/^## (.*)$/', '<h2>$1</h2>', $line);
            $line = preg_replace('/^### (.*)$/', '<h3>$1</h3>', $line);
            $line = preg_replace('/^#### (.*)$/', '<h4>$1</h4>', $line);
            $line = preg_replace('/^##### (.*)$/', '<h5>$1</h5>', $line);

            // Horizontal rule
            $line = preg_replace('/^----$/', '<hr>', $line);

            // Lists
            $line = $this->listHandler($line, $listType);

            // Tables
            [$line, $tableRowOpen] = $this->tableHandler($line, $tableRowOpen);

            // Paragraph
            if (!preg_match('/^</', $line)) {
                $line = "<p>$line</p>";
            }

            // Inline markups
            $line = preg_replace('/(?<!\\\\)\*\*(.*?)\*\*/', '<b>$1</b>', $line);
            $line = preg_replace('/(?<!\\\\)\*(.*?)\*/', '<i>$1</i>', $line);
            $line = preg_replace('/(?<!\\\\)~~(.*?)~~/', '<s>$1</s>', $line);
            $line = preg_replace('/(?<!\\\\)__(.*?)__/', '<u>$1</u>', $line);
            $line = preg_replace('/(?<!\\\\)`(.*?)`/', '<code>$1</code>', $line);
            
            $line = str_replace('\n', '<br>', $line);
            $line = preg_replace('/(?<!\\\\)\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $line);

            // Backslashes
            $line = str_replace('\\\\', '&#92;', $line);
            $line = str_replace('\\', '', $line);

            $html .= $line;
        }

        return $html;
    }

    private function listHandler(string $line, array &$listType): string
    {
        $listDictionary = ['*' => 'ul', '-' => 'ol'];
        $prefixCount = $this->countPrefix($line);
        $listDepth = count($listType);
        $output = $line;

        if ($prefixCount > 0) {
            $output = substr($line, $prefixCount + 1);
            $output = "<li>$output</li>";

            for ($i = $listDepth; $i < $prefixCount; $i++) {
                $markup = $listDictionary[$line[$i]];
                $output = "<$markup>$output";
                $listType[] = $line[$prefixCount - 1];
            }
        }

        for ($i = $listDepth; $i > $prefixCount; $i--) {
            $markup = $listDictionary[array_pop($listType)];
            $output = "</$markup>\n$output";
        }

        return $output;
    }

    private function countPrefix(string $line): int
    {
        if (preg_match('/^([*\-]+)(?= )/', $line, $matches)) {
            return strlen($matches[1]);
        }
        return 0;
    }

    private function tableHandler(string $line, bool $tableRowOpen): array
    {
        $modifiedLine = $line;

        if (preg_match('/^\{\|/', $line)) {
            $modifiedLine = substr($modifiedLine, 3);
            $modifiedLine = "<table $modifiedLine>";

        } elseif (preg_match('/^\|\}/', $line)) {
            $modifiedLine = "</tr></table>";
            $tableRowOpen = false;

        } elseif (preg_match('/^\|-/',$line)) {
            if (!$tableRowOpen) {
                $modifiedLine = "<tr>";
                $tableRowOpen = true;
            } else {
                $modifiedLine = "</tr><tr>";
            }

        } elseif (preg_match('/^! /', $line)) {
            $modifiedLine = $this->tableCutAndMarkup($line, 2, 'th');

        } elseif (preg_match('/^\| /', $line)) {
            $modifiedLine = $this->tableCutAndMarkup($line, 2, 'td');

        } elseif (preg_match('/^\|\+ /', $line)) {
            $modifiedLine = $this->tableCutAndMarkup($line, 3, 'caption');
        }

        return [$modifiedLine, $tableRowOpen];
    }

    private function tableCutAndMarkup(string $line, int $prefix, string $markup): string
    {
        $output = substr($line, $prefix);
        $markupStart = '';

        if (preg_match('/(?<!\\\\)\|/', $output)) {
            preg_match('/^(.*?).\|/', $output, $matches);
            $markupStart = "<$markup {$matches[1]}>";
            preg_match('/\|.(.*)$/', $output, $matches2);
            $output = $matches2[1];
        } else {
            $markupStart = "<$markup>";
        }

        return "$markupStart$output</$markup>";
    }
}

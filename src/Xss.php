<?php

namespace Ebcms;

use DOMDocument;

class Xss
{

    private $white_list = [
        'a' => ['target', 'href', 'title'],
        'abbr' => ['title'],
        'address' => [],
        'area' => ['shape', 'coords', 'href', 'alt'],
        'article' => [],
        'aside' => [],
        'audio' => ['autoplay', 'controls', 'loop', 'preload', 'src'],
        'b' => [],
        'bdi' => ['dir'],
        'bdo' => ['dir'],
        'big' => [],
        'blockquote' => ['cite'],
        'br' => [],
        'caption' => [],
        'center' => [],
        'cite' => [],
        'code' => [],
        'col' => ['align', 'valign', 'span', 'width'],
        'colgroup' => ['align', 'valign', 'span', 'width'],
        'dd' => [],
        'del' => ['datetime'],
        'details' => ['open'],
        'div' => [],
        'dl' => [],
        'dt' => [],
        'em' => [],
        'font' => ['color', 'size', 'face'],
        'footer' => [],
        'h1' => [],
        'h2' => [],
        'h3' => [],
        'h4' => [],
        'h5' => [],
        'h6' => [],
        'header' => [],
        'hr' => [],
        'i' => [],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'align', 'vspace', 'hspace'],
        'ins' => ['datetime'],
        'li' => [],
        'mark' => [],
        'nav' => [],
        'ol' => [],
        'p' => [],
        'pre' => [],
        's' => [],
        'section' => [],
        'small' => [],
        'span' => ['style'],
        'sub' => [],
        'sup' => [],
        'strong' => [],
        'table' => ['width', 'border', 'align', 'valign'],
        'tbody' => ['align', 'valign'],
        'td' => ['width', 'rowspan', 'colspan', 'align', 'valign'],
        'tfoot' => ['align', 'valign'],
        'th' => ['width', 'rowspan', 'colspan', 'align', 'valign'],
        'thead' => ['align', 'valign'],
        'tr' => ['rowspan', 'align', 'valign'],
        'tt' => [],
        'u' => [],
        'ul' => [],
        'video' => ['autoplay', 'controls', 'loop', 'preload', 'src', 'height', 'width'],
        'embed' => ['src', 'height', 'align', 'width', 'type', 'pluginspage', 'wmode', 'play', 'loop', 'menu', 'allowscriptaccess', 'allowfullscreen'],
        'source' => ['src', 'type'],
    ];

    public function clear($html, array $white_list = null): string
    {
        if (is_null($white_list)) {
            $white_list = $this->white_list;
        }
        $white_list['html'] = [];
        $white_list['body'] = [];
        $xml = new DOMDocument();
        libxml_use_internal_errors(true);
        if ($xml->loadHTML('<!DOCTYPE HTML><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>' . $html . '</body></html>')) {
            start:foreach ($xml->getElementsByTagName("*") as $element) {
                if (!isset($white_list[$element->tagName])) {
                    $element->parentNode->removeChild($element);
                    goto start;
                } else {
                    for ($k = $element->attributes->length - 1; $k >= 0; --$k) {
                        if (!in_array($element->attributes->item($k)->nodeName, $white_list[$element->tagName])) {
                            $element->removeAttributeNode($element->attributes->item($k));
                        } elseif (in_array($element->attributes->item($k)->nodeName, ['href', 'src', 'style', 'background', 'size'])) {
                            $_keywords = ['javascript:', 'javascript.:', 'vbscript:', 'vbscript.:', ':expression'];
                            $find = false;
                            foreach ($_keywords as $a => $b) {
                                if (false !== strpos(strtolower($element->attributes->item($k)->nodeValue), $b)) {
                                    $find = true;
                                }
                            }
                            if ($find) {
                                $element->removeAttributeNode($element->attributes->item($k));
                            }
                        }
                    }
                }
            }
        }
        return substr($xml->saveHTML($xml->documentElement), 12, -14);
    }
}
